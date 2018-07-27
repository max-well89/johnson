<?php

class Database
{

    private static $instance;

    private $conn;

    public function __construct($connstr, $login, $password)
    {
        try {
            $this->conn = new PDO($connstr, $login, $password);
            //$this->conn->prepare('SET CHARSET \'utf8\'')->execute();
            //$this->conn->prepare('ALTER SESSION SET NLS_DATE_FORMAT = "YYYY-MM-DD HH24:MI:SS"')->execute();
            $this->conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        } catch (PDOException $ex) {
            //throw new BaseAPIException('critical error, try later', BaseAPIException::DATABASE_ERROR);
        }
        if (!($this->conn instanceof PDO)) {
            //throw new BaseAPIException('critical error, try later', BaseAPIException::DATABASE_ERROR);
        }
        self::$instance = $this;
        $this->conn->beginTransaction();
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

}


?>
