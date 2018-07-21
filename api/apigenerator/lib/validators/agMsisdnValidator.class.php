<?php

/**
 * Валидатор номера телефона
 */
class agMsisdnValidator extends agBaseValidator {

	protected function init() {
		parent::init();
	}

	public function clean($value) {
		$value = parent::clean($value);
		
		if (!$value) {
			return null;
		}
		
		if (!preg_match('/^7\d{10}$/', $value)) {
			throw new agInvalidValueException($value);
		}
				
		return (string) $value;
	}
	
	public function __toString() {
		$params = array('Номер телефона в формате 7DEF1234567');
		if ($this->getOption('required')) {
			$params[] = 'обязательный';
		} else {
			$params[] = 'не обязательный';
		}
		return implode(', ', $params);
	}
	
	public function getExample() {
		return $this->getOption('example', '70001234567');
	}

}
