<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class JoinRequestForm extends nomvcAbstractForm {
    public $type_residents = [
        1 => 'Да',
        0 => 'Нет'
    ];

    public $type_cards = [];

    public $type_docs = [];
    
    public function init() {
        parent::init();

        //init type_docs
        $stmt = $this->context->getDb()->prepare('select key, value from t_type_doc');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->type_docs[$row['key']] = $row['value'];
        }

        //init type_cards
        $stmt = $this->context->getDb()->prepare('select key, value from t_type_card');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->type_cards[$row['key']] = $row['value'];
        }

        $disabled = true;
        
        if ($disabled)
            $attr_ext = array('disabled' => 'disabled');
        else
            $attr_ext = [];
        
        $this->addWidget(new nomvcInputHiddenWidget('id_join_request', 'id_join_request'));
        $this->addValidator('id_join_request', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromArrayWidget('Резидент?', 'is_resident', array('options' => $this->type_residents), array_merge(array(), $attr_ext)));
        
        $this->addWidget(new nomvcInputTextWidget('Фамилия', 'surname', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Имя', 'name', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Отчество', 'patronymic', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputDatePickerWidget('Дата рождения', 'dt_birthday', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('ИНН', 'inn', array(), array_merge(array(), $attr_ext)));

        $this->addWidget(new nomvcSelectFromArrayWidget('Вид документа', 'resident_type_doc', array('options' => $this->type_docs, 'default' => array(''=>'')), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcTextareaWidget('Описание документа', 'resident_doc_description', array(), array_merge(array(), $attr_ext)));

        $this->addWidget(new nomvcInputTextWidget('Паспорт - серия', 'passport_1', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Паспорт - номер', 'passport_2', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputDatePickerWidget('Дата выдачи', 'dt_passport_give', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Код подразделения', 'subdivision_code', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcTextareaWidget('Кем выдан', 'whom_give_out', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcTextareaWidget('Адрес регистрации', 'address_register', array(), array_merge(array(), $attr_ext)));

        $this->addWidget(new nomvcInputTextWidget('Номер телефона для участия', 'msisdn', array(), array_merge(array(), $attr_ext)));
        
        $this->addWidget(new nomvcInputTextWidget('Контактный email', 'email', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Контактный номер телефона', 'msisdn_two', array(), array_merge(array(), $attr_ext)));
        
        $this->addWidget(new nomvcInputTextWidget('Код ТТ', 'code_tt', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Город', 'city', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Улица, дом', 'street', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Дилер (ООО..., ИП...)', 'dealer', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Имя и фамилия торгового представителя МегаФон, обслуживающего салон', 'name_of_sales', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Торговая марка', 'trademark', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Стаж работы у Дилера (Год / года / лет)', 'length_of_service_1', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('Стаж работы у Дилера (Месяцев)', 'length_of_service_2', array(), array_merge(array(), $attr_ext)));

        $this->addWidget(new nomvcInputTextWidget('Наименование Банка', 'bank_name', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('БИК Банка', 'bank_bik', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('ИНН Банка', 'bank_inn', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('КПП Банка', 'bank_kpp', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('К/Счет Банка', 'bank_account', array(), array_merge(array(), $attr_ext)));

        $this->addWidget(new nomvcInputTextWidget('ФИО владельца лицевого счета в Банке', 'owner_fio', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('№ лиц. счета владельца', 'owner_account', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcInputTextWidget('№ карты владельца лицевого счета в Банке', 'owner_card_number', array(), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcSelectFromArrayWidget('Вид карты (Маэстро, Виза и т.д.) владельца лицевого счета в Банке', 'owner_type_card', array('options' => $this->type_cards, 'default' => array(''=>'')), array_merge(array(), $attr_ext)));
        
        $file_path = '/files/';
        $this->addWidget(new nomvcImagePreviewWidget('Паспорт 1 страница', 'passport_1_path', array('path'=> $file_path), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcImagePreviewWidget('Паспорт регистрация', 'passport_2_path', array('path'=> $file_path), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcImagePreviewWidget('ИНН', 'inn_path', array('path'=> $file_path),  array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcImagePreviewWidget('1 страница анкеты', 'blank_1_path', array('path'=> $file_path), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcImagePreviewWidget('2 страница анкеты', 'blank_2_path', array('path'=> $file_path), array_merge(array(), $attr_ext)));
        $this->addWidget(new nomvcImagePreviewWidget('Иной документ', 'other_path', array('path'=> $file_path), array_merge(array(), $attr_ext)));
    }
}
