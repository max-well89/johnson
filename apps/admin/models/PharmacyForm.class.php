<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class PharmacyForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_pharmacy', 'id_pharmacy'));
        $this->addValidator('id_pharmacy', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('id_crm', 'id_crm'));
        $this->addValidator('id_crm', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('name', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcInputTextWidget('address', 'address'));
        $this->addValidator('address', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('category', 'id_category', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 't_category',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_category', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 't_category',
            'key' => 'id_category'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('region', 'id_region', array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_region',
            'order' => 'name'
        )));

        $this->addValidator('id_region', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_region',
            'key' => 'id_region'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('city', 'id_city', array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_city',
            'order' => 'name'
        )));

        $this->addValidator('id_city', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_city',
            'key' => 'id_city'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('area', 'id_area', array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_area',
            'order' => 'name'
        )));

        $this->addValidator('id_area', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_area',
            'key' => 'id_area'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('merchandiser', 'id_member', array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_merch_list',
            'val' => 'fio',
            'order' => 'fio'
        )));

        $this->addValidator('id_member', new nomvcValueInDbValidator(array(
            'required' => false,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_merch_list',
            'key' => 'id_member'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('status', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_pharmacy_status',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_pharmacy_status',
            'key' => 'id_status'
        )));
    }
}
