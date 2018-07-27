<?php

/**
 * Валидатор строковых значений
 */
class agRegexpValidator extends agStringValidator
{

    public function clean($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $value = parent::clean($value);

        if ($this->addOption('required') == false && $value == null) {
            return null;
        }

        if (!preg_match($this->getOption('regexp'), $value)) {
            throw new agInvalidValueException($value);
        }

        return (string)$value;
    }

    public function __toString()
    {
        return parent::__toString() . ', ' . $this->getOption('regexp_descr');
    }

    public function getExample()
    {
        return $this->getOption('example', 'Test string');
    }

    protected function init()
    {
        parent::init();
        $this->addOption('regexp', true);
        $this->addOption('regexp_descr', false, 'регулярное выражение');
    }

}
