<?php

/**
 * Валидатор чисел
 */
class nomvcCheckboxValidator extends nomvcBaseValidator
{

    public function clean($value)
    {
        $value = parent::clean($value);
        if ($this->getOption('required') == false && $value == null) return 0;

        if (!empty($value)) return $value;

    }

    protected function init()
    {
        parent::init();
    }

}
