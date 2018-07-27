<?php

class nomvcPlainTextWidget extends nomvcBaseWidget
{

    public function renderForForm($formName, $value = null)
    {
        return sprintf('<div id="form_group_%s" class="form-group"><label class="col-sm-offset-1 col-sm-10">%s</label></div>',
            $this->getName(), $this->getLabel());
    }

    public function renderForFilter($formName, $value = null)
    {
        return sprintf('<div id="form_group_%s" class="form-group"><label class="col-sm-offset-1 col-sm-10">%s</label></div>',
            $this->getName(), $this->getLabel());
    }

    protected function init()
    {
        parent::init();
    }
}
