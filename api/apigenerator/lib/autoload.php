<?php

function __autoload($class)
{
    $class = preg_replace('/[^\w\d]/imu', '', $class);
    $basedir = dirname(dirname(__FILE__));
    $dirs = array(
        'lib',
        'lib/exceptions',
        'lib/task',
        'lib/task/lib',
        'lib/validators',
        'lib/extra/yaml',
        'lib/app/lib',
    );
    foreach ($dirs as $dir) {
        $files = array(
            "{$basedir}/{$dir}/{$class}.class.php",
            "{$basedir}/{$dir}/{$class}.php",
        );
        foreach ($files as $file) {
            if (file_exists($file)) {
                require_once($file);
                return true;
            }
        }
    }
    eval("class $class {}");
    throw new agClassNotFoundException($class);
}
