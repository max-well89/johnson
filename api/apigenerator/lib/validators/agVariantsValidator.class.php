<?php

/**
 * Валидатор по списку значений
 */
class agVariantsValidator extends agBaseValidator {

	protected function init() {
		parent::init();
		$this->addOption('variants', true, false);
	}

	public function clean($value) {
		$value = parent::clean($value);
		if ($this->addOption('required') == false && $value == null) {
			return null;
		}

		$variants = $this->getOption('variants');
		if (!in_array($value, $variants)) {
			throw new agInvalidValueException($value);
		}

		return (string) $value;
	}
	
	public function __toString() {
		
		$params = array();
		if ($this->getOption('required')) {
			$params[] = 'обязательный';
		} else {
			$params[] = 'не обязательный';
		}
		
		$variants = $this->getOption('variants');
		$variantsFormatted = array();
		foreach ($variants as $variant => $description) {
			$variantsFormatted[] = "$variant - $description";
		}
		
		$params[] = sprintf('варианты значений: <br>%s<br>', implode(' <br> ', $variantsFormatted));
		return implode(', ', $params);
	}
	
	public function getExample() {
		$vals = $this->getOption('variants');

		$variants = array_keys($vals);
		return $vals[sfMoreSecure::crypto_rand_secure(0, count($variants) - 1)];
	}

}
