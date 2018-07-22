<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class TaskForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_task', 'id_task'));
        $this->addValidator('id_task', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('name', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcInputDatePickerWidget('dt_task', 'dt_task', array(), array()));
        $this->addValidator('dt_task', new nomvcDateValidator(array('required' => true, 'in_format' => DateHelper::HTMLD_FORMAT)));

//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип', 'id_sku_type', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_type',
//            'order' => 'name',
//            'required' => true,
//            'with-add' => '+ добавить',
//            'with-add-url' => "/admin/backend/add-sku-type/"
//        )));
//
//        $this->addValidator('id_sku_type', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_type',
//            'key' => 'id_sku_type'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Производитель', 'id_sku_producer', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_producer',
//            'order' => 'name',
//            'required' => true,
//            'with-add' => '+ добавить',
//            'with-add-url' => "/admin/backend/add-sku-producer/"
//        )));
//
//        $this->addValidator('id_sku_producer', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 't_sku_producer',
//            'key' => 'id_sku_producer'
//        )));
//
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_sku_status',
//            'order' => 'name',
//            'required' => true
//        )));
//
//        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'v_sku_status',
//            'key' => 'id_status'
//        )));
    }
}
