<?php

class nomvcErrorOutController extends nomvcBaseController
{

    public function run()
    {
        echo $this->exception->getMessage();
    }

    /** Этим методом получаем эксепшен */
    public function setException($exception)
    {
        $this->exception = $exception;
    }

    protected function init()
    {
        $this->context->addViewAddon('bootstrap');
    }

}
