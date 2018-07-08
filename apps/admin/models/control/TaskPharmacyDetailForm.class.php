<?php

class TaskPharmacyDetailForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_task_data', 'id_task_data'));
        $this->addValidator('id_task_data', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcInputTextWidget('Цена', 'value'));
        $this->addValidator('value', new nomvcNumberValidator());

        $this->addWidget(new nomvcInputTextWidget('Остаток (шт.)', 'rest_cnt'));
        $this->addValidator('rest_cnt', new nomvcNumberValidator());

        $this->addWidget(new nomvcInputTextWidget('Неликвид (шт.)', 'illiquid_cnt'));
        $this->addValidator('illiquid_cnt', new nomvcNumberValidator());
    }
}
