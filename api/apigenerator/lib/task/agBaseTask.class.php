<?php

abstract class agBaseTask
{

    protected $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public abstract function getCommand();

    public abstract function getDescription();

    public abstract function getOptions();

}

?>
