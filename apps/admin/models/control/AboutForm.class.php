<?php

class AboutForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        //  $disabled = $this->context->getUser()->getAttribute('id_prize')?true:false;

        $this->addWidget(new nomvcInputHiddenWidget('id_about', 'id_about'));
        $this->addValidator('id_about', new nomvcIntegerValidator(array('required' => false)));

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
            'table' => 'V_ABOUT_STATUS',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_ABOUT_STATUS', 'key' => 'id_status')));

    }
}
