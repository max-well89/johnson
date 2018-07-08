<?php

class BeaconForm extends nomvcAbstractForm {

	public function init() {
		parent::init();
		
		$this->addWidget(new nomvcInputHiddenWidget('id_map', 'id_map'));
		$this->addWidget(new nomvcInputHiddenWidget('x', 'x'));
		$this->addWidget(new nomvcInputHiddenWidget('y', 'y'));
		$this->addWidget(new nomvcInputHiddenWidget('mapkey', 'mapkey'));
		$this->addWidget(new nomvcInputHiddenWidget('id_beacon', 'id_beacon'));
		
		$this->addWidget(new nomvcInputTextWidget('ID', 'beacon_name', array(), array('maxlength' => 4)));
		$this->addWidget(new nomvcInputTextWidget('Major', 'major', array(), array('maxlength' => 6)));
		$this->addWidget(new nomvcInputTextWidget('Minor', 'minor', array(), array('maxlength' => 6)));
		
		$this->addWidget(new nomvcInputTextWidget('Latitude', 'latitude', array(), array('readonly' => 'readonly')));
		$this->addWidget(new nomvcInputTextWidget('Longitude', 'longitude', array(), array('readonly' => 'readonly')));
		
		$signal_powers = array(0,1,2,3,4,5,6,7);
		$this->addWidget(new nomvcSelectFromArrayWidget('Мощность', 'signal_power', array('options' => $signal_powers)));
		
		$this->addWidget(new nomvcTextareaWidget('Описание', 'description', array(), array('rows' => 5)));
		
		$this->addValidator('id_map', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('x', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('y', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('mapkey', new nomvcStringValidator(array('required' => false)));
		
		$this->addValidator('beacon_name', new nomvcRegexpValidator(array('required' => true, 'regexp' => '/^[\d\w]{4}$/')));
		$this->addValidator('major', new nomvcRegexpValidator(array('required' => true, 'regexp' => '/^\d{3,6}$/')));
		$this->addValidator('minor', new nomvcRegexpValidator(array('required' => true, 'regexp' => '/^\d{3,6}$/')));
		$this->addValidator('description', new nomvcStringValidator(array('required' => false, 'max' => 1000)));
		
		
	}
	
}
