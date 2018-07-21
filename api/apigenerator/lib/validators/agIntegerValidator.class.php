<?php

/**
 * Валидатор целых чисел
 */
class agIntegerValidator extends agBaseValidator {
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
		
		if (!preg_match('/^\-?\d+$/', $value)) {
			throw new agInvalidValueException($value);
		}
		
		$value = (int) $value;
		
		$min = $this->getOption('min');
		if ($min && $value < $min) {
			throw new agInvalidValueException($value);
		}
		$max = $this->getOption('max');
		if ($max && $value > $max) {
			throw new agInvalidValueException($value);
		}
		
		return $value;
	}
	
	public function __toString() {
		$params = array('Целое');
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
		return $this->getOption('example', sfMoreSecure::crypto_rand_secure($min, $max));
	}

}
