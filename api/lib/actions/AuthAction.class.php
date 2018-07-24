<?php

class AuthAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Авторизация/получить токен';
    }

    public function init()
    {
        parent::init();

        $this->addParameter('login', new agStringValidator(array('required' => true)), 'Логин');
        $this->addParameter('password', new agStringValidator(array('required' => true)), 'Пароль');
        $this->addParameter('push_token', new agStringValidator(array('required' => false)), 'Токен для пушей');

        $this->dbHelper->addQuery($this->getAction() . '/save_device', '
            insert into t_member_device (id_member, push_token, id_os) values (:id_member, :push_token, :id_os)
        ');
    }

    public function execute()
    {
        $member = $this->auth();

        if (isset($member['id_member'])) {
            // save member device
            try {
                $this->dbHelper->execute($this->getAction() . '/save_device', array(
                    'id_member' => $member['id_member'],
                    'push_token' => $this->getValue('push_token'),
                    'id_os' => $this->context->getUser()->getAttribute('os')
                ));
            } catch (exception $e) {
            }

            $token = $this->generateToken($member['id_member'], $member['login']);

            return array('result' => Errors::SUCCESS, 'data' => [
                'default_language' => $member['default_language'],
                'token' => $token,
                'token_life_time' => $this->token_life_time
            ]);

        } else
            $this->throwActionException(Errors::MEMBER_NOT_FOUND);

        return array('result' => Errors::FAIL);
    }

    public function getResponseExample()
    {
        return json_decode('{
  "response": {
    "result": 100,
    "data": {
      "default_language": "en",
      "token": "9ca7e09bee58071a1291c3ef5f3dd29137a918e26e7957463c6206b060bca547457c61a95ebf22154e82d113550a8c413c19d02cb088acbb0e9d64a0924d2754",
      "token_life_time": 1
    }
  }
}');
    }
}
