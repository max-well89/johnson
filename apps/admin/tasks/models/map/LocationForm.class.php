<?php

class LocationForm extends nomvcAbstractForm {

	public function init() {
		parent::init();
		
		$this->addWidget(new nomvcInputHiddenWidget('id_map', 'id_map'));
		$this->addWidget(new nomvcInputHiddenWidget('mapkey', 'mapkey'));
		$this->addWidget(new nomvcInputHiddenWidget('id_location', 'id_location'));
		$this->addWidget(new nomvcInputHiddenWidget('old_id_location', 'old_id_location'));
		$this->addWidget(new nomvcInputHiddenWidget('x', 'x'));
		$this->addWidget(new nomvcInputHiddenWidget('y', 'y'));
		
		$this->addWidget(new nomvcSelectFromDbWidget('Локация', 'id_location', array(
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION_AVAILABLE',
			'key'	=> 'id_location',
			'val' => 'name'
		), array()));		
		
		$this->addWidget(new nomvcInputTextWidget('Latitude', 'latitude', array(), array('readonly' => 'readonly')));
		$this->addWidget(new nomvcInputTextWidget('Longitude', 'longitude', array(), array('readonly' => 'readonly')));
		
		$this->addValidator('id_map', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('mapkey', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('x', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('y', new nomvcStringValidator(array('required' => false)));
		
		$this->addValidator('id_location', new nomvcValueInDbValidator(array(
			'required'	=> true,
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION_AVAILABLE',
			'key'	=> 'id_location',
		)));
		$this->addValidator('old_id_location', new nomvcValueInDbValidator(array(
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION',
			'key'	=> 'id_location',
		)));
	}
	
}
