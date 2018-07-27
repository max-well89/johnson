<?php
define('API_GENERATOR_DIR', dirname(__DIR__) . '/apigenerator');
require_once(dirname(__DIR__) . '/lib/autoload.php');

try {

    $context = new ApiGeneratorContext(agContext::ENV_DEBUG);
    $controller = new ApiGeneratorController($context);
    $context->setController($controller);
    echo $controller->exec();

} catch (Exception $ex) {
    echo $ex->getMessage();
    echo "<pre>";
    echo $ex->getTraceAsString();
    echo "</pre>";
}

?>
