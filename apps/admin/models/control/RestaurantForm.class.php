<?php

class RestaurantForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_restaurant', 'id_restaurant'));
        $this->addValidator('id_restaurant', new nomvcIntegerValidator(array('required' => false)));
        
        $this->addWidget(new nomvcInputTextWidget('Название', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Город', 'id_city', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_CITY',
            'order' => 'name',
            'required' => false
        )));
        $this->addValidator('id_city', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'T_CITY', 'key' => 'id_city')));

        $this->addWidget(new nomvcInputTextWidget('Адрес', 'address'));
        $this->addValidator('address', new nomvcStringValidator(array('required' => true, 'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Формат ресторана', 'id_restaurant_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_RESTAURANT_TYPE',
            'order' => 'name',
            'required' => false
        )));
        $this->addValidator('id_restaurant_type', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'T_RESTAURANT_TYPE', 'key' => 'id_restaurant_type')));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_RESTAURANT_STATUS',
            'order' => 'name',
            'required' => true
        )));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_RESTAURANT_STATUS', 'key' => 'id_status')));


        //Фамилия
        //$this->addWidget(new nomvcInputTextWidget('Фамилия', 'surname'));
        //$this->addValidator('surname', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));
        //Имя
        /*
        $this->addWidget(new nomvcInputTextWidget('Название', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));
        //Отчество
        $this->addWidget(new nomvcInputTextWidget('Отчество', 'patronymic'));
        $this->addValidator('patronymic', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        //День рождения
        $this->addWidget(new nomvcInputDateTimePickerWidget("День рождения", "day_of_birth", array("value" => date("Y-m-d")), array("data-date-format" => "DD.MM.YYYY")));
        $this->addValidator('day_of_birth', new nomvcDateValidator(array('required' => false, 'in_format' => DateHelper::HTMLD_FORMAT)));
        //Логин
        $this->addWidget(new nomvcInputTextWidget('Логин', 'login'));
        $this->addValidator('login', new nomvcStringValidator(array('required' => true,'min' => 5, 'max' => 50)));

        //Пароль
        $this->addWidget(new nomvcInputPasswordWidget('Пароль', 'passwd'));
        $this->addValidator('passwd', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        //Подтверждение пароля
        $this->addWidget(new nomvcInputPasswordWidget('Подтвердите пароль', 'passwd_confirm'));
        $this->addValidator('passwd_confirm', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        //Телефон
        $this->addWidget(new nomvcInputTextWidget('Телефон', 'msisdn'));
        $this->addValidator('msisdn', new nomvcStringValidator(array('required' => true,'min' => 11, 'max' => 16)));

        //Почта
        $this->addWidget(new nomvcInputTextWidget('Почта', 'email'));
        $this->addValidator('email', new nomvcStringValidator(array('required' => true, 'min' => 8, 'max' => 100)));

        //Роль
        $this->addWidget(new nomvcSelectFromDbWidget('Роль', 'id_role', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_ROLE',
        )));
        $this->addValidator('id_role', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_ROLE', 'key' => 'id_role')));

        //Регионы
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Доступные регионы', 'regions', array(
            'helper' => $this->context->getDbHelper(), 'table' => 'V_REGION', 'key' => 'id_region'), array()));
        $this->addValidator('regions', new nomvcArrayValidator(array('required' => false)));
    */
    }

}
