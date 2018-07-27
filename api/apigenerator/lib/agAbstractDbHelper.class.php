<?php

abstract class agAbstractDbHelper extends agAbstractComponent
{

    protected $queries = array();
    protected $stmts = array();

    /**
     * Регистрация запроса в хелпере
     */
    public function addQuery($query_code, $query_sql, $auto_prepare = false)
    {
        $this->queries[$query_code] = $query_sql;
        unset($this->stmts[$query_code]);
        if ($auto_prepare) {
            $this->getStmt($query_code);
        }
    }

    public function getStmt($query_code, $values = array(), $params = array(), $lobs = array())
    {
        if (!isset($this->stmts[$query_code])) {
            $this->stmts[$query_code] = $this->context->getDb()->prepare($this->queries[$query_code]);
        }
        $stmt = $this->stmts[$query_code];
        $this->bindValues($stmt, $values);
        $this->bindParams($stmt, $params);
        $this->bindLOBs($stmt, $lobs);
        return $stmt;
    }

    public function bindValues($stmt, $values)
    {
        foreach ($values as $name => $value) {
            $stmt->bindValue($name, $value);
        }
    }

    public function bindParams($stmt, $params)
    {
        foreach ($params as $name => $param) {
            $stmt->bindParam($name, $params[$name], PDO::PARAM_STR, 200);
        }
    }

    protected function bindLOBs($stmt, $lobs)
    {
        foreach ($lobs as $name => $lob) {
            $stmt->bindParam($name, $lob, PDO::PARAM_LOB);
        }
    }

    public function getQuery($query_code)
    {
        return isset($this->queries[$query_code]) ? $this->queries[$query_code] : false;
    }

    /**
     * выполнение запроса без возвращения результата
     */
    public function execute($query_code, $values = array(), $params = array(), $lobs = array())
    {
        $stmt = $this->getStmt($query_code);
        $this->bindValues($stmt, $values);
        $this->bindParams($stmt, $params);
        $this->bindLOBs($stmt, $lobs);
        $this->doExecute($stmt);
    }

    private function doExecute($stmt)
    {
        if (!$stmt->execute()) {
            throw new agGlobalException(serialize($stmt->errorInfo()), agAbstractApiController::FATAL_ERROR);
        }
        return $stmt;
    }

    public function select($query_code, $values = array(), $params = array(), $lobs = array())
    {
        $stmt = $this->getStmt($query_code);
        $this->bindValues($stmt, $values);
        $this->bindParams($stmt, $params);
        $this->bindLOBs($stmt, $lobs);
        return $this->doExecute($stmt);
    }

    public function selectRow($query_code, $values = array(), $fetch = PDO::FETCH_ASSOC)
    {
        $stmt = $this->getStmt($query_code);
        $this->bindValues($stmt, $values);
        $this->doExecute($stmt);
        return $stmt->fetch($fetch);
    }

    public function selectValue($query_code, $values = array())
    {
        $stmt = $this->getStmt($query_code);
        $this->bindValues($stmt, $values);
        $this->doExecute($stmt);
        list($value) = $stmt->fetch();
        return $value;
    }

    public function generateId($sequence)
    {
        $stmt = $this->context->getDb()->prepare("select {$sequence}.nextval from dual");
        list($value) = $this->doExecute($stmt)->fetch();
        return $value;
    }

}
