<?php

/** 
 * Контекст API 
 */
abstract class agApiContext extends agContext {

    // ссылка на активный контроллер	
    private $controller;
    // текущее подключение к БД и хелпер
    private $dbconn = null;
    private $dbhelper = null;
    // логгер
    private $logger = null;
    // ссылка на активного пользователя
    private $user;
            
    /** Получение коннекта к БД */
    public function getDb() {
        if (is_null($this->dbconn)) {
            $dbconf = $this->getConfigVal('db', null);

            $this->dbconn = new PDO($dbconf['dsn'], $dbconf['user'], $dbconf['passw']);
            if (!$this->dbconn) throw new agGlobalException('no connect to DB', agAbstractApiController::FATAL_ERROR);

            //$this->dbconn->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
            $this->dbconn->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $this->dbconn->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
        }
        return $this->dbconn;
    }
    
    /** Получение инстанса хелпера */
    public function getDbHelper() {
        if (is_null($this->dbhelper)) {
            $this->dbhelper = new DbHelper($this);
        }
        return $this->dbhelper;
    }

    /** устанавливает активный контроллер */
    public function setController($controller) {
        $this->controller = $controller;
        $logger = $this->getLogger();
        $logger->setController(get_class($controller));
    }
    
    /** возвращает активный контроллер */
    public function getController() {
        return $this->controller;
    }
    
    /** возвращает логгер **/
    public function getLogger() {
        if ($this->logger == null) {
            $this->logger = new Logger($this);
        }
        return $this->logger;
    }
    
    /** устанавливает активного пользователя */
    public function setUser($user) {
        $this->user = $user;
    }
    
    /** возвращает активного пользователя */
    public function getUser() {
        return $this->user;
    }
    
    /** возвращает URL api */
    public function getApiUrl() {
        $url = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url.= 's';
        }
        $url.= '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/json.php';
        return $url;
    }
    
    /** возвращает название проекта */
    public function getProjectName() {
        return $this->getConfigVal('project_name', 'unnamed');
    }

}
