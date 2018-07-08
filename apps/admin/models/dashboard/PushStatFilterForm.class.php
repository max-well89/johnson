<?php

class PushStatFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();
		
		$mobileOsArr = array(1 => 'Android', '2' => 'iOS', -1 => 'Все');
		$dtDivideArr = array('DD' => 'Дни', 'MM' => 'Месяцы');
		
		$this->addWidget(new nomvcButtonGroupWidget('ОС', 'id_mobile_os', array('options' => $mobileOsArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Период', 'dt_divide', array('options' => $dtDivideArr)));
		$this->addWidget(new nomvcInputDatePeriodPickerWidget('Период', 'date'));
		$this->addWidget(new nomvcInputHiddenWidget(null, 'id_map'));
				
		$this->addValidator('id_mobile_os', new nomvcStringValidator());
		$this->addValidator('dt_divide', new nomvcStringValidator());
		$this->addValidator('date', new nomvcDatePeriodValidator());
		$this->addValidator('id_map', new nomvcIntegerValidator());
		
		$this->addContextMap('id_mobile_os', 'id_mobile_os');
		$this->addContextMap('dt_divide', 'dt_divide');
		$this->addContextMap('date', 'date');
		$this->addContextMap('id_map', 'this_id_map');
		
		$this->setDefault('id_mobile_os', -1);
		$this->setDefault('dt_divide', 'DD');
		$this->setDefault('date', array(
			'from' => date(DateHelper::HTMLD_FORMAT, mktime(0, 0, 0, date('m'), 1)),
			'to' => date(DateHelper::HTMLD_FORMAT, mktime(23, 59, 59, date('m') + 1, 0)),
		));
		
		//кнопки
		$this->addButton('search');
		$this->addButton('reset');

	}

}
