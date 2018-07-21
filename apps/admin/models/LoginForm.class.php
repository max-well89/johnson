<?php

class LoginForm extends nomvcAbstractForm {

	public function init() {
		parent::init();

		$this->addWidget(new nomvcInputTextWidget('login', 'login', array('label-width' => 4)));
		$this->addWidget(new nomvcInputPasswordWidget($this->context->translate('password'), 'password', array('label-width' => 4)));
		$this->addWidget(new nomvcButtonWidget('login', 'log_in', array('type' => 'submit')));
		
		$this->addValidator('login', new nomvcStringValidator(array('required' => true)));
		$this->addValidator('password', new nomvcStringValidator(array('required' => true)));
	}
}
