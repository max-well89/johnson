<?php
/**
 * Форма Тэгов, здесь указываем поля и валидаторы
 */
class GeoObjectForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_GEO_OBJECT
		$this->addWidget(new nomvcInputHiddenWidget('id_geo_object', 'id_geo_object'));
		$this->addValidator('id_geo_object', new nomvcIntegerValidator(array('required' => false)));

		//ID_TYPE
		$this->addWidget(new nomvcInputHiddenWidget('id_type', 'id_type'));
		$this->addValidator('id_type', new nomvcIntegerValidator(array('required' => false)));

		//Название
		$this->addWidget(new nomvcInputTextWidget('Название', 'name'));
		$this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Название на английском
		$this->addWidget(new nomvcInputTextWidget('Name', 'name_eng'));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Сортировка в списке
		$this->addWidget(new nomvcInputTextWidget('Широта', 'latitude'));
		$this->addValidator('latitude', new nomvcNumberValidator(array('required' => true, "min" => 0)));

		//Сортировка в списке
		$this->addWidget(new nomvcInputTextWidget('Долгота', 'longitude'));
		$this->addValidator('longitude', new nomvcNumberValidator(array('required' => true, "min" => 0)));

		//Подчинённые объекты, есть только у стран и регионов
		switch ($this->getAttribute("type")) {
			//для страны - регионы
			case "country":
				$this->addWidget(new nomvcSelectFromMultipleDbWidget('Регионы', 'geo_object_slaves', array(
					'helper' => $this->context->getDbHelper(), 'table' => 'V_REGION', 'key' => 'id_region'), array()));
				$this->addValidator('geo_object_slaves', new nomvcArrayValidator(array('required' => false)));
				break;
			//для регионов - города
			case "region":
				$this->addWidget(new nomvcSelectFromMultipleDbWidget('Города', 'geo_object_slaves', array(
				    'helper' => $this->context->getDbHelper(), 'table' => 'V_CITY', 'key' => 'id_city'), array()));
				$this->addValidator('geo_object_slaves', new nomvcArrayValidator(array('required' => false)));
				break;
		}

		//Опубликовать
		$this->addWidget(new nomvcInputCheckboxWidget("Опубликовать", "is_display", array(), array("value" => 1)));
		$this->addValidator('is_display', new nomvcCheckboxValidator(array('required' => false)));
	}

}
