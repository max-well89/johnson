<?php

class nomvcInputMonthWidget extends nomvcInputWidget
{

    public function renderControl($value, $attributes = array())
    {
        if ($value) {
            $value = DateHelper::dateConvert(DateHelper::DBD_FORMAT, 'Y-m', $value);
        }
        return parent::renderControl($value, $attributes);
    }

    protected function init()
    {
        parent::init();
        $this->setAttribute('type', 'month');
    }

}
