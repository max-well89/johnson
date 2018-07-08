<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class PointForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_point', 'id_point'));
        $this->addValidator('id_point', new nomvcIntegerValidator(array('required' => false)));

//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Группа', 'id_faq_group', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_FAQ_GROUP',
//            'order' => 'name',
//            'required' => true,
//            'order' => 'id_faq_group'
//        )));
//
//        $this->addValidator('id_faq_group', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_FAQ_GROUP',
//            'key' => 'id_faq_group'
//        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Дилер', 'id_dealer', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_DEALER_LIST',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_dealer', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_DEALER_LIST',
            'key' => 'id_dealer'
        )));
        
        $this->addWidget(new nomvcInputTextWidget('Регион', 'region'));
        $this->addValidator('region', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcInputTextWidget('Город', 'city'));
        $this->addValidator('city', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));
        
        $this->addWidget(new nomvcInputTextWidget('Адрес', 'address'));
        $this->addValidator('address', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcInputTextWidget('Код ТТ', 'code'));
        $this->addValidator('code', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        
//        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_FAQ_STATUS',
//            'order' => 'name',
//            'required' => true
//        )));
//
//        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
//            'required' => true,
//            'helper' => $this->context->getDbHelper(),
//            'table' => 'V_FAQ_STATUS',
//            'key' => 'id_status'
//        )));
    }
}
