<?php
/**
 * Фильтры для таблицы Пользователи
 */
class RestaurantsFilterForm extends nomvcAbstractFilterForm{

    public function init() {
        parent::init();

        //Период
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "created"));
        //$this->addValidator("created", new nomvcDatePeriodValidator());

        //ФИО
        $this->addWidget(new nomvcInputTextWidget("ФИО", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

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

        $this->addButton('search');
        $this->addButton('reset');
//		$this->addButton('export');
*/
        $this->addWidget(new nomvcButtonWidget('Добавить ресторан', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('restaurants');",
            'class' => 'btn btn-success'
        )));

    }

}
