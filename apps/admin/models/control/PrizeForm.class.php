<?php

class PrizeForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

      //  $disabled = $this->context->getUser()->getAttribute('id_prize')?true:false;

        $this->addWidget(new nomvcInputHiddenWidget('id_prize', 'id_prize'));
        $this->addValidator('id_prize', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Название', 'name', array()));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcTextareaWidget('Текстовое описание', 'description', array()));
        $this->addValidator('description', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcImageMultiFileWidget('Фотографии', 'photos', array("show-splash" => false)));
        $this->addValidator('photos', new nomvcImageMultiFileValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость в баллах', 'price', array()));
        $this->addValidator('price', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Общее количество', 'cnt_all', array()));
        $this->addValidator('cnt_all', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Категория', 'id_prize_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PRIZE_TYPE',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_prize_type', new nomvcValueInDbValidator(array(
            'required' => true, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_PRIZE_TYPE', 
            'key' => 'id_prize_type'
        )));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PRIZE_STATUS',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_PRIZE_STATUS', 'key' => 'id_status')));

    }
}
