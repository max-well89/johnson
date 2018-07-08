<?php
/**
 * Фильтры для таблицы Тэгов
 */
class RoutesFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();

//		$this->addWidget(new nomvcInputHiddenWidget(null, "id_map"));
//		$this->addValidator('id_map', new nomvcIntegerValidator());
//		$this->addContextMap('id_map', 'this_id_map');
//
//		//Период
//		$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата публикации", "dt_publish"));
//		$this->addValidator("dt_publish", new nomvcDatePeriodValidator());
//
//		//Статус публикации
//		$this->addWidget(new nomvcSelectFromDbWidget('Статус публикации', 'id_news_status', array("helper" => $this->context->getDbHelper(),
//		    "table" => "V_NEWS_STATUS")));
//		$this->addValidator('id_news_status', new nomvcValueInDbValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_NEWS_STATUS", "key" => "id_news_status")));


//		$this->addButton('search');
//		$this->addButton('reset');
//		$this->addButton('export');

		$this->addWidget(new nomvcButtonWidget('Создать маршрут', 'create', array(
		    'type' => 'button',
		    'icon' => 'file'
		), array(
		    'onclick' => "return TableFormActions.getForm('route');",
		    'class' => 'btn btn-success'
		)));

	}

}
