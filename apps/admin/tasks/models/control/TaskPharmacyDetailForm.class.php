<?php

class TaskPharmacyDetailForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_task_data', 'id_task_data'));
        $this->addValidator('id_task_data', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcInputTextWidget('Цена', 'value'));
        $this->addValidator('value', new nomvcNumberValidator());
    }
}
