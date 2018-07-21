<?php

class AddPurposeRequestForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        //  $disabled = $this->context->getUser()->getAttribute('id_prize')?true:false;

        $disabled = true;
        
        $this->addWidget(new nomvcInputDateTimePickerWidget("Дата отправки запроса", "dt", array(), array(
            'disabled' => $disabled
        )));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputHiddenWidget('ID запроса', 'id_add_member_point', array(), array()));
        $this->addValidator('id_add_member_point', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Learning ID', 'learning_id', array(), array(
            'disabled' => $disabled
        )));
        $this->addValidator('learning_id', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Фамилия', 'surname', array(), array(
            'disabled' => $disabled
        )));
        $this->addValidator('surname', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Должность', 'id_position', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_POSITION',
            'order' => 'name'
        ), array('disabled' => $disabled)));
        $this->addValidator('id_position', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_POSITION",
            "key" => "id_position"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Город', 'id_city', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_CITY',
            'order' => 'name'
        ), array('disabled' => $disabled)));
        $this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "T_CITY",
            "key" => "id_city"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Название ресторана', 'id_restaurant', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_RESTAURANT',
            'order' => 'name'
        ), array('disabled' => $disabled)));
        $this->addValidator('id_restaurant', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_RESTAURANT",
            "key" => "id_restaurant"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип запроса', 'id_add_purpose', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_ADD_PURPOSE',
            'order' => 'name'
        ), array('disabled' => $disabled)));
        $this->addValidator('id_add_purpose', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "T_ADD_PURPOSE",
            "key" => "id_add_purpose"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус запроса', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_ADD_PURPOSE_MEMBER_STATUS',
            'order' => 'name'
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_ADD_PURPOSE_MEMBER_STATUS",
            "key" => "id_status"
        )));
        
        if ($this->isBined == true){
            if ($this->getValue('id_status') == 1)
                $this->getWidget('id_status')->setAttribute('disabled', true);
        }
    }
}
