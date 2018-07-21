<?php

/**
 * Валидатор e-mail адресов
 */
class nomvcEmailValidator extends nomvcBaseValidator {

	protected function init() {
		parent::init();
	}

	public function clean($value) {
		$value = parent::clean($value);
		
		if (!$value) {
			return (string) $value;
		}
		
		if (!preg_match('/^[\.\-_\w\d]+?@[\.\-\w\d]+?\.[\w\d]{2,6}$/i', $value)) {
			throw new nomvcInvalidValueException($value);
		}
				
		return strtolower($value);
	}
	
	public function makeFormat($value){
        return preg_replace('/^[\.\-_\w\d]+?@[\.\-\w\d]+?\.[\w\d]{2,6}$/i', '', $value);
    }
}
