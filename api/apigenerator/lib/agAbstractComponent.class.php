<?php

/**
 *  Класс - родоначальник всех компонентов
 */
abstract class agAbstractComponent
{

    // ссылка на контекст
    protected $context;

    protected function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif (!empty($_SERVER['HTTP_X_REAL_IP'])){
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return ip2long($ip);
    }

    /** Конструктор */
    public function __construct($context)
    {
        $this->context = $context;
        $this->init();
    }

    /** Первичная инициализация компонента */
    protected abstract function init();

    protected function asStrictTypes($value, $types = array())
    {
        $new_value = false;

        if (is_array($value))
            foreach ($value as $key => $val) {
                if (isset($types[$key]) && $types[$key] == 'string'){
                    $new_value[$key] = (string) $val;
                }
                elseif (isset($types[$key]) && $types[$key] == 'int'){
                    $new_value[$key] = (int) $val;
                }
                elseif (isset($types[$key]) && $types[$key] == 'float'){
                    $new_value[$key] = (float) $val;
                }
                elseif (isset($types[$key]) && $types[$key] == 'boolean'){
                    $new_value[$key] = (boolean) $val;
                }
                else {
                    $new_value[$key] = $this->applyAutoType($val);
                }
            }

        return $new_value;
    }

    protected function asStrictType($value, $type = false)
    {
        if (isset($type) && $type == 'string'){
            $new_value = (string) $value;
        }
        elseif (isset($type) && $type == 'int'){
            $new_value = (int) $value;
        }
        elseif (isset($type) && $type == 'float'){
            $new_value = (float) $value;
        }
        elseif (isset($type) && $type == 'boolean'){
            $new_value = (boolean) $value;
        }
        else
            $new_value = $this->applyAutoType($value);

        return $new_value;
    }

    private function applyAutoType($value)
    {
        $new_value = null;

        if (is_numeric($value)) {
            if (is_float($value)) {
                $new_value = (float) $value;
            } else {
                $new_value = (int) $value;
            }
        }
        elseif (is_null($value)){
            $new_value = $value;
        }
        elseif (is_bool($value)){
            $new_value = (boolean) $value;
        }
        else {
            $new_value = (string) $value;
        }
        

        return $new_value;
    }
}
