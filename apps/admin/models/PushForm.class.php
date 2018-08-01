<?php

class PushForm extends nomvcAbstractForm
{
    public function init()
    {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_push', 'id_push'));
        $this->addValidator('id_push', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('message_text', 'message', array()));
        $this->addValidator('message', new nomvcStringValidator(array('required' => true)));

        $this->addWidget(new nomvcInputDateTimePickerWidget('dt_start_send', 'dt_start', array()));
        $this->addValidator('dt_start', new nomvcDateValidator(array('required' => false, 'in_format' => DateHelper::HTMLT_FORMAT)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('status', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'required' => true,
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage(),
            'order' => 'name_'.Context::getInstance()->getUser()->getLanguage()
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'key' => 'id_status'
        )));

    }
}
