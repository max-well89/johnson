<?php

/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class SkuForm extends nomvcAbstractForm
{
    public function init()
    {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_sku', 'id_sku'));
        $this->addValidator('id_sku', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('name', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true, 'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_type', 'id_sku_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_type',
            'order' => 'name',
            'required' => true,
            'with-add' => '+ добавить',
            'with-add-url' => "/admin/backend/add-sku-type/"
        )));

        $this->addValidator('id_sku_type', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_type',
            'key' => 'id_sku_type'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('sku_producer', 'id_sku_producer', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_producer',
            'order' => 'name',
            'required' => true,
            'with-add' => '+ добавить',
            'with-add-url' => "/admin/backend/add-sku-producer/"
        )));

        $this->addValidator('id_sku_producer', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_producer',
            'key' => 'id_sku_producer'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('priority', 'id_priority', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_priority',
            'order' => 'id_priority',
            'required' => false,
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage()
            //'with-add' => '+ добавить',
            //'with-add-url' => "/admin/backend/add-sku-producer/"
        )));

        $this->addValidator('id_priority', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_priority',
            'key' => 'id_priority'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('status', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_status',
            'val' => 'name_'.Context::getInstance()->getUser()->getLanguage(),
            'required' => true
        )));

        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_sku_status',
            'key' => 'id_status'
        )));
    }
}