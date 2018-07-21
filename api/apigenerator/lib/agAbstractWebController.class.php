<?php

/**
 * Абстрактный контроллер для WEB - приложений 
 */
abstract class agAbstractWebController extends agAbstractController {
	
	/** выполняет указанный шаблон */
	protected function processTemplate($template) {
		ob_start();
		include($this->context->getDir('template').'/'.$template.'.php');
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}
	
	/** возвращает пример запроса текущей команды */
	public function getRequestExample() {
		$printer = new JsonPrettyPrinter();
		return $printer->format(json_encode($this->action->getRequestExample()));
	}
	
	/** возвращает пример ответа текущей команды */
	public function getResponseExample() {
		$printer = new JsonPrettyPrinter();
		return $printer->format(json_encode($this->action->getResponseExample()));
	}

}
