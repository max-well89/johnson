<?php

class Task
{
    public static function getTableName()
    {
        return 'v_task';
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

        if ($name == 'detail')
            return $this->getDetail();

        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }

    private function getDetail()
    {
        return "<a target='_blank' href='/admin/stat/task-detail/?id_task={$this->id_task}'>" . Context::getInstance()->translate('view') . "</a>";
    }

    public function getRowId()
    {
        return $this->id_task;
    }
}
