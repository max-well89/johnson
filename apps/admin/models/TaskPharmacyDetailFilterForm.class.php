<?php

class TaskPharmacyDetailFilterForm extends nomvcAbstractFilterForm
{
    public function init()
    {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("dt", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputHiddenWidget('id_task', 'id_task'));
        $this->addValidator('id_task', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcInputHiddenWidget('id_pharmacy', 'id_pharmacy'));
        $this->addValidator('id_pharmacy', new nomvcIntegerValidator(array('required' => true)));

        $this->addWidget(new nomvcInputHiddenWidget('id_member', 'id_member'));
        $this->addValidator('id_member', new nomvcIntegerValidator(array('required' => true)));

//        $this->addWidget(new nomvcInputTextWidget("Аптека", "pharmacy"));
//        $this->addValidator('pharmacy', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
//
//        $this->addWidget(new nomvcInputTextWidget("Адрес", "address"));
//        $this->addValidator('address', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Категория', 'id_category', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_category',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_category', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_category',
//            'key' => 'id_category'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Регион', 'id_region', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_region',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_region', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_region',
//            'key' => 'id_region'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Город', 'id_city', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_city',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_city',
//            'key' => 'id_city'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Район', 'id_area', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_area',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_area', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_area',
//            'key' => 'id_area'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Мерчендайзер', 'id_member', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_merch_list',
//            'order' => 'fio',
//            'val' => 'fio',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_member', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_merch_list',
//            'key' => 'id_member'
//        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku', 'id_sku', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_sku', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku',
            'key' => 'id_sku'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_type', 'id_sku_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 't_sku_type',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_sku_type', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 't_sku_type',
            'key' => 'id_sku_type'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_producer', 'id_sku_producer', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 't_sku_producer',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_sku_producer', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 't_sku_producer',
            'key' => 'id_sku_producer'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('action_status', 'id_action_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_action_status',
            "key" => "id_status",
            'order' => 'name',
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
//
        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

//        $this->addWidget(new nomvcButtonWidget('Добавить аптеку', 'create', array(
//            'type' => 'button',
//            'icon' => 'file'
//        ), array(
//            'onclick' => "return TableFormActions.getForm('pharmacy');",
//            'class' => 'btn btn-success'
//        )));
    }

}
