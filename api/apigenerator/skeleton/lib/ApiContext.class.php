<?php

class ApiContext extends agApiContext
{

    public function configureDirs()
    {
        $this->setDir('base', dirname(dirname(__FILE__)));
        $this->setDir('config', $this->getDir('base') . '/config');
        $this->setDir('lib', $this->getDir('base') . '/lib');
        $this->setDir('actions', $this->getDir('lib') . '/actions');
        $this->setDir('template', $this->getDir('base') . '/template');
    }

}

?>
