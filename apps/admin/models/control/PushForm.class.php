<?php

class PushForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_push', 'id_push'));
        $this->addValidator('id_push', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Текст сообщения', 'message', array()));
        $this->addValidator('message', new nomvcStringValidator(array('required' => true)));

        $this->addWidget(new nomvcInputDateTimePickerWidget('Время начала отправки', 'dt_start', array()));
        $this->addValidator('dt_start', new nomvcDateValidator(array('required' => false, 'in_format' => DateHelper::HTMLT_FORMAT)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'order' => 'name',
            'required' => true,
            'order' => 'id_status'
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array(
            'required' => true,
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PUSH_STATUS',
            'key' => 'id_status'
        )));

    }
}
