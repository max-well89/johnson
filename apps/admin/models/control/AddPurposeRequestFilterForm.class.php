<?php

class AddPurposeRequestFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("Период отправки запроса", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ID запроса', 'id_add_member_point'));
        $this->addValidator('id_add_member_point', new nomvcIntegerValidator(array('required' => false)));
        
        $this->addWidget(new nomvcInputTextWidget('Learning ID', 'learning_id'));
        $this->addValidator('learning_id', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Фамилия', 'surname'));
        $this->addValidator('surname', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Должность', 'id_position', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_POSITION',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_position', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_POSITION",
            "key" => "id_position"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Город', 'id_city', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_CITY',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "T_CITY",
            "key" => "id_city"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Название ресторана', 'id_restaurant', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_RESTAURANT',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_restaurant', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_RESTAURANT",
            "key" => "id_restaurant"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип запроса', 'id_add_purpose', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_ADD_PURPOSE',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_add_purpose', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "T_ADD_PURPOSE",
            "key" => "id_add_purpose"
        )));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус запроса', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_ADD_PURPOSE_MEMBER_STATUS',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_ADD_PURPOSE_MEMBER_STATUS",
            "key" => "id_status"
        )));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');
/*
        $this->addWidget(new nomvcButtonWidget('Добавить новость', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('news');",
            'class' => 'btn btn-success'
        )));
*/
    }

}
