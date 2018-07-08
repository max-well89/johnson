<?php

class agApiInitTask extends agBaseTask {
	
	public function execute($options) {
		// create directory
		
		if (preg_match('/^\/.*/', $options['directory'])) {
			$dir = $options['directory'];
		} else {
			$dir = getcwd().'/'.$options['directory'];
		}
		if (!file_exists($dir)) {
			echo sprintf("create directory %s\n", $dir);
			mkdir($dir, 0775, true);
		} else {
			echo sprintf("directory %s exist\n", $dir);
		}
		// copy files
		$this->copyFiles($this->context->getDir('skeleton'), $dir);
	}
	
	public function copyFiles($source, $target) {
		foreach (scandir($source) as $file) {
			if (!($file == '.' || $file == '..' || $file == '.svn')) {
				if (is_dir($source.'/'.$file)) {
					if (!file_exists($target.'/'.$file)) {
						echo sprintf("create directory %s\n", $target.'/'.$file);
						mkdir($target.'/'.$file, 0775, true);
					}
					echo sprintf("copy files from %s to %s\n", $source.'/'.$file, $target.'/'.$file);
					$this->copyFiles($source.'/'.$file, $target.'/'.$file);
				} else {
					echo sprintf("create file %s\n", $target.'/'.$file);
					copy($source.'/'.$file, $target.'/'.$file);
				}
			}
		}
	}
	
	public function getCommand() {
		return 'apiinit';
	}
	
	public function getDescription() {
		return 'initapi';
	}
	
	public function getOptions() {
		return array('apiname', 'directory');
	}
	
}

?>
