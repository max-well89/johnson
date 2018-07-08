<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class FaqForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_faq', 'id_faq'));
        $this->addValidator('id_faq', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Группа', 'id_faq_group', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_FAQ_GROUP',
            'order' => 'name',
            'required' => true,
            'order' => 'id_faq_group'
        )));

        $this->addValidator('id_faq_group', new nomvcValueInDbValidator(array(
            'required' => true, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_FAQ_GROUP', 
            'key' => 'id_faq_group'
        )));
        
        $this->addWidget(new nomvcInputTextWidget('Заголовок', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 100)));

        $this->addWidget(new nomvcTextareaWidget('Описание', 'description'));
        $this->addValidator('description', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 3000)));

        

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_FAQ_STATUS',
            'order' => 'name',
            'required' => true
        )));

        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_FAQ_STATUS',
            'key' => 'id_status'
        )));
    }
}
