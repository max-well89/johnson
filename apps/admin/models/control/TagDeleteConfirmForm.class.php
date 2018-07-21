<?php

/**
 * Тэги. Подтверждение удаления
 */
class TagDeleteConfirmForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_TAG
		$this->addWidget(new nomvcInputHiddenWidget('id_tag', 'id_tag'));
		$this->addValidator('id_tag', new nomvcIntegerValidator(array('required' => false)));

		$this->addWidget(new nomvcPlainTextWidget('Внимание. Эти действия необратимы!!!', 'form_text'));
	}

}
