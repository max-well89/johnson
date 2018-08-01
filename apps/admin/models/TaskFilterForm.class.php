<?php

class TaskFilterForm extends nomvcAbstractFilterForm
{
    public function init()
    {
        parent::init();

        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("dt", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

//        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
//        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип', 'id_sku_type', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_type',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_sku_type', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_type',
//            'key' => 'id_sku_type'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Производитель', 'id_sku_producer', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_producer',
//            'order' => 'name',
//            'required' => false,
//            'multiple' => true
//        ), array()));
//        $this->addValidator('id_sku_producer', new nomvcValueInDbMultipleValidator(array(
//            'required' => false,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_producer',
//            'key' => 'id_sku_producer'
//        )));
//
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('status', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_task_status',
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage(),
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_task_status',
            'key' => 'id_status'
        )));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget('add_task', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('task');",
            'class' => 'btn btn-success'
        )));

    }

}
