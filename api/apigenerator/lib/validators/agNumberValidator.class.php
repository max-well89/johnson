<?php

/**
 * Валидатор чисел
 */
class agNumberValidator extends agBaseValidator {

	protected function init() {
		parent::init();
		$this->addOption('decimal_in', false, ',.');
		$this->addOption('decimal_out', false, '.');
		$this->addOption('min', false, false);
		$this->addOption('max', false, false);
	}

	public function clean($value) {
		$value = parent::clean($value);
		
		if ($this->getOption('required') == false && $value == null) {
			return null;
		}
		
		$regexp_check = sprintf('/^\-?\d+([%s]\d+)?$/', $this->getOption('decimal_in'));
		$regexp_clean = sprintf('/[%s]/', $this->getOption('decimal_in'));
		if (!preg_match($regexp_check, $value)) {
			throw new agInvalidValueException($value);
		}
		return (float) preg_replace($regexp_clean, $this->getOption('decimal_out'), $value);
	}
	
	public function __toString() {
		$params = array('С плавающей точкой');
		if ($this->getOption('required')) {
			$params[] = 'обязательный';
		} else {
			$params[] = 'не обязательный';
		}
		
		$min = $this->getOption('min');
		if ($min) $params[] = sprintf('не менее %s', $min);
		$max = $this->getOption('max');
		if ($max) $params[] = sprintf('не более %s', $max);
		
		return implode(', ', $params);
	}
	
	public function getExample() {
		$min = $this->getOption('min', 1);
		$max = $this->getOption('max', $min + 99);
		return $this->getOption('example', sfMoreSecure::crypto_rand_secure($min, $max) + (sfMoreSecure::crypto_rand_secure(1, 999999) / 1000000));
	}

}
