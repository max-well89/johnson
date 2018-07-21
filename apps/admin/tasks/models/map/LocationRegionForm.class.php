<?php

class LocationRegionForm extends nomvcAbstractForm {

	public function init() {
		parent::init();
		
		$this->addWidget(new nomvcInputHiddenWidget('id_map', 'id_map'));
		$this->addWidget(new nomvcInputHiddenWidget('mapkey', 'mapkey'));
		$this->addWidget(new nomvcInputHiddenWidget('id_location_region', 'id_location_region'));
				
		$this->addWidget(new nomvcSelectFromDbWidget('Локация', 'id_location', array(
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION',
			'key'	=> 'id_location',
			'val' => 'name'
		), array()));
		$this->addWidget(new nomvcInputColorWidget('Цвет', 'color'));
		
				
		$this->addValidator('id_map', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('mapkey', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('id_location_region', new nomvcStringValidator(array('required' => false)));
		
		$this->addValidator('id_location', new nomvcValueInDbValidator(array(
			'required'	=> true,
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION',
			'key'	=> 'id_location',
		)));
		$this->addValidator('color', new nomvcColorValidator(array('required' => false)));
		
	}
	
}
