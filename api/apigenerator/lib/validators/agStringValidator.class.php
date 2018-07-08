<?php

/**
 * Валидатор строковых значений
 */
class agStringValidator extends agBaseValidator {

	protected function init() {
		parent::init();
		$this->addOption('min', false, false);
		$this->addOption('max', false, false);
	}

	public function clean($value) {
		$value = parent::clean($value);
		
		if ($this->getOption('required') == false && $value == null) {
			return null;
		}

		$min = $this->getOption('min');
		if ($min && mb_strlen($value, 'UTF-8') < $min) {
			throw new agInvalidValueException($value);
		}
		$max = $this->getOption('max');
		if ($max && mb_strlen($value, 'UTF-8') > $max) {
			throw new agInvalidValueException($value);
		};
		return (string) $value;
	}
	
	public function __toString() {
		$params = array('Строка');
		if ($this->getOption('required')) {
			$params[] = 'обязательный';
		} else {
			$params[] = 'не обязательный';
		}
		$min = $this->getOption('min');
		$max = $this->getOption('max');
		if ($min && $max && ($min == $max)) {
			$params[] = sprintf('%s символов', $max);
		} else {
			if ($min) $params[] = sprintf('не менее %s символов', $min);
			if ($max) $params[] = sprintf('не более %s символов', $max);
		}
		return implode(', ', $params);
	}
	
	public function getExample() {
		return $this->getOption('example', 'Test string');
	}

}
