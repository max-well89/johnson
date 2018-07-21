<?php

class NewsForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        //  $disabled = $this->context->getUser()->getAttribute('id_prize')?true:false;

        $this->addWidget(new nomvcInputHiddenWidget('id_news', 'id_news'));
        $this->addValidator('id_news', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Парковка', 'id_parking', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PARKING',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_parking', new nomvcValueInDbValidator(array(
            'required' => false, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_PARKING', 
            'key' => 'id_parking'
        )));
        
        $this->addWidget(new nomvcInputTextWidget('Название', 'name', array()));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcTextareaWidget('Краткое описание', 'short_description', array()));
        $this->addValidator('short_description', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcTextareaWidget('Полное описание', 'description', array()));
        $this->addValidator('description', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcImageMultiFileWidget('Фотографии', 'photos', array("show-splash" => false)));
        $this->addValidator('photos', new nomvcImageMultiFileValidator(array('required' => false)));

        //$this->addWidget(new nomvcInputDateTimePickerWidget('Дата публикации', 'dt_public', array(), array()));
        //$this->addValidator('dt_public', new nomvcDateValidator(array('in_format' => DateHelper::HTMLT_FORMAT)));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_NEWS_STATUS',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_NEWS_STATUS', 'key' => 'id_status')));

    }
}
