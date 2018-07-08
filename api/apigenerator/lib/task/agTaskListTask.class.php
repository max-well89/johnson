<?php

class agTaskListTask extends agBaseTask {
	
	private $tasks = array();
	
	public function __construct($context) {
		parent::__construct($context);
		$this->registerAllTask();;
	}
	
	private function registerAllTask() {
		$regexp = '/(^[\w\d]+Task)\.class\.php$/';
		$taskDir = $this->context->getDir('task');
		foreach (scandir($taskDir) as $file) {
			if (preg_match($regexp, $file, $m)) {
				list($filename, $classname) = $m;
				require_once($taskDir.'/'.$filename);				
				$reflection = new ReflectionClass($classname);
				if (!$reflection->isAbstract()) {
					if ($classname == get_class($this)) {
						$task = $this;
					} else {
						$task = new $classname($this->context);
					}
					$this->tasks[$task->getCommand()] = $task;
				}
			}
		}
	}
	
	public function parseCommandLine($argv) {
		$argc = count($argv);
		if ($argc > 1) {
			$command = $argv[1];
			if (!isset($this->tasks[$command])) {
				throw new agTaskNotFoundException($command);
			} else {
				$this->cmd = $command;
				$options = $this->tasks[$this->cmd]->getOptions();
				unset($argv[0], $argv[1]);				
				if (count($argv) != count($options)) {
					throw new agAttributeException(sprintf("invalid format\n\t\tuse: %s <%s>", $this->cmd, implode('> <', $options)));
				}
				$this->options = array_combine($options, $argv);
			}
		} else {
			$this->cmd = $this->command;
			$this->options = array();
		}
	}
	
	public function executeCommand() {
		if (isset($this->tasks[$this->cmd])) {
			$task = $this->tasks[$this->cmd];
			$task->execute($this->options);
		} else {
			$this->execute(array());
		}
	}
	
	public function execute($options) {
		echo "\n";
		foreach ($this->tasks as $key => $task) {
			echo sprintf("\x1b[1;34m%s\x1b[0m:\t\t%s\n", $key, $task->getDescription());
		}
		echo "\n";
	}
	
	public function getCommand() {
		return 'tasklist';
	}
	
	public function getDescription() {
		return 'get list of all task';
	}
	
	public function getOptions() {
		//$this->
	}

}

?>
