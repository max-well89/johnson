<?php

/**
 * Класс - основа для создания классов пользователей
 */
abstract class agAbstractUser extends agAbstractComponent
{

    // логин авторизованного пользователя
    protected $login = null;
    // роль пользователя
    protected $role = 'unauth';
    // аттрибуты пользователя
    protected $attributes = array();

    /** здесь должна быть реализована авторизация */
    public abstract function auth();

    /**
     * проверяет доступность конкретного экшена для пользователя
     *
     * $action - проверяемый экшен
     */
    public function hasAccessAction($action)
    {
        return in_array($this->role, $action->getAccessRoles());
    }

    /** возвращает логин пользователя **/
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Возвращает указанный атрибут, или значение по умолчанию при отсутствии указанного атрибута
     *
     * $name    название атрибута
     * $default    значение по умолчанию
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /** инициализация пользователя */
    protected function init()
    {
    }

}
