<?php

class PushFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt"));
		$this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата начала отправки", "dt_start"));
        $this->addValidator("dt_start", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget("Текст сообщения", "message"));
        $this->addValidator("message", new nomvcStringValidator());

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'order' => 'name',
            'multiple' => true,
            'required' => false
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array('required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'key' => 'id_status'
        )));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget(' Создать', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('push');",
            'class' => 'btn btn-success'
        )));

    }

}