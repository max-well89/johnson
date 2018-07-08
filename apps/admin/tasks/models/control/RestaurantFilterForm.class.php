<?php

class RestaurantFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        //Период
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "created"));
        //$this->addValidator("created", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ID', 'id_restaurant'));
        $this->addValidator('id_restaurant', new nomvcIntegerValidator(array('required' => false)));

        
        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Город', 'id_city', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_CITY',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        )));
        $this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(), 
            "table" => "T_CITY", 
            "key" => "id_city"
        )));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_RESTAURANT_STATUS',
            'order' => 'name',
            'required' => false
        )));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_RESTAURANT_STATUS', 'key' => 'id_status')));


        //Дата рождения
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата рождения", "date_of_birth"));
        //$this->addValidator("date_of_birth", new nomvcDatePeriodValidator());

        //Логин
        //$this->addWidget(new nomvcInputTextWidget("Логин", "login"));
       // $this->addValidator('login', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Телефон
        /*
        $this->addWidget(new nomvcInputTextWidget("Телефон", "msisdn"));
        $this->addValidator('msisdn', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Почта
        $this->addWidget(new nomvcInputTextWidget("Почта", "email"));
        $this->addValidator('email', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Роль
        $this->addWidget(new nomvcInputTextWidget("Роль", "roles_list"));
        $this->addValidator('roles_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Регион
        $this->addWidget(new nomvcInputTextWidget("Регион", "geo_object_list"));
        $this->addValidator('geo_object_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));
        
*/
        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');
        
        $this->addWidget(new nomvcButtonWidget('Добавить ресторан', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('restaurant');",
            'class' => 'btn btn-success'
        )));

    }

}
