<?php

class agCreateActionTask extends agBaseTask {
	
	public function execute($options) {
		ob_start();
		include($this->context->getDir('template').'/action_base.tpl');
		$data = ob_get_contents();
		ob_end_clean();
	
		var_dump($data);
	}
	
	public function getCommand() {
		return 'createaction';
	}
	
	public function getDescription() {
		return 'create new action';
	}
	
	public function getOptions() {
		return array('action_name');
	}
	
}

?>
