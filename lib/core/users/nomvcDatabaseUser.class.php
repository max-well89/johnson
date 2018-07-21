<?php

/**
 * Абстрактный пользователь, данные для авторизации которого можно найти в БД
 */
abstract class nomvcDatabaseUser extends nomvcSessionUser {

    /** @var string таблица, в которой живут пользователи */
    protected $dbTable = null;
    /** @var string поле с логином пользователя */
    protected $dbLogin = null;
    /** @var string поле с паролем пользователя */
    protected $dbPassword = null;
    /** @var dbHelper поле с паролем пользователя */
    private $dbHelper;

    /** @const уровень пользователя, с которого начинается доступ в Админку */
    const USER_LEVEL_AVAILABLE = 3;

    public function init(){
        parent::init();
        $this->dbHelper = $this->context->getDbHelper();
    }
    
    public function hasBlock($login, $password){
        $sql = sprintf('select * from %s where lower(%s) = lower(:%s) and %s=:%s and id_status != 1',
            $this->dbTable,
            $this->dbLogin,
            $this->dbLogin,
            $this->dbPassword,
            $this->dbPassword
        );
        
        
        $this->dbHelper->addQuery('check_user_block', $sql);
        if ($this->dbHelper->selectRow('check_user_block', array($this->dbLogin => $login, $this->dbPassword => $password))){
            return true;
        }
        
        
        /**/
        /*
        $sql = sprintf('
            select * 
            from T_MEMBER tm
            --inner join T_RESTAURANT tr on tm.id_restaurant = tr.id_restaurant
            where %s =:%s
            and %s =:%s
            and tr.id_status != 1',
            $this->dbLogin,
            $this->dbLogin,
            $this->dbPassword,
            $this->dbPassword
        );
        $this->dbHelper->addQuery('check_restaurant_block', $sql);
        if ($this->dbHelper->selectRow('check_restaurant_block', array($this->dbLogin => $login, $this->dbPassword => $password))){
            return true;
        }*/

        return false;
    }

    /**
     * Авторизация
     * @param string $login		логин
     * @param string $password	пароль
     * @return mixed
     */
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

    /**
     * Проверка доступа к модулю, может вернуть 4 результата: 
     * 0 - нет доступа, 
     * 1 - чтение, 
     * 3 - запись, 
     * 7 - удаление
     * @param string $module	имя модуля, из адресной строки
     * @return mixed
     */
    public function checkAccess($module) {
        if(empty($module)) return 0;
        $this->dbHelper->addQuery('check_access', '
            select id_access_type
            from t_module_role mdlrl
            inner join t_module mdl on mdl.id_module = mdlrl.id_module
            inner join t_member_role mbrl on mbrl.id_role = mdlrl.id_role
            where mbrl.id_member = :id_member 
            and lower(mdl.module) = lower(:module)
        ');
        $id_access_type = $this->dbHelper->selectValue('check_access', array(":module" => $module, ":id_member" => $this->getUserID()));
        
        return empty($id_access_type) ? 0 : $id_access_type;
    }

    public function getModuleDefault(){
        $this->dbHelper->addQuery('get_module_default', '
            select path
            FROM t_module_role mdlrl
            inner join t_module mdl on mdl.id_module = mdlrl.id_module
            inner join t_member_role mbrl on mbrl.id_role = mdlrl.id_role
            where mbrl.id_member = :id_member
            order by mdl.order_by_module asc
        ');
        $row = $this->dbHelper->selectValue('get_module_default', array(":id_member" => $this->getUserID()));
        
        return $row;
    }

    /**
     * ID авторизованного пользователя или 0
     */
    public function getUserID() {
        return $this->getAttribute("id_member", 0);
    }

    /**
     * Возвращает максимальный уровень пользователя по всем ролям, которые у него есть
     */
    public function getUserLevel() {
        if(empty($this->getAttribute('role_level'))){
            //$this->dbHelper->addQuery('select_user_level', "select roles_level from v_member_role where id_member = :id_member");
            //$role_level = $this->dbHelper->selectValue('select_user_level', array(":id_member" => $this->getUserID()));
            
            $role_level = 7;
            
            $this->setAttribute("role_level", $role_level);
        }

        return $this->getAttribute('role_level');
    }


    /**
     * Возвращает массив ролей пользователя
     */
    public function getUserRoles() {
        if(empty($this->getAttribute('roles'))){
            $this->dbHelper->addQuery('select_roles', '
                select rl.id_role, rl.description, rl.order_by_roles
                from t_module_role mbrl 
                inner join t_role rl on rl.id_role = mbrl.id_role
                where mbrl.id_member =  :id_member
                order by rl.order_by_roles');
            $stmt = $this->dbHelper->select('select_roles', array(":id_member" => $this->getUserID()));
            $roles_array = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row = array_change_key_case($row);
                $roles_array[$row["id_role"]] = $row;

            }
            $this->setAttribute("roles", $roles_array);
        }

        return $this->getAttribute('roles');
    }
    
}
