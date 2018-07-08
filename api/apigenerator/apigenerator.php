<?php

	try {
		require_once(dirname(__FILE__).'/lib/autoload.php');

		$context = new agContext(agContext::ENV_DEBUG);
		$task = new agTaskListTask($context);
		$task->parseCommandLine($argv);
		$task->executeCommand();
		
	} catch (Exception $ex) {
		echo sprintf("\n\x1b[1;37;41m\n\t%s\n\x1b[0m\n\n", $ex->getMessage());
	}
	
?>
