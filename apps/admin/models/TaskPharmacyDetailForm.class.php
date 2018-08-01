<?php

class TaskPharmacyDetailForm extends nomvcAbstractForm
{
    public function init()
    {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_task_data', 'id_task_data'));
        $this->addValidator('id_task_data', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcInputTextWidget('my_value', 'value'));
        $this->addValidator('value', new nomvcNumberValidator());

        $this->addWidget(new nomvcInputTextWidget('rest_cnt', 'rest_cnt'));
        $this->addValidator('rest_cnt', new nomvcNumberValidator());

        $this->addWidget(new nomvcInputTextWidget('illiquid_cnt', 'illiquid_cnt'));
        $this->addValidator('illiquid_cnt', new nomvcNumberValidator());

        $this->addWidget(new nomvcSelectFromDbWidget('action_status', 'is_action', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_action_status',
            "key" => "id_status",
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage()
        )));

        $this->addValidator('is_action', new nomvcValueInDbValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_action_status",
            "key" => "id_status"
        )));

        $this->addWidget(new nomvcInputTextWidget("comment", "comment"));
        $this->addValidator('comment', new nomvcStringValidator(array('required' => false)));
    }
}
