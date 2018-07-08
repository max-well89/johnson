<?php

class ParkingTariffForm extends nomvcAbstractForm {
    public function init() {
        parent::init();

        $this->addWidget(new nomvcInputHiddenWidget('id_parking_tariff', 'id_parking_tariff'));
        $this->addValidator('id_parking_tariff', new nomvcIntegerValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Название', 'name'));
        $this->addValidator('name', new nomvcStringValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость в час', 'cost_hour'));
        $this->addValidator('cost_hour', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость в сутки', 'cost_day'));
        $this->addValidator('cost_day', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за месяц', 'cost_month'));
        $this->addValidator('cost_month', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за месяц (день)', 'cost_month_daily'));
        $this->addValidator('cost_month_daily', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за месяц (ночь)', 'cost_month_nightly'));
        $this->addValidator('cost_month_nightly', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 3 месяца', 'cost_month3'));
        $this->addValidator('cost_month3', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 3 месяца (день)', 'cost_month3_daily'));
        $this->addValidator('cost_month3_daily', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 3 месяца (ночь)', 'cost_month3_nightly'));
        $this->addValidator('cost_month3_nightly', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 6 месяцев', 'cost_month6'));
        $this->addValidator('cost_month6', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 6 месяцев (день)', 'cost_month6_daily'));
        $this->addValidator('cost_month6_daily', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 6 месяцев (ночь)', 'cost_month6_nightly'));
        $this->addValidator('cost_month6_nightly', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 12 месяцев', 'cost_month12'));
        $this->addValidator('cost_month12', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 12 месяцев (день)', 'cost_month12_daily'));
        $this->addValidator('cost_month12_daily', new nomvcNumberValidator(array('required' => false)));

        $this->addWidget(new nomvcInputTextWidget('Стоимость за 12 месяцев (ночь)', 'cost_month12_nightly'));
        $this->addValidator('cost_month12_nightly', new nomvcNumberValidator(array('required' => false)));        
        
        $this->addWidget(new nomvcSelectFromMultipleDbWidget('Статус', 'id_status', array(
            'helper' => $this->context->getDbHelper(),
            'table' => 'V_PARKING_TARIFF_STATUS',
            'order' => 'name',
            'required' => true
        ), array()));
        $this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_PARKING_TARIFF_STATUS', 'key' => 'id_status')));

    }
}
