<?php

/**
 *    Класс - основа экшенов
 */
abstract class agAbstractAction extends agAbstractComponent
{

    // параметры
    protected $parameters = array();
    // исключения
    protected $exceptions = array();
    // проверенные значения
    protected $values = array();

    /**  Возвращает человеческое название экшена (для документации) */
    public abstract function getTitle();

    /** Метод должен возвращать массив ролей пользователей. которым доступен этот экшен */
    public abstract function getAccessRoles();

    /**    Возвращает детальное описание экшена (для документации) */
    public function getDescription()
    {
        return false;
    }

    /**
     * Валидация входных параметров
     *
     * $params    входные параметры экшена
     */
    public function validate($params)
    {
        $values = array();
        foreach ($this->parameters as $name => $conf) {
            try {
                $values[$name] = $conf['validator']->clean(isset($params[$name]) ? $params[$name] : null);
            } catch (agInvalidValueException $ex) {
                // если связанный код ошибки не установлен - выбрасываем дефолтовое исключение
                if ($conf['errorcode'] && isset($this->exceptions[$conf['errorcode']])) {
                    $this->throwActionException($conf['errorcode']);
                } else {
                    throw new agActionException(sprintf('Ошибка в параметре "%s": %s', $name, $ex->getMessage()), Errors::BAD_PARAMETER);
                }
            }
        }
        $this->values = $values;
    }

    /**
     * Выброс зарегисрированного исключения, выбрасываемое исключение должно предварительно быть зарегисрировано методом registerActionException
     *
     * $code    код ошибки
     */
    protected function throwActionException($code)
    {
        throw new agActionException($this->exceptions[$code], $code);
    }

    /**
     * Возвращает проверенное значение
     *
     * $name    название входного параметра
     * $default    значение по умолчанию
     */
    public function getValue($name, $default = null)
    {
        return (isset($this->values[$name]) && $this->values[$name] !== null) ? $this->values[$name] : $default;
    }

    /** Возвращает список сконфигурированных параметров (для документации и тестов) */
    public function getParameters()
    {
        return $this->parameters;
    }

    /** Возвращает список зарегистрированных исключений (для документации и тестов) */
    public function getExceptions()
    {
        // успешное завершение - тоже своего рода исключение ;)
        $exceptions = array(Errors::SUCCESS => 'Запрос успешно выполнен');
        // получаем исключения из конфигурации параметров
        foreach ($this->parameters as $parameter => $conf) {
            if ($conf['errorcode']) {
                $exceptions[$conf['errorcode']] = 'Ошибка в параметре ' . $parameter;
            }
        }
        // добавляем зарегистрированные исключения
        foreach ($this->exceptions as $code => $description) {
            $exceptions[$code] = $description;
        }
        ksort($exceptions);
        return $exceptions;
    }

    /** Возвращает сгенерирорванный автоматически пример запроса (для документации и тестов) */
    public function getRequestExample()
    {
        $parameters = array();
        foreach ($this->parameters as $parameter => $conf) {
            $parameters[$parameter] = $conf['validator']->getExample();
        }
        return array('request' => array(
            'action' => $this->getAction(),
            'params' => $parameters
        ));
    }

    /**
     * Возвращает название(код) экшена
     * !!! ВНИМАНИЕ !!!    код экшена должен быть уникальным в рамках проекта,
     * так как используется для определения того, какой класс, по какому запросу запускать
     */
    public abstract function getAction();

    /** Возвращает сгенерирорванный автоматически пример ответа (для документации и тестов) */
    public function getResponseExample()
    {
        // TODO: Подумать об автоматической генерации примера ответа
        return array('result' => Errors::SUCCESS);
    }

    /**
     * Добавление параметра
     *
     * $name        название параметра
     * $validator    настроенный валидатор для параметра
     * $description    описание параметра для документации
     * $errorcode    связанный код ошибки, обязательно должен быть зарегистрирован методом registerActionException
     */
    protected function addParameter($name, $validator, $description, $errorcode = false)
    {
        $this->parameters[$name] = array(
            'validator' => $validator,
            'description' => $description,
            'errorcode' => $errorcode,
        );
    }

    /**
     * Регистрация кода ошибки
     *
     * $code        код ошибки
     * $description    описание ошибки
     */
    protected function registerActionException($code, $description)
    {
        $this->exceptions[$code] = $description;
    }

}
