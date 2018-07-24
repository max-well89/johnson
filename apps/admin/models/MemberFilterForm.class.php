<?php

/**
 * Фильтры для таблицы Пользователи
 */
class MemberFilterForm extends nomvcAbstractFilterForm
{

    public function init()
    {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget('dt', "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('member_surname', 'surname'));
        $this->addValidator('surname', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcInputTextWidget('member_name', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

//        $this->addWidget(new nomvcInputTextWidget('Отчество', 'patronymic'));
//        $this->addValidator('patronymic', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('region', 'id_region', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_region',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        )));

        $this->addValidator('id_region', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_region",
            "key" => "id_region"
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('city', 'id_city', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_city',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        )));

        $this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_city",
            "key" => "id_city"
        )));


        $this->addWidget(new nomvcSelectFromMultipleDbWidget('area', 'id_area', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_area',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        )));

        $this->addValidator('id_area', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_area",
            "key" => "id_area"
        )));

        //Телефон
//        $this->addWidget(new nomvcInputTextWidget("Телефон", "msisdn"));
//        $this->addValidator('msisdn', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));
//
//        //Почта
//        $this->addWidget(new nomvcInputTextWidget("Email", "email"));
//        $this->addValidator('email', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Логин
        $this->addWidget(new nomvcInputTextWidget("login", "login"));
        $this->addValidator('login', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));


//        $this->addWidget(new nomvcInputTextWidget('Дилер', 'name_dealer'));
//        $this->addValidator('name_dealer', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Мета филиал', 'id_meta_filial', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_META_FILIAL',
//            'order' => 'name',
//            'required' => false
//        )));
//        $this->addValidator('id_meta_filial', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_META_FILIAL', 'key' => 'id_meta_filial')));
//
//        $this->addWidget(new nomvcInputTextWidget('Филиал', 'filial'));
//        $this->addValidator('filial', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_MEMBER_STATUS',
//            'order' => 'name',
//            'required' => false
//        )));
//        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_MEMBER_STATUS', 'key' => 'id_status')));
//        
        /*
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус участия в ПЛ', 'id_status_ext', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_MEMBER_STATUS_EXT',
            'order' => 'id_status',
            'required' => false,
            'key' => 'id_status',
            'multiple' => true
        )));
        $this->addValidator('id_status_ext', new nomvcValueInDbMultipleValidator(array(
            'required' => false, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_MEMBER_STATUS_EXT', 
            'key' => 'id_status'
        )));*/

        //Роль
        //$this->addWidget(new nomvcInputTextWidget("Роль", "roles_list"));
        //$this->addValidator('roles_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        //Регион
        //$this->addWidget(new nomvcInputTextWidget("Регион", "geo_object_list"));
        //$this->addValidator('geo_object_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget('add_member', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('member');",
            'class' => 'btn btn-success'
        )));

        /*
        if ($this->context->getUser()->getAttribute('id_restaurant') == null) {
            $this->addWidget(new nomvcButtonWidget('Подгрузить список пользователей', 'import', array(
                'type' => 'button',
                'icon' => 'file'
            ), array(
                'onclick' => "return TableFormActions.getForm('member-import');",
                'class' => 'btn btn-default'
            )));
        }*/
    }
}
