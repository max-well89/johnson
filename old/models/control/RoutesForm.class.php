<?php
/**
 * Форма Тэгов, здесь указываем поля и валидаторы
 */
class RoutesForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_ROUTE
		$this->addWidget(new nomvcInputHiddenWidget('id_route', 'id_route'));
		$this->addValidator('id_route', new nomvcIntegerValidator(array('required' => false)));

		//ID_AUTHOR
		$this->addWidget(new nomvcInputHiddenWidget('id_author', 'id_author'));
		$this->addValidator('id_author', new nomvcIntegerValidator(array('required' => false)));

		//Фотографии
		$this->addWidget(new nomvcImageMultiFileWidget('Фотографии', 'photos'));
		$this->addValidator('photos', new nomvcImageMultiFileValidator(array('required' => false)));
		
		//Название
		$this->addWidget(new nomvcInputTextWidget('Название', 'name'));
		$this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Название на английском
		$this->addWidget(new nomvcInputTextWidget('Name', 'name_eng'));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Тэги
		$this->addWidget(new nomvcSelectFromMultipleDbWidget('Объекты', 'objects', array(
		    'helper' => $this->context->getDbHelper(), 'table' => 'V_OBJECT_LIST', 'key' => 'id_object'), array()));
		$this->addValidator('objects', new nomvcArrayValidator(array('required' => false)));

		//Опубликовать
		$this->addWidget(new nomvcInputCheckboxWidget("Опубликовать", "is_display", array(), array("value" => 1)));
		$this->addValidator('is_display', new nomvcCheckboxValidator(array('required' => false)));

		//Редактируемый
		$this->addWidget(new nomvcInputCheckboxWidget("Редактируемый", "is_edited", array(), array("value" => 1)));
		$this->addValidator('is_edited', new nomvcCheckboxValidator(array('required' => false)));

	}

}
