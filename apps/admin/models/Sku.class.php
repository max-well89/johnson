<?php

class Sku {
    public static function getTableName() {
        return 'v_sku';
    }

    public function getRowStatusClass() {
        $color_class = null;

        switch ($this->id_priority){
            case 3:
                $color_class = 'success';
                break;
            case 2:
                $color_class = 'warning';
                break;
            break;
            case 1:
                $color_class = 'error';
                break;
            break;
            default:
              break;
        }

        return $color_class;
    }

    public function getRowTitle() {
        return '';
    }

    public static function getTotal() {
        return array();
    }

    public function __get($name) { return $this->get($name); }

    public function getRowId() {
        return $this->id_sku;
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
