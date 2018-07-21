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

        $this->addWidget(new nomvcSelectFromDbWidget('Акция', 'is_action', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_action_status',
            "key" => "id_status",
            'order' => 'name'
        )));

        $this->addValidator('is_action', new nomvcValueInDbValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_action_status",
            "key" => "id_status"
        )));

        $this->addWidget(new nomvcInputTextWidget("Комментарий", "comment"));
        $this->addValidator('comment', new nomvcStringValidator(array('required' => false)));
    }
}
