<?php

	$reflection = new ReflectionClass('Errors');
	$errors = $reflection->getConstants();
	$errorsCode = array_flip($errors);
	$errorsActions = array_combine(array_keys($errorsCode), array_fill(0, count($errorsCode), array()));
	
	foreach ($this->getActionsList() as $cmd => $title) {
		$actionClass = self::toCamelCase('_'.$cmd).'Action';
		$action = new $actionClass($this->context);
		foreach ($action->getExceptions() as $code => $message) {
			$errorsActions[$code][$cmd] = $message;
		}
	}

	echo '<?php'
?>


/**
 * Autogenerated class
 * IS API project: "<?php echo $this->context->getProjectName(); ?>"
 * date/time: <?php echo date('Y-m-d H:i:s'); ?>
 
 * API URL: <?php echo $this->context->getApiUrl(); ?>
 
 */

class ApiErrors {

<?php foreach($errors as $name => $code) : ?>
	/**<?php foreach($errorsActions[$code] as $cmd => $message) : ?>
	
	 * <?php echo "{$cmd} - $message"; endforeach; ?>

	 */
	<?php echo "const {$name}\t= {$code};"; ?>


<?php endforeach; ?>

}