<?php

class PurposeFilterForm extends nomvcAbstractFilterForm{
    public function init() {
        parent::init();
        
        //Период
        $this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt"));
        $this->addValidator("dt", new nomvcDatePeriodValidator());

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Тип цели', 'id_purpose_type', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PURPOSE_TYPE_MAIN',
            'order' => 'id_purpose_type',
            'required' => false,
            'multiple' => true
        ), array()));

        $this->addValidator('id_purpose_type', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_PURPOSE_TYPE_MAIN",
            "key" => "id_purpose_type"
        )));

        $this->addWidget(new nomvcInputTextWidget("Название", "name"));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false,'min' => 2, 'max' => 200)));

        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PURPOSE_STATUS',
            'order' => 'name',
            'required' => false,
            'multiple' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbMultipleValidator(array(
            'required' => false,
            "helper" => $this->context->getDbHelper(),
            "table" => "V_PURPOSE_STATUS",
            "key" => "id_status"
        )));

        $this->addButton('search');
        $this->addButton('reset');
        $this->addButton('export');

        if (!$this->context->getUser()->getAttribute('id_restaurant')) {
            $this->addWidget(new nomvcSelectFromMultipleDbWidget('Месяц для статистики', 'id_month', array(
                'helper' => $this->context->getDbHelper(),
                'table' => 'T_MONTH',
                'order' => 'id_month',
                'required' => false,
                'multiple' => true
            ), array()));
            $this->addValidator('id_month', new nomvcValueInDbMultipleValidator(array(
                'required' => false,
                'helper' => $this->context->getDbHelper(),
                'table' => 'T_MONTH',
                'key' => 'id_month'
            )));
            
            
            $this->addWidget(new nomvcButtonWidget('Добавить доп. цель', 'create_three', array(
                'type' => 'button',
                'icon' => 'file'
            ), array(
                'onclick' => "return TableFormActions.getForm('purpose-three');",
                'class' => 'btn btn-success'
            )));

            $this->addWidget(new nomvcButtonWidget('Добавить цель на скорость и вкус', 'create_two', array(
                'type' => 'button',
                'icon' => 'file'
            ), array(
                'onclick' => "return TableFormActions.getForm('purpose-two');",
                'class' => 'btn btn-success'
            )));

            $this->addWidget(new nomvcButtonWidget('Добавить цель на увеличение продаж', 'create_one', array(
                'type' => 'button',
                'icon' => 'file'
            ), array(
                'onclick' => "return TableFormActions.getForm('purpose-one');",
                'class' => 'btn btn-success'
            )));
            
        }
        
/*        if (!$this->context->getUser()->getAttribute('id_restaurant')) {
            $this->addWidget(new nomvcButtonWidget('Добавить цель', 'create', array(
                'type' => 'button',
                'icon' => 'file'
            ), array(
                'onclick' => "return TableFormActions.getForm('purpose');",
                'class' => 'btn btn-success'
            )));
        }
*/
    }

}
