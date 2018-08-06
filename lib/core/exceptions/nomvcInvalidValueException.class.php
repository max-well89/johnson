<?php

/**
 * Эксепшен, выбрасываемый валидатором при некорректном значении
 */
class nomvcInvalidValueException extends nomvcBaseException
{
    protected $value;
    protected $reason;
    protected $default_error_message = 'field_incorrect';

    public function __construct($value, $reason = 'invalid')
    {
        if (is_array($value)) {
            $value = implode(", ", $value);
        }
        parent::__construct(sprintf('Invalid value "%s"', $value));
        $this->value = $value;
        $this->reason = $reason;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function getErrorMessageText()
    {
        switch ($this->reason) {
            case 'required':
                $error_message = 'field_required';
                break;
            case 'invalid':
            case 'min':
            case 'max':
            default;
                $error_message = $this->default_error_message;
                break;
        }

        return $error_message;
    }
}