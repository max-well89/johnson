<?php
/**
 * Фильтры для таблицы Тэгов
 */
class TagFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();

		$this->addWidget(new nomvcInputTextWidget("Название", "name"));
		$this->addValidator('name', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcInputTextWidget("Title", "name_eng"));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcSelectFromDbWidget('Публиковать', 'is_display', array("helper" => $this->context->getDbHelper(), "table" => "V_YES_NO", "key" => "id_yes_no")));
		$this->addValidator('is_display', new nomvcValueInDbValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_YES_NO", "key" => "id_yes_no")));


		$this->addButton('search');
		$this->addButton('reset');

		$this->addWidget(new nomvcButtonWidget('Создать тэг', 'create', array(
		    'type' => 'button',
		    'icon' => 'file'
		), array(
		    'onclick' => "return TableFormActions.getForm('tag');",
		    'class' => 'btn btn-success'
		)));

	}

}
