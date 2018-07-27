<?php

abstract class agLogger extends agAbstractComponent
{

    protected $start_time = null;
    protected $stop_time = null;

    public function __construct($context)
    {
        parent::__construct($context);
        $this->start_time = microtime(true);
    }

    public function __destruct()
    {
        $this->stop_time = microtime(true);
        $this->log();
    }

    protected abstract function log();

}
