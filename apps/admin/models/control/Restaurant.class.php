<?php

class Restaurant {
    public static function getTableName() {
        return 'V_RESTAURANT';
    }

    public function getRowStatusClass() {
        return '';
    }

    public function getRowTitle() {
        return '';
    }

    public static function getTotal() {
        return array();
    }

    public function __get($name) { return $this->get($name); }

    public function getRowId() {
        return $this->id_restaurant;
    }

    public function get($name) {
        $name = strtoupper($name);
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }
}
