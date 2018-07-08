<?php

/**
 * Валидатор e-mail адресов
 */
class agEmailValidator extends agBaseValidator {

	protected function init() {
		parent::init();
	}

	public function clean($value) {
		$value = parent::clean($value);
		
		if (!$value) {
			return (string) $value;
		}
		
		if (!preg_match('/^[\.\-_\w\d]+?@[\.\-\w\d]+?\.[\w\d]{2,6}$/i', $value)) {
			throw new agInvalidValueException($value);
		}
				
		return (string) $value;
	}
	
	public function __toString() {
		$params = array('Корректный e-mail');
		if ($this->getOption('required')) {
			$params[] = 'обязательный';
		} else {
			$params[] = 'не обязательный';
		}
		return implode(', ', $params);
	}
	
	public function getExample() {
		return $this->getOption('example', 'test@example.com');
	}

}
