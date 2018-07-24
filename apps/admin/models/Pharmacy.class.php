<?php

class Pharmacy
{
    public static function getTableName()
    {
        return 'v_pharmacy';
    }

    public static function getTotal()
    {
        return array();
    }

    public function getRowStatusClass()
    {
        return '';
    }

    public function getRowTitle()
    {
        return '';
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        $name = strtolower($name);
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }

    public function getRowId()
    {
        return $this->id_pharmacy;
    }
}
