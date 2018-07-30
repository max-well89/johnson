<?php

class UpdatePushStatusAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Установить статус устройства для пушей';
    }

    public function init()
    {
        parent::init();

        $this->addParameter('token', new agStringValidator(array('required' => true)), 'Token');
        $this->addParameter('push_token', new agStringValidator(array('required' => false)), 'Токен для пушей');
        $this->addParameter('is_active', new agBooleanValidator(array('required' => false)), 'true - активируем токен и false наоборот');

        $this->dbHelper->addQuery($this->getAction() . '/save_device', '
            insert into t_member_device (id_member, push_token, id_os) values (:id_member, :push_token, :id_os)
        ');

        $this->dbHelper->addQuery($this->getAction() . '/delete_device', '
            delete from t_member_device where id_member = :id_member and push_token = :push_token
        ');
    }

    public function execute()
    {
        $member = $this->authByToken();

        if (isset($member['id_member'])) {
            if ($this->getValue('is_active')) {
                $this->dbHelper->execute($this->getAction() . '/save_device', array(
                    'id_member' => $member['id_member'],
                    'push_token' => $this->getValue('push_token'),
                    'id_os' => $this->context->getUser()->getAttribute('os')
                ));
            } else {
                $this->dbHelper->execute($this->getAction() . '/delete_device', array(
                    'id_member' => $member['id_member'],
                    'push_token' => $this->getValue('push_token')
                ));
            }

            return array('result' => Errors::SUCCESS);
        } else
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);

        return array('result' => Errors::FAIL);
    }

    public function getResponseExample()
    {
        return json_decode('{
  "response": {
    "result": 100
  }
}');
    }
}
