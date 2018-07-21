<?php

class ObjectsDeleteConfirmForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_OBJECT
		$this->addWidget(new nomvcInputHiddenWidget('id_object', 'id_object'));
		$this->addValidator('id_object', new nomvcIntegerValidator(array('required' => false)));

		$this->addWidget(new nomvcPlainTextWidget('Внимание. Эти действия необратимы!!!', 'form_text'));
	}

}
