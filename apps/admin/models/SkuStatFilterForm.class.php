<?php

class SkuStatFilterForm extends nomvcAbstractFilterForm
{
    public function init()
    {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("dt", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget("name", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_type', 'id_sku_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_type',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_sku_type', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_type',
            'key' => 'id_sku_type'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_producer', 'id_sku_producer', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_producer',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_sku_producer', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_producer',
            'key' => 'id_sku_producer'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('merchandiser', 'id_member', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_merch_list',
            'order' => 'fio',
            'val' => 'fio',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_member', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_merch_list',
            'key' => 'id_member'
        )));


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

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('action_status', 'id_action_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_action_status',
            "key" => "id_status",
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage(),
            'order' => 'name_'.Context::getInstance()->getUser()->getLanguage(),
            'required' => false,
            'multiple' => true
        )));

        $this->addValidator('id_action_status', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "v_action_status",
            "key" => "id_status"
        )));

        $this->addWidget(new nomvcInputTextWidget("comment", "comment"));
        $this->addValidator('comment', new nomvcStringValidator(array('required' => false)));

//        $this->addWidget(new nomvcInputTextWidget("Цена", "my_value"));
//        $this->addValidator('my_value', new nomvcNumberValidator(array('required' => false)));


//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_sku_status',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_sku_status',
//            'key' => 'id_status'
//        )));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');
//
//        $this->addWidget(new nomvcButtonWidget('Добавить SKU', 'create', array(
//            'type' => 'button',
//            'icon' => 'file'
//        ), array(
//            'onclick' => "return TableFormActions.getForm('sku');",
//            'class' => 'btn btn-success'
//        )));

    }

}
