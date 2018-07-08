<?php

class ParkingForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

      //  $disabled = $this->context->getUser()->getAttribute('id_prize')?true:false;

        $this->addWidget(new nomvcInputHiddenWidget('id_parking', 'id_parking'));
        $this->addValidator('id_parking', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('№', 'ext_num', array()));
        $this->addValidator('ext_num', new nomvcNumberValidator(array('required' => true)));
        
        $this->addWidget(new nomvcInputTextWidget('Название', 'name', array()));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcTextareaWidget('Адрес', 'address', array()));
        $this->addValidator('address', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Вместимость', 'capacity', array()));
        $this->addValidator('capacity', new nomvcIntegerValidator(array('required' => false)));
        
        $this->addWidget(new nomvcInputTextWidget('Широта', 'latitude', array()));
        $this->addValidator('latitude', new nomvcNumberValidator(array('required' => false)));
        
        $this->addWidget(new nomvcInputTextWidget('Долгота', 'longitude', array()));
        $this->addValidator('longitude', new nomvcNumberValidator(array('required' => false)));
        
        $this->addWidget(new nomvcImageMultiFileWidget('Фотографии', 'photos', array("show-splash" => false)));
        $this->addValidator('photos', new nomvcImageMultiFileValidator(array('required' => false)));
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Характеристики', 'id_tag', array(
            'with-add' => true, 
            'helper' => $this->context->getDbHelper(),
            'table' => 'T_TAG',
            'with-add-url' => '/admin.php/backend/parking/add-parking-tag/',
            'multiple' => true
        )));
        $this->addValidator('id_tag', new nomvcArrayValidator(array('required' => false, 'max' => 1000)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Действующий тариф', 'id_parking_tariff', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_ACTIVE_PARKING_TARIFF',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_parking_tariff', new nomvcValueInDbValidator(array(
            'required' => false, 
            'helper' => $this->context->getDbHelper(), 
            'table' => 'V_ACTIVE_PARKING_TARIFF', 
            'key' => 'id_parking_tariff'
        )));

        $this->addWidget(new nomvcInputTextWidget('Ссылка на видео', 'path_video', array()));
        $this->addValidator('path_video', new nomvcStringValidator(array('required' => false)));
        
        //$this->addWidget(new nomvcInputTextWidget('Общее количество', 'cnt_all', array()));
        //$this->addValidator('cnt_all', new nomvcIntegerValidator(array('required' => false)));

        /*$this->addWidget(new nomvcSelectFromMultipleDbWidget('Категория', 'id_prize_type', array(
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
        */
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PARKING_STATUS',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_PARKING_STATUS', 'key' => 'id_status')));

    }
}
