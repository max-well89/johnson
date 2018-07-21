<?php
/**
 * Фильтры для таблицы Тэгов
 */
class GeoObjectFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();

		$this->addWidget(new nomvcInputTextWidget("Название", "name"));
		$this->addValidator('name', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcInputTextWidget("Title", "name_eng"));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcSelectFromMultipleDbWidget("Тип", 'id_type', array("helper" => $this->context->getDbHelper(), "table" => "v_geo_type", "val" => "name", "key" => "id_geo_type")));
		$this->addValidator('id_type', new nomvcValueInDbMultipleValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "v_geo_type", "key" => "id_geo_type")));

		$this->addWidget(new nomvcInputTextWidget("Подчинённые объекты", "slave_object"));
		$this->addValidator('slave_object', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcSelectFromDbWidget("Публиковать", "is_display", array('helper' => $this->context->getDbHelper(), 'table' => 'V_YES_NO', 'key' => 'id_yes_no')));
		$this->addValidator('is_display', new nomvcValueInDbValidator(array('helper' => $this->context->getDbHelper(), 'table' => 'V_YES_NO', 'key' => 'id_yes_no')));

		$this->addButton('search');
		$this->addButton('reset');

		$this->addWidget(new nomvcButtonWidget('Создать город', 'create-city', array(
		    'type' => 'button',
		    'icon' => 'file'
			), array(
		    'onclick' => "return TableFormActions.getForm('city');",
		    'class' => 'btn btn-success'
		)));
		$this->addWidget(new nomvcButtonWidget('Создать регион', 'create-region', array(
		    'type' => 'button',
		    'icon' => 'file'
			), array(
		    'onclick' => "return TableFormActions.getForm('region');",
		    'class' => 'btn btn-success'
		)));
		$this->addWidget(new nomvcButtonWidget('Создать страну', 'create-country', array(
		    'type' => 'button',
		    'icon' => 'file'
		), array(
		    'onclick' => "return TableFormActions.getForm('country');",
		    'class' => 'btn btn-success'
		)));
	}

}
