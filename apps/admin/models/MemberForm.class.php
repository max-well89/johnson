<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class MemberForm extends nomvcAbstractForm {
//    public $type_residents = [
//        1 => 'Да',
//        0 => 'Нет'
//    ];
//
//    public $type_cards = [];
//
//    public $type_docs = [];
    
    public function init() {
        parent::init();
        
//        //init type_docs
//        $stmt = $this->context->getDb()->prepare('select key, value from t_type_doc');
//        $stmt->execute();
//
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $this->type_docs[$row['key']] = $row['value'];
//        }
//
//        //init type_cards
//        $stmt = $this->context->getDb()->prepare('select key, value from t_type_card');
//        $stmt->execute();
//
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $this->type_cards[$row['key']] = $row['value'];
//        }

        $disabled = false;

        if ($disabled)
            $attr_ext = array('disabled' => 'disabled');
        else
            $attr_ext = [];

        $this->addWidget(new nomvcInputHiddenWidget('id_member', 'id_member'));
        $this->addValidator('id_member', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('member_surname', 'surname', array(), array_merge(array(), $attr_ext)));
        $this->addValidator('surname', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcInputTextWidget('member_name', 'name', array(), array_merge(array(), $attr_ext)));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

//        $this->addWidget(new nomvcInputTextWidget('Отчество', 'patronymic', array(), array_merge(array(), $attr_ext)));
//        $this->addWidget(new nomvcInputTextWidget('Номер телефона', 'msisdn', array(), array_merge(array(), $attr_ext)));

//        $this->addWidget(new nomvcInputTextWidget('Email', 'email', array(), array_merge(array(), $attr_ext)));

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

        $this->addWidget(new nomvcInputTextWidget('login', 'login'));
        $this->addValidator('login', new nomvcStringValidator(array('required' => true,'min' => 5, 'max' => 50)));
        
        $this->addWidget(new nomvcInputPasswordWidget('password', 'passwd'));
        $this->addValidator('passwd', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        $this->addWidget(new nomvcInputPasswordWidget('password_confirm', 'passwd_confirm'));
        $this->addValidator('passwd_confirm', new nomvcStringValidator(array('required' => false, "min" => 6, "max" => 10)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('language', 'id_language', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_language',
            'order' => 'name'
        )));

        $this->addValidator('id_language', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_language',
            'key' => 'id_language'
        )));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('status', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_member_status',
            'order' => 'name'
        )));
        
        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'v_member_status',
            'key' => 'id_status'
        )));
    }

}
