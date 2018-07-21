<?php

	define('API_GENERATOR_DIR', '/opt/www/citigate/web/apigenerator');
	require_once(dirname(__DIR__).'/lib/autoload.php');

	try {

		$context = new ApiContext(agContext::ENV_DEBUG);
		$context->setUser(new agConfigUser($context));
		$controller = new JsonApiController($context);
		$context->setController($controller);
		$output = $controller->exec();
		$context->getLogger()->setOutput($output);
		echo $output;
	
	} catch (Exception $ex) {
		echo $ex->getMessage();
	}
	
?>
