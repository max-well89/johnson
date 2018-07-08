<?php

class AboutFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        //Период
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt"));
        //$this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ID', 'id_about'));
        $this->addValidator('id_about', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_ABOUT_STATUS',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_ABOUT_STATUS', 'key' => 'id_status')));


        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget('Добавить пункт', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('about');",
            'class' => 'btn btn-success'
        )));

    }

}
