<?php

class nomvcLegendWidget extends nomvcBaseWidget
{

    public function renderForForm($formName, $value = null)
    {

        return sprintf('<div id="form_group_%s" class="form-group"></div>',
            $this->getName());
    }

    public function renderForFilter($formName, $value = null)
    {
        return sprintf('<div id="form_group_%s" class="form-group"><span class="label label-success">%s</span></div>',
            $this->getName(), $this->getLabel());
    }

    protected function init()
    {
        parent::init();
    }
}
