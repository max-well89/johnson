<?php

class nomvcModelFactory
{

    // ссылка на контекст
    protected $context;

    /** Конструктор */
    public function __construct($context)
    {
        $this->context = $context;
    }

//    protected function setContext($criteria = null) {

    public function select($model, $criteria = null, $fetchByClass = false)
    {
        $this->setContext($criteria);
        $sql = $this->makeQuery($model, $criteria);
        $dbHelper = $this->context->getDbHelper();
        $query_code = md5($sql);
        $dbHelper->addQuery($query_code, $sql);
        $stmt = $dbHelper->select($query_code, $criteria->getValues());
        $data = array();
        if ($fetchByClass) {
            while ($obj = $stmt->fetch(PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE)) $data[] = $obj;
        } else {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $model);
            while ($obj = $stmt->fetch(PDO::FETCH_CLASS)) $data[] = $obj;
        }
        return $data;
    }

    public function setContext($criteria = null)
    {
        if ($criteria) {
            $dbHelper = $this->context->getDbHelper();
            foreach ($criteria->getContext() as $name => $value) {
                if (is_array($value)) {
                    if (empty($value)) {
                        $value = null;
                    } else {
                        $value = "(^" . implode("|", $value) . "$)";
                    }
                }
                $sql = "begin project_context.set_parameter('{$name}', :{$name}); end;";
                $query_code = md5($sql);
                $dbHelper->addQuery($query_code, $sql);
                $dbHelper->execute($query_code, array($name => $value));
            }
        }
    }

    public function makeQuery($model, $criteria)
    {
        $orderBy = $criteria->getOrderBy();
//        $ar1 = explode(' ', $orderBy);
//
//        if (is_array($ar1)) {
//            $orderBy = '';
//
//            foreach ($ar1 as $key => $param) {
//                if (!in_array($param, array('asc', 'desc',',')))
//                    $orderBy .= " \"" . strtoupper($param) . "\"";
//                else
//                    $orderBy .= " ".$param;
//            }
//        }
//        //var_dump($ar1, $orderBy); exit;

        $sql = "select {$model::getTableName()}.*, row_number() over(order by {$orderBy}) mf_rownumber from {$model::getTableName()}";
        if ($criteria == null) {
            return $sql;
        }
        if ($where = $criteria->getWhere()) {
            $sql .= ' ' . $where;
        }
        if ($limit = $criteria->getLimit()) {
            $sql = "select * from ($sql) tbl0 where mf_rownumber between {$criteria->getOffset()} and {$criteria->getLimit()} + {$criteria->getOffset()}";
        }

        //var_dump($sql); exit;
        return $sql;
    }

    public function count($model, $criteria = null)
    {
        $this->setContext($criteria);
        $sql = $this->makeCountQuery($model, $criteria);
        $dbHelper = $this->context->getDbHelper();
        $query_code = md5($sql);
        $dbHelper->addQuery($query_code, $sql);

        $stmt = $dbHelper->select($query_code, $criteria->getValues());
        $stmt->setFetchMode(PDO::FETCH_CLASS, $model);
        return $stmt->fetch(PDO::FETCH_CLASS);
    }

    public function makeCountQuery($model, $criteria)
    {
        $fields = array('count(*) as "count"');
        foreach ($model::getTotal() as $field => $function) {
            $fields[] = "{$function}($field) as {$field}";
        }
        $fields = implode(', ', $fields);
        $sql = "select $fields from {$model::getTableName()}";
        if ($criteria == null) {
            return $sql;
        }
        if ($where = $criteria->getWhere()) {
            $sql .= ' ' . $where;
        }
        return $sql;
    }

}
