<?php

class UpdatePushStatusAction extends AbstractAction {
    public function getTitle() {
        return 'статус устройства для пушей';
    }

    public function init() {
        parent::init();

        $this->addParameter('token', new agStringValidator(array('required' => true)), 'Token');
        $this->addParameter('push_token', new agStringValidator(array('required' => false)), 'Токен для пушей');

        $this->dbHelper->addQuery($this->getAction().'/save_device', '
            insert into t_member_device (id_member, push_token) values (:id_member, :push_token)
        ');

        $this->dbHelper->addQuery($this->getAction().'/save_device', '
            insert into t_member_device (id_member, push_token) values (:id_member, :push_token)
        ');
    }

    public function execute() {
        $member = $this->auth();

        if (isset($member['id_member'])){
            // save member device
            try {
                $this->dbHelper->execute($this->getAction().'/save_device', array(
                    'id_member' => $member['id_member'],
                    'push_token' => $this->getValue('push_token')
                ));
            }
            catch(exception $e){}

            $token = $this->generateToken($member['id_member'], $member['login']);

            return array('result' => Errors::SUCCESS, 'data' => [
                'token' => $token,
                'token_life_time' => $this->token_life_time
            ]);

        }
        else
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);

        return array('result' => Errors::FAIL);
    }

    public function getResponseExample() {
        return json_decode('{
  "response": {
    "result": 100,
    "data": {
      "token": "9ca7e09bee58071a1291c3ef5f3dd29137a918e26e7957463c6206b060bca547457c61a95ebf22154e82d113550a8c413c19d02cb088acbb0e9d64a0924d2754",
      "token_life_time": 1
    }
  }
}');
    }
}
