<?php

class PrizeRequestFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();

        //Период
        //$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt"));
        //$this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcInputTextWidget('ID', 'id_news'));
        $this->addValidator('id_news', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Сатус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_NEWS_STATUS',
            'order' => 'name',
            'required' => false
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, 'helper' => $this->context->getDbHelper(), 'table' => 'V_NEWS_STATUS', 'key' => 'id_status')));


        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        $this->addWidget(new nomvcButtonWidget('Добавить новость', 'create', array(
            'type' => 'button',
            'icon' => 'file'
        ), array(
            'onclick' => "return TableFormActions.getForm('news');",
            'class' => 'btn btn-success'
        )));

    }

}
