<?php
/**
 * Фильтры списка объектов
 *
 * @author sefimov
 */
class ObjectFilterForm extends nomvcAbstractFilterForm{

	public function init() {
		parent::init();

		$this->addWidget(new nomvcInputDatePeriodPickerWidget("Дата создания", "dt_created"));
		$this->addValidator("dt_created", new nomvcDatePeriodValidator());

		$this->addWidget(new nomvcInputTextWidget("Название", "name"));
		$this->addValidator('name', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcSelectFromMultipleDbWidget("Категория", 'id_category', array("helper" => $this->context->getDbHelper(), "table" => "V_CATEGORY", "val" => "name", "key" => "id_category")));
		$this->addValidator('id_category', new nomvcValueInDbMultipleValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_CATEGORY", "key" => "id_category")));

		$this->addWidget(new nomvcSelectFromMultipleDbWidget("Тип", 'id_type', array("helper" => $this->context->getDbHelper(), "table" => "V_TYPE", "val" => "name", "key" => "id_type")));
		$this->addValidator('id_type', new nomvcValueInDbMultipleValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_TYPE", "key" => "id_type")));

		$this->addWidget(new nomvcSelectFromMultipleDbWidget("Город", 'id_city', array("helper" => $this->context->getDbHelper(), "table" => "V_CITY", "val" => "name", "key" => "id_city")));
		$this->addValidator('id_city', new nomvcValueInDbMultipleValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_CITY", "key" => "id_city")));

		$this->addWidget(new nomvcInputTextWidget("Адрес", "address"));
		$this->addValidator('address', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcInputTextWidget("Почта", "email_list"));
		$this->addValidator('email_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcInputTextWidget("Телефон", "msisdn_list"));
		$this->addValidator('msisdn_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcInputTextWidget("WEB", "url_list"));
		$this->addValidator('url_list', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 200)));

		$this->addWidget(new nomvcSelectFromMultipleDbWidget("Автор", 'id_author', array("helper" => $this->context->getDbHelper(), "table" => "V_MEMBER", "val" => "name", "key" => "id_member")));
		$this->addValidator('id_author', new nomvcValueInDbMultipleValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_MEMBER", "key" => "id_member")));

		$this->addWidget(new nomvcSelectFromDbWidget('Статус', 'id_status', array("helper" => $this->context->getDbHelper(), "table" => "V_STATUS", "key" => "id_status")));
		$this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => false, "helper" => $this->context->getDbHelper(), "table" => "V_STATUS", "key" => "id_status")));


		$this->addButton('search');
		$this->addButton('reset');

		$this->addWidget(new nomvcButtonWidget('Создать объект', 'create', array(
		    'type' => 'button',
		    'icon' => 'file'
		), array(
		    'onclick' => "return TableFormActions.getForm('object');",
		    'class' => 'btn btn-success'
		)));


//		$this->addContextMap('date', 'date');

//		$this->setDefault('is_valid', 1);
	}

}
