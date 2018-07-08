<?php
/**
 * Форма Тэгов, здесь указываем поля и валидаторы
 */
class TagForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_TAG
		$this->addWidget(new nomvcInputHiddenWidget('id_tag', 'id_tag'));
		$this->addValidator('id_tag', new nomvcIntegerValidator(array('required' => false)));

		//Название
		$this->addWidget(new nomvcInputTextWidget('Название', 'name'));
		$this->addValidator('name', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Название на английском
		$this->addWidget(new nomvcInputTextWidget('Name', 'name_eng'));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => true,'min' => 2, 'max' => 200)));

		//Сортировка в списке
		$this->addWidget(new nomvcInputTextWidget('Сортировка', 'order_by_type'));
		$this->addValidator('order_by_type', new nomvcNumberValidator(array('required' => true, "min" => 0)));

		//Опубликовать
		$this->addWidget(new nomvcInputCheckboxWidget("Опубликовать", "is_display", array(), array("value" => 1)));
		$this->addValidator('is_display', new nomvcCheckboxValidator(array('required' => false)));
	}

}
