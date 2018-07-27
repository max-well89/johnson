<?php

class agClassNotFoundException extends agBaseException
{

    protected $className;

    public function __construct($className)
    {
        parent::__construct("Class $className not found");
        $this->className = $className;
    }

    public function getClassName()
    {
        return $this->className;
    }
}
