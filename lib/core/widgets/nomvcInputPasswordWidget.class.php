<?php

class nomvcInputPasswordWidget extends nomvcInputWidget
{

    public function renderControl($value, $attributes = array())
    {
        return parent::renderControl(null, $attributes);
    }

    protected function init()
    {
        parent::init();
        $this->setAttribute('type', 'password');
    }

}
