<?php

/**
 * Валидатор строковых значений
 */
class nomvcRegexpLightValidator extends nomvcStringValidator {

    protected function init() {
        parent::init();
        $this->addOption('regexp', true);
        $this->addOption('regexp_descr', false, 'регулярное выражение');
    }

    public function clean($value) {
        $value = parent::clean($value);

        if ($this->addOption('required') == false && $value == null) {
            return null;
        }
        
        $value = preg_replace($this->getOption('regexp'), '', $value);
        
        return (string) $value;
    }

}
