<?php

/**
 * Валидатор даты/времени
 */
class agDateTimeValidator extends agBaseValidator
{

    public function clean($value)
    {
        $value = parent::clean($value);

        if (!$value) {
            return (string)$value;
        }

        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i', $value, $match)) {
            throw new agInvalidValueException($value);
        }
        list($f, $y, $m, $d, $h, $i, $s) = $match;
        if ($value != date('Y-m-d H:i:s', mktime($h, $i, $s, $m, $d, $y))) {
            throw new agInvalidValueException($value);
        }

        $min = $this->getOption('min');
        if ($min && $value < $min) {
            throw new agInvalidValueException($value);
        }

        return (string)$value;
    }

    public function __toString()
    {
        $params = array('Дата/время в формате YYYY-MM-DD HH24:MI:SS');
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
        $this->addOption('min', false, false);
    }

}
