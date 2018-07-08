<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class DealerForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_dealer', 'id_dealer'));
        $this->addValidator('id_dealer', new nomvcIntegerValidator(array('required' => false)));

//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Мета филиал', 'id_meta_filial', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_META_FILIAL',
//            'order' => 'name',
//            'required' => true
//        )));
//
//        $this->addValidator('id_meta_filial', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_META_FILIAL',
//            'key' => 'id_meta_filial'
//        )));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Филиал', 'id_filial', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_FILIAL_LIST',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_filial', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_FILIAL_LIST',
            'key' => 'id_filial'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Канал продаж', 'id_sales_channel', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_SALES_CHANNEL',
            'order' => 'id_sales_channel',
            'required' => true
        )));

        $this->addValidator('id_sales_channel', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_SALES_CHANNEL',
            'key' => 'id_sales_channel'
        )));
        
        $this->addWidget(new nomvcInputTextWidget('Название', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcInputTextWidget('Логин', 'login'));
        $this->addValidator('login', new nomvcStringValidator(array('required' => true,'min' => 5, 'max' => 50)));

        $this->addWidget(new nomvcInputPasswordWidget('Пароль', 'passwd'));
        $this->addValidator('passwd', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        $this->addWidget(new nomvcInputPasswordWidget('Подтвердите пароль', 'passwd_confirm'));
        $this->addValidator('passwd_confirm', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        
    }
}
