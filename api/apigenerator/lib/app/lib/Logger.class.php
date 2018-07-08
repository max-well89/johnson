<?php

class Logger extends agLogger {

	const LOG_DIR = '/var/log/gate';
	
	private $module = null;
	private $action = null;
	private $input = null;
	private $output = null;
	
	protected function init() {
		
	}
	
	protected function log() {
		$message = sprintf("%s\t%s\t%s/%s\t%s\t%s\n",
			date('Y-m-d H:i:s'),
			$this->stop_time - $this->start_time,
			$this->module,
			$this->action,
			str_replace(array("\r", "\n"), array('', ''), $this->input ? serialize($this->input) : '-'),
			str_replace(array("\r", "\n"), array('', ''), $this->output ? serialize($this->output) : '-')
		);
		$filename = self::LOG_DIR.'/'.$this->context->getConfigVal('log_prefix','unprefix').'_main.log';
		file_put_contents($filename, $message, FILE_APPEND);
	}
	
	public function setController($module) {
		$this->module = $module;
	}
	
	public function setAction($action) {
		$this->action = $action;
	}
	
	public function setInput($input) {
		$this->input = $input;
	}
	
	public function setOutput($output) {
		$this->output = $output;
	}
		
}

?>
