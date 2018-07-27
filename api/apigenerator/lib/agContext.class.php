<?php

/**
 * Класс - описатель контекста выполнения приложения, предназначен для соединения отдельных
 * компонентов в одно целое. Реализован в виде синглтона.
 */
class agContext
{

    // константы режима работы среды выполнения
    const ENV_PROD = 'prod';
    const ENV_DEBUG = 'debug';

    // список директорий
    protected $directories = array();

    // конфиг среды окружения
    protected $config;

    /**
     * Создание контекста
     *
     * $env - среда окружения выбранная при создании
     */
    public function __construct($env = self::ENV_PROD)
    {
        $this->configureDirs();
        $this->configureContext($env);
    }

    /** конфигурация директорий **/
    protected function configureDirs()
    {
        $this->setDir('base', dirname(dirname(__FILE__)));
        $this->setDir('task', $this->getDir('base') . '/lib/task');
        $this->setDir('config', $this->getDir('base') . '/config');
        $this->setDir('template', $this->getDir('base') . '/template');
        $this->setDir('skeleton', $this->getDir('base') . '/skeleton');
    }

    /**
     * Добавляет запись в список длиректорий
     *
     * $name    название директории
     * $val        абсолютный путь
     */
    protected function setDir($name, $val)
    {
        $this->directories[$name] = $val;
    }

    /**
     * возвращает путь запрошенной директории или же значение по умолчанию
     * если указанная директория не найдена
     *
     * $name    код директории
     * $default    значение по умолчанию
     */
    public function getDir($name, $default = null)
    {
        if (isset($this->directories[$name])) {
            return $this->directories[$name];
        } else {
            return $default;
        }
    }

    /** конфигурация среды окружения */
    protected function configureContext($env)
    {
        $config = sfYaml::load($this->getDir('config') . '/context.yml');
        $config = array_merge($config[$env], $config['all']);
        foreach ($config['ini_set'] as $key => $val) {
            ini_set($key, $val);
        }
        $this->config = $config;
        return $config;
    }

    /** возвращает параметр из конфиг контекста */
    public function getConfigVal($name, $default = null)
    {
        return isset($this->config[$name]) && $this->config[$name] ? $this->config[$name] : $default;
    }

}
