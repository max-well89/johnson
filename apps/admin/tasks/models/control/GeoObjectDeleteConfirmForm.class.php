<?php

/**
 * Тэги. Подтверждение удаления
 */
class GeoObjectDeleteConfirmForm extends nomvcAbstractForm {
	public function init() {
		parent::init();

		//ID_GEO_OBJECT
		$this->addWidget(new nomvcInputHiddenWidget('id_geo_object', 'id_geo_object'));
		$this->addValidator('id_geo_object', new nomvcIntegerValidator(array('required' => false)));

		$this->addWidget(new nomvcPlainTextWidget('Внимание. Эти действия необратимы!!!', 'form_text'));
	}

}
