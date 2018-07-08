<?php

/**
 * Маршруты. Подтверждение удаления
 */
class RoutesDeleteConfirmForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_ROUTE
		$this->addWidget(new nomvcInputHiddenWidget('id_route', 'id_route'));
		$this->addValidator('id_route', new nomvcIntegerValidator(array('required' => false)));

		$this->addWidget(new nomvcPlainTextWidget('Внимание. Эти действия необратимы!!!', 'form_text'));
	}

}
