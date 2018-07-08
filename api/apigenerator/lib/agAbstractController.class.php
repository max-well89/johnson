<?php

/**
 * Класс - основа для всех контроллеров
 */
abstract class agAbstractController extends agAbstractComponent {

	/** инициализация контроллера */
	protected function init() {}

	/** этот метод вызывается для запуска контроллера */
	public abstract function exec();
	
	/**
	 * Возвращает список доступных экшенов (для документации и тестирования)
	 */
	public function getActionsList() {
		$regexp = '/(^[\w\d]+Action)\.class\.php$/';
		$actionsDir = $this->context->getDir('actions');
		$actions = array();
		$actionsFiles = scandir($actionsDir);
		if (is_array($actionsFiles)) {
			foreach ($actionsFiles as $file) {
				if (preg_match($regexp, $file, $m)) {
					list($filename, $classname) = $m;
					require_once($actionsDir.'/'.$filename);				
					$reflection = new ReflectionClass($classname);
					if (!$reflection->isAbstract()) {
						$action = new $classname($this->context);
						$actions[$action->getAction()] = $action->getTitle();
					}
				}
			}
		}
		return $actions;
	}
	
	/** преобразует строку из TestString в test_string */
	public static function fromCamelCase($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

	/** преобразует строку из test_string в TestString */
	public static function toCamelCase($str, $capitalise_first_char = false) {
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}

}
