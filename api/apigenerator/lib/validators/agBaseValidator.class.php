<?php

/**
 * Абстрактный валидатор
 */
abstract class agBaseValidator
{

    // список опций
    protected $optionsVal = array();
    // список значений опций
    private $options = array();

    public final function __construct($options = array())
    {
        $this->init();
        $this->checkOptions($options);
    }

    /** Инициализация валидатора */
    protected function init()
    {
        $this->addOption('required', false, false);
        $this->addOption('pre_trim', false, true);
        $this->addOption('example', false, null);
    }

    /**
     * Добавление опции
     *
     * $option        название опции
     * $required    обязательна ли опция?
     * $default        значение по умолчанию
     */
    protected function addOption($option, $required = false, $default = null)
    {
        $this->options[$option] = array(
            'default' => $default,
            'required' => $required
        );
    }

    /**
     * Проверка корректности настроек виджета
     */
    protected function checkOptions($options)
    {
        // проверяем, что нам не передали лишних опций
        foreach ($options as $option => $val) {
            if (!isset($this->options[$option])) {
                throw new agAttributeException(sprintf('Incorrect option %s for validator %s', $option, get_class($this)));
            }
            $this->optionsVal[$option] = $val;
        }
        // проверяем, что все необходимые опции установлены
        foreach ($this->options as $option => $param) {
            if (!isset($this->optionsVal[$option])) {
                if ($param['required']) {
                    throw new agAttributeException(sprintf('Option %s required for validator %s', $option, get_class($this)));
                }
                $this->optionsVal[$option] = $param['default'];
            }
        }
    }

    /** Этот метод должен гененрировать текстовое описание сконфигурированного валидатора */
    public abstract function __toString();

    /** метод должен возвращать пример валидного (с учётом конфигурации) значения */
    public abstract function getExample();

    /** Вызов процесса валидации */
    public function clean($value)
    {
        if ($this->getOption('pre_trim')) {
            $value = trim($value);
        }
        if ($value == null) {
            if ($this->getOption('required')) {
                throw new agInvalidValueException($value, 'required');
            } else {
                return null;
            }
        }
        return $value;
    }

    /**
     * Возвращает значение опции или значение по умолчанию
     *
     * $option    опция
     * $default значение по умолчанию
     */
    public function getOption($option, $default = null)
    {
        return $this->optionsVal[$option] ? $this->optionsVal[$option] : $default;
    }

}
