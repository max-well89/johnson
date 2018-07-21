<?php

class HeatmapFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();
		
		$this->setAttribute('id', 'heatmap-filter');
		$this->setAttribute('onsubmit', 'clearTimeout(Map.periodicLoadDataTimer); Map.periodicLoadData(); return false;');
		
		$mobileOsArr = array(1 => 'Android', '2' => 'iOS', -1 => 'Все');
		$sexArr = array(1 => 'Мужчины', '2' => 'Женщины', -1 => 'Все');
		$ageArr = array(1 => 'от 0 до 17', 2 => 'от 18 до 24', 3 => 'от 25 до 34', 4 => 'от 35 до 44', 5 => 'от 45 и старше', -1 => 'Все');
		$periodArr = array(1 => 'Сутки', 7 => 'Неделя', 30 => 'Месяц');
						
		$this->addWidget(new nomvcButtonGroupWidget('ОС', 'id_mobile_os', array('options' => $mobileOsArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Пол', 'id_sex', array('options' => $sexArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Возраст', 'id_age_range', array('options' => $ageArr)));
		$this->addWidget(new nomvcButtonGroupWidget('Период', 'period', array('options' => $periodArr)));
				
		$this->addValidator('id_mobile_os', new nomvcStringValidator());
		$this->addValidator('id_sex', new nomvcStringValidator());
		$this->addValidator('id_age_range', new nomvcStringValidator());
		$this->addValidator('period', new nomvcStringValidator());
				
		$this->setDefault('id_mobile_os', -1);
		$this->setDefault('id_sex', -1);
		$this->setDefault('id_age_range', -1);
		$this->setDefault('period', 7);

	}

}
