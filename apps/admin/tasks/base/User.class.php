<?php

class User extends nomvcDatabaseUser {

    protected $dbTable = 't_member';		// таблица, в которой живут пользователи
    protected $dbLogin = 'login';		// поле с логином полдьзователя
    protected $dbPassword = 'passwd';	// поле с паролем пользователя

    private $dbHelper;

    public function init(){
        parent::init();
        $this->dbHelper = $this->context->getDbHelper();
    }

    public function signin($login, $password) {
        //var_dump($this->dbLogin); exit;
        $sql = sprintf('select * from %s where lower(%s) = lower(:%s) and %s =:%s',
            $this->dbTable,
            $this->dbLogin,
            $this->dbLogin,
            $this->dbPassword,
            $this->dbPassword
        );
        $this->dbHelper->addQuery('check_user', $sql);
        $user = $this->dbHelper->selectRow('check_user', array($this->dbLogin => $login, $this->dbPassword => $password));

        if ($user === false) return false;
        foreach ($user as $key => $val) {
            $this->setAttribute(strtolower($key), $val);
        }

        //if($this->getUserLevel() > self::USER_LEVEL_AVAILABLE) return false;

        $this->setAttribute('has_auth', true);
        return true;
    }

}
