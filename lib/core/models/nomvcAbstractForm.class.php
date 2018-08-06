<?php

/**
 * Класс - основа формы
 */
abstract class nomvcAbstractForm
{

    public $widgets = array();
    public $valueErrors = array();        // виджеты формы
    protected $context = null;    // валидаторы формы
    protected $validators = array();    // ошибки в полях формы
    protected $errorValues = array();    // значения ошибочных полей
    protected $values = array();        // проверенные значения
    protected $defaultValues = array();
    protected $attributes = array();    // проверенные значения
    protected $errorMessage = null;        // сообщение об ошибке
    protected $notifyMessage = null;

    protected $javascripts = [];
    protected $stylesheets = [];

    protected $isBined = false;
    protected $isSend = false;

    protected $textErrors = array();

    /**
     * Конструктор
     * $context                Контекст выполнения
     */
    public function __construct($context, $attributes = array())
    {
        $this->context = $context;
        $this->attributes = $attributes;
        $this->init();
    }

    /**
     * Инициализация формы, здесь должны быть установлены виджеты и валидаторы
     */
    protected function init()
    {
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('role', 'form');
        $user = $this->context->getUser();
        $this->setDBContextParameter('id_member', $user->getAttribute('id_member'));
        if ($user->getAttribute('id_language'))
            $this->setDBContextParameter('id_language', $user->getAttribute('id_language'));
        if ($user->getAttribute('id_database'))
            $this->setDBContextParameter('id_database', $user->getAttribute('id_database'));
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    protected function setDBContextParameter($var, $val)
    {
        try {
            $query = "select set_parameter(:name, :val);";
            $stmt = $this->context->getDb()->prepare($query);
            $stmt->bindValue('name', $var);
            $stmt->bindValue('val', $val);
            $stmt->execute();
        } catch (exception $e) {
        }
    }

    public function setTextError($name, $error)
    {
        $this->textErrors[$name] = $error;
    }

    public function getIsSend()
    {
        return $this->isSend;
    }

    public function setIsSend($value)
    {
        $this->isSend = (boolean)$value;
    }

    public function clear()
    {
        $this->values = null;
    }

    /**
     * Метод для добавления виджета
     */
    public function addWidget($widget)
    {
        $this->widgets[$widget->getName()] = $widget;
    }

    public function addJavascript($script)
    {
        $this->javascripts[] = $script;
    }

    public function addStylesheet($style)
    {
        $this->stylesheets[] = $style;
    }

    /**
     * Метод для добавления валидатора
     */
    public function addValidator($name, $validator)
    {
        $this->validators[$name] = $validator;
    }

    /**
     * Метод для получения валидатора
     */
    public function getValidator($name)
    {
        return $this->validators[$name];
    }

    public function setValue($name, $value)
    {
        return $this->values[$name] = $value;
    }

    /**
     * возвращает все проверенные значения, или значения только тех полей, которые переданы в $fields
     */
    public function getValues($fields = false)
    {
        if (is_array($fields)) {
            $values = array();
            foreach ($fields as $name) {
                $values[$name] = $this->values[$name];
            }
            return $values;
        } else {
            return $this->values;
        }
    }

    /**
     * Возвращает список ошибок в полях
     */
    public function getValueErrors() {
        $errors = array();

        foreach ($this->valueErrors as $key => $value){
            $reason = $value;

            switch ($value){
                case 'required':
                    $text = 'field_required';
                    break;
                case 'invalid':
                default:
                    $text = 'field_incorrect';
                    break;
            }

            $errors[$key] = array('error' => $reason, 'text' => Context::getInstance()->translate($text));
        }

        return $errors;
    }

    public function setValueError($name)
    {
        $this->errorValues[$name] = $this->values[$name];
        $this->valueErrors[$name] = 'invalid'; //$error;
        $this->getWidget($name)->setOption('has-error', true);
    }

    /**
     * Метод для получения виджета
     */
    public function getWidget($name)
    {
        return $this->widgets[$name];
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Устанавливает текст об ошибке
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getNotifyMessage()
    {
        return $this->notifyMessage;
    }

    /**
     * Устанавливает текст нотификации
     */
    public function setNotifyMessage($notifyMessage)
    {
        $this->notifyMessage = $notifyMessage;
    }

    public function bind($values)
    {
        $this->values = $values;
        foreach ($this->defaultValues as $name => $value) {
            if (!array_key_exists($name, $this->values)) {
                $this->values[$name] = $value;
            }
        }
        $this->isBined = true;
    }

    public function renderFormWidgetSimple($formName, $widgetName)
    {
        //$this->init();

        $name = $widgetName;
        $widget = $this->widgets[$name];
        //var_dump($widget->classes, 'yes'); exit;
        if ($widget->getLabel() !== false) {
            if ($this->getAttribute('readonly')) {
                $widget->setAttribute('readonly', 'readonly');
                $widget->setAttribute('disabled', 'disabled');
            }
            if (isset($this->valueErrors[$name])) {
                $widget->setOption('has-error', true);
                $widget->setOption('text-error', $this->textErrors[$name]);

                return $widget->renderForFormSimple($formName, $this->errorValues[$name]);
            } else {

                return $widget->renderForFormSimple($formName, $this->getValue($name));
            }
        }
    }

    //for site render

    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Возвращает проверенное значение формы
     */
    public function getValue($name, $default = null)
    {
        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    /**
     * отрисовывает форму
     */
    public function render($formName)
    {
        $this->init();

        $form = array(sprintf('<form %s>', implode(' ', $this->compileAttribute($this->getAttributes()))));
        if ($this->errorMessage != null) {
            $form[] = '<p class="text-danger">' . $this->errorMessage . '</p>';
        }
        $buttons = array();
        foreach ($this->widgets as $name => $widget) {
            if ($widget instanceof nomvcButtonWidget) {
                $buttons[] = $widget;
            } elseif ($widget->getLabel() !== false) {
                if ($this->getAttribute('readonly')) {
                    $widget->setAttribute('readonly', 'readonly');
                    $widget->setAttribute('disabled', 'disabled');
                }
                if (isset($this->valueErrors[$name])) {
                    $widget->setOption('has-error', true);
                    $form[] = $widget->renderForForm($formName, $this->errorValues[$name]);
                } else {
                    $form[] = $widget->renderForForm($formName, $this->getValue($name));
                }
            }
        }

        foreach ($this->javascripts as $javascript) {
            $form[] = $javascript;
        }

        foreach ($this->stylesheets as $stylesheet) {
            $form[] = $stylesheet;
        }

        if (count($buttons)) {
            foreach ($buttons as $name => $button) {
                $buttons[$name] = $button->renderControl(null);
            }
            $form[] = sprintf('<div id="form_group_buttons" class="form-group pull-right">%s</div>', implode(' ', $buttons));
        }

        $form[] = '</form>';
        return implode('', $form);
    }

    protected function compileAttribute($attributes)
    {
        $attributesCompiled = array();
        foreach ($attributes as $attr => $val) {
            if ($val === null) {
                $attributesCompiled[] = sprintf('%s', $attr);
            } elseif ($val !== false) {
                $attributesCompiled[] = sprintf('%s="%s"', $attr, $val);
            }
        }
        return $attributesCompiled;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Запускает процесс проверки формы
     */
    public function validate($data)
    {
        $values = array();
        $this->errorValues = array();
        $this->valueErrors = array();
        foreach ($this->validators as $name => $validator) {
            try {
                if (!isset($data[$name])) $data[$name] = null;
                $values[$name] = $validator->clean(isset($data[$name]) ? $data[$name] : null);
            } catch (nomvcInvalidValueException $ex) {
                $this->errorValues[$name] = $data[$name];
                $this->valueErrors[$name] = $ex->getReason();
                $this->textErrors[$name] = $ex->getErrorMessageText();
            }
        }
        $this->values = $values;
        return count($this->valueErrors) == 0;
    }

    public function setDefault($name, $value)
    {
        $validator = $this->validators[$name];
        try {
            $this->defaultValues[$name] = $validator->clean($value);
        } catch (nomvcInvalidValueException $ex) {
        }
    }

    public function getDefaults()
    {
        return $this->defaultValues;
    }

    public function setWidgetAttribute($nameWidget, $attrName, $attrVal)
    {
        $this->widgets[$nameWidget]->setAttribute($attrName, $attrVal);
    }

    protected function unsetWidget($name)
    {
        unset($this->widgets[$name]);
        unset($this->validators[$name]);
    }
}
