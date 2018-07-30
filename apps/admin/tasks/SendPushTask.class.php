<?php

class SendPushTask extends nomvcBaseTask
{

    const RESULT_OK = 0;
    const RESULT_DEVICE_ID_ERR = 1;

    public function exec($params)
    {
        parent::exec($params);
        $dbHelper = $this->context->getDbHelper();
        $badge = 1;

        while (true) {
            $has_sended = false;

            $stmt = $dbHelper->select(get_class($this) . '/get-push-to-send');

            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                list($id_push, $message, $id_member, $push_token, $id_os) = $row;

                $push_data = array('id_push' => $id_push, 'message' => $message);

                if ($id_os == 1) {
                    $res = $this->sendApplePush($id_push, $push_token, $push_data, $badge);
                } else {
                    $res = $this->sendGooglePush($id_push, $push_token, $push_data, $badge);
                }


                if ($res == self::RESULT_OK) {
                    $dbHelper->execute(get_class($this) . '/push-send-log', array(
                        'id_push' => $id_push,
                        'id_member' => $id_member,
                        'message' => $message,
                        'push_token' => $push_token,
                        'id_os' => $id_os
                    ));

                    $has_sended = true;
                }

                echo "+1 push = $id_push sended to, member = $id_member, token = $push_token, message = $message\n";
            }

            if ($has_sended)
                $dbHelper->execute(get_class($this) . '/mark-push-as-send', array(
                    'id_push' => $id_push
                ));
            echo "next\n";
            sleep(5);
        }
    }

    public function sendApplePush($id_push, $device_id, $push_data, $badge)
    {
        $message = $push_data['message'];
        unset($push_data['message']);
        $push = array(
            "loc-key" => $message,
            "parameters" => $push_data
        );
        try {
            $resp = $this->applePushServer->sendMessage($device_id, $push, /*badge*/
                $badge, /*sound*/
                'default');

            if ($resp == 'Message successfully delivered') {
                echo "send $id_push to $device_id is OK\n";
                return self::RESULT_OK;
            } else {
                var_dump($resp, $device_id);
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
            return self::RESULT_DEVICE_ID_ERR;
        }
    }

    public function sendGooglePush($id_push, $device_id, $push_data, $badge)
    {
        $this->googlePushServer->newMessage(array(
            'registration_ids' => array($device_id),
            'data' => $push_data,
        ));
        $resp = $this->googlePushServer->send();
    }

    protected function init()
    {
        $dbHelper = $this->context->getDbHelper();
        $dbHelper->addQuery(get_class($this) . '/get-push-to-send', '
            select
            t_push.id_push,
            t_push.message,
            t_member_device.id_member,
            t_member_device.push_token,
            t_member_device.id_os
            from t_push
            inner join t_member on 1 = 1 and t_push.id_database = t_member.id_database
            inner join t_member_device on t_member.id_member = t_member_device.id_member and t_member_device.push_token is not null and t_member_device.id_os = 1
            where t_push.dt_send is null 
            and t_member.id_status = 1
            and t_push.id_status = 1
            and (now() > t_push.dt_start or t_push.dt_start is null)            
        ');

        $dbHelper->addQuery(get_class($this) . '/push-send-log', '
            insert into t_push_log (
                id_push,
                id_member,
                message,
                dt_send,
                push_token,
                id_os
            )
            values(
                :id_push,
                :id_member,
                :message,
                now(),
                :push_token,
                :id_os
            )
        ');


        $dbHelper->addQuery(get_class($this) . '/mark-push-as-send', '
            update t_push set id_status = 2 where id_push = :id_push
        ');


        //$this->googlePushServer = new GCMHTTPConnectionServer('AIzaSyDXA_w7sOHh_wNTUm0mmBGfM6F4jCm-sjE');
        $this->applePushServer = new ApplePushNotificationSimple('prod', $this->context);
    }
}
