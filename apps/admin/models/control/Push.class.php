<?php

class Push{
    public static function getTableName() {
        return 'v_push';
    }

    public function getRowStatusClass() {

    }

    public function getRowTitle() {
        return '';
    }

    public static function getTotal() {
        return array();
    }

    public function __get($name) { return $this->get($name); }

    public function getRowId() {
        return $this->id_push;
    }

    public function get($name) {
        $name = strtolower($name);

        if ($name == 'dt'){
            //var_dump(date('d-m-Y H:i:s',strtotime($this->$name))); exit;
            $this->$name = date('Y-m-d H:i:s',strtotime($this->$name));
        }

        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }
}
