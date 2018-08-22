<?php
    session_name('admin-panel');
    set_time_limit(0);

    define('NOMVC_APPNAME', 'admin');
    define('NOMVC_BASEDIR', dirname(dirname(__FILE__)));
    
    ini_set('session.cookie_lifetime', 86400);
    ini_set('session.gc_maxlifetime', 86400);
    
    session_id();
    
    require_once(dirname(__DIR__).'/lib/autoload.php');

    try {
        $context = new Context(Context::ENV_PROD);
        echo $context->getRootController()->run();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
