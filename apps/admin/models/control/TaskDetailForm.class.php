<?php

class TaskDetailForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_task_mp', 'id_task_mp'));
        $this->addValidator('id_task_mp', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_tmp_status',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_tmp_status',
            'key' => 'id_status'
        )));
    }
}
