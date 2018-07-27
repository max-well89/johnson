<?php

/**
 * Валидатор даты/времени
 */
class agDateValidator extends agBaseValidator
{

    public function clean($value)
    {
        $value = parent::clean($value);

        if (!$value) {
            return (string)$value;
        }

        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/i', $value, $matches)) {
            throw new agInvalidValueException($value);
        }
        list($f, $y, $m, $d) = $matches;
        if ($f != date('Y-m-d', mktime(0, 0, 0, $m, $d, $y))) {
            throw new agInvalidValueException($value);
        }

        return (string)$value;
    }

    public function __toString()
    {
        $params = array('Дата в формате YYYY-MM-DD');
        if ($this->getOption('required')) {
            $params[] = 'обязательный';
        } else {
            $params[] = 'не обязательный';
        }
        return implode(', ', $params);
    }

    public function getExample()
    {
        return $this->getOption('example', date('Y-m-d H:i:s'));
    }

    protected function init()
    {
        parent::init();
    }

}
