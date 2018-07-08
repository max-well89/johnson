<?php

class agActionException extends agBaseException {
    protected $fieldName = false;

    /**
     * Кастомный коструктор для эксепшена Action-a
     * @param string $message	Текст ошибки
     * @param string $code		Код ошибки
     * @param string $previous	Предыдущая ошибка
     * @param string $fieldName	Поле, где произошла ошибка
     */
    public function __construct($message = "", $code = 0, $previous = null, $fieldName = false) {
        $this->fieldName = $fieldName;

        parent::__construct($message, $code);
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function setFieldName($fieldName) {
        return $this->fieldName = $fieldName;
    }
    
}
