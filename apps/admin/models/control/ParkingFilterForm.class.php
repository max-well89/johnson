<?php

class ParkingFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        //Период
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt"));
        //$this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ID', 'id_parking'));
        $this->addValidator('id_parking', new nomvcIntegerValidator(array('required' => false)));
        
        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

/*
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Категория', 'id_prize_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PRIZE_TYPE',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_prize_type', new nomvcValueInDbValidator(array(
            'required' => false, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_PRIZE_TYPE', 
            'key' => 'id_prize_type'
        )));
        */

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PARKING_STATUS',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_PARKING_STATUS', 'key' => 'id_status')));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget('Добавить парковку', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('parking');",
            'class' => 'btn btn-success'
        )));

    }

}
