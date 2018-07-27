<?php

class nomvcInputMsisdnWidget extends nomvcInputWidget
{
    //protected $classes = [];

    protected function init()
    {
        parent::init();
        $this->setAttribute('type', 'text');
        $this->setAttribute('data-mask', '+7(999)999-99-99');
    }

}
