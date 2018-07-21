<?php

class RoutePointForm extends nomvcAbstractForm {

	public function init() {
		parent::init();
		
		$this->addWidget(new nomvcInputHiddenWidget('id_map', 'id_map'));
		$this->addWidget(new nomvcInputHiddenWidget('x', 'x'));
		$this->addWidget(new nomvcInputHiddenWidget('y', 'y'));
		$this->addWidget(new nomvcInputHiddenWidget('mapkey', 'mapkey'));
		$this->addWidget(new nomvcInputHiddenWidget('id_route_point', 'id_route_point'));
		
		$this->addWidget(new nomvcInputTextWidget('Latitude', 'latitude', array(), array('readonly' => 'readonly')));
		$this->addWidget(new nomvcInputTextWidget('Longitude', 'longitude', array(), array('readonly' => 'readonly')));
		$this->addWidget(new nomvcSelectFromDbWidget('Локация', 'id_location', array(
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION',
			'key'	=> 'id_location',
			'val' => 'name'
		), array()));
				
		$this->addValidator('id_map', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('x', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('y', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('mapkey', new nomvcStringValidator(array('required' => false)));
		$this->addValidator('id_location', new nomvcValueInDbValidator(array(
			'helper' => $this->context->getDbHelper(),
			'table'	=> 'V_LOCATION',
			'key'	=> 'id_location',
		)));
		
		
	}
	
}
