<?php
/**
 * Фильтры для таблицы Пользователи
 */
class JoinRequestFilterForm extends nomvcAbstractFilterForm{

    public function init() {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата поступления запроса", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ФИО', 'fio'));
        $this->addValidator('fio', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
        
        $this->addWidget(new nomvcInputTextWidget('Телефон для участия', 'msisdn'));
        $this->addValidator('msisdn', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
        
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Дилер', 'id_dealer', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_DEALER_LIST',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        )));
//
//        $this->addValidator('id_dealer', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            "helper" => $this->context->getDbHelper(),
//            "table" => "V_DEALER_LIST",
//            "key" => "id_dealer"
//        )));

        $this->addWidget(new nomvcInputTextWidget('Дилер', 'name_dealer'));
        $this->addValidator('name_dealer', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Мета филиал', 'id_meta_filial', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_META_FILIAL',
            'order' => 'name',
            'required' => false
        )));
        $this->addValidator('id_meta_filial', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_META_FILIAL', 'key' => 'id_meta_filial')));

        $this->addWidget(new nomvcInputTextWidget('Филиал', 'filial'));
        $this->addValidator('filial', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_JOIN_REQUEST_STATUS',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        )));

        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_JOIN_REQUEST_STATUS",
            "key" => "id_status"
        )));
        

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

//        $this->addWidget(new nomvcButtonWidget('Добавить пользователя', 'create', array(
//            'type' => 'button',
//            'icon' => 'file'
//        ), array(
//            'onclick' => "return TableFormActions.getForm('join-request');",
//            'class' => 'btn btn-success'
//        )));

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
