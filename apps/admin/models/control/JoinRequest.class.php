<?php
/**
 * Тэги. Базовый класс
 */
class JoinRequest {
    public static function getTableName() {
        return 'V_JOIN_REQUEST';
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
        return $this->id_join_request;
    }

    public function get($name) {
        $name = strtolower($name);
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }
}
