<?php

class CategoryStatFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();
		
		$mobileOsArr = array(1 => 'Android', '2' => 'iOS', -1 => 'Все');
		$sexArr = array(1 => 'Мужчины', '2' => 'Женщины', -1 => 'Все');
		$ageArr = array(1 => 'от 0 до 17', 2 => 'от 18 до 24', 3 => 'от 25 до 34', 4 => 'от 35 до 44', 5 => 'от 45 и старше', -1 => 'Все');
		
				
		$this->addWidget(new nomvcButtonGroupWidget('ОС', 'id_mobile_os', array('options' => $mobileOsArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Пол', 'id_sex', array('options' => $sexArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Возраст', 'id_age_range', array('options' => $ageArr)));
		$this->addWidget(new nomvcInputHiddenWidget(null, 'id_map'));
				
		$this->addValidator('id_mobile_os', new nomvcStringValidator());
		$this->addValidator('id_sex', new nomvcStringValidator());
		$this->addValidator('id_age_range', new nomvcStringValidator());
		$this->addValidator('id_map', new nomvcIntegerValidator());
		
		$this->addContextMap('id_mobile_os', 'id_mobile_os');
		$this->addContextMap('id_sex', 'id_sex');
		$this->addContextMap('id_age_range', 'id_age_range');
		$this->addContextMap('id_map', 'this_id_map');
		
		$this->setDefault('id_mobile_os', -1);
		$this->setDefault('id_sex', -1);
		$this->setDefault('id_age_range', -1);
		
	}

}
