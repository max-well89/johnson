<?php
/**
 * Форма Пользователи, здесь указываем поля и валидаторы
 */
class FeedbackForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $disabled = true;

        if ($disabled)
            $attr_ext = array('disabled' => 'disabled');
        else
            $attr_ext = [];
        
        $this->addWidget(new nomvcInputHiddenWidget('id_feedback', 'id_feedback'));
        $this->addValidator('id_feedback', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Тип пользователя', 'type_member', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('type_member', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcInputTextWidget('Способ связи', 'method', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('method', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcInputTextWidget('ФИО', 'fio', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('fio', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcInputTextWidget('Телефон для участия', 'msisdn', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('msisdn', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcInputTextWidget('Контактный email', 'email', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('email', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcInputTextWidget('Контактный телефон', 'msisdn_two', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('msisdn_two', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));
        
        $this->addWidget(new nomvcTextareaWidget('Вопрос', 'question', array(), array_merge(array(), $attr_ext)));
//        $this->addValidator('question', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));

        $this->addWidget(new nomvcTextareaWidget('Ответ', 'answer', array(), array()));
        $this->addValidator('answer', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 1000)));
        
        if ($this->isBined){
            if ($this->getValue('id_status') == 1){
                $this->getWidget('answer')->setAttribute('disabled', 'disabled');
            }
        }
    }
}
