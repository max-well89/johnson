<?php

/**
 * Description of ActionsForm
 *
 * @author sefimov
 */
class ObjectForm extends nomvcAbstractForm {

	public function init() {
		parent::init();

		//ID_OBJECT
		$this->addWidget(new nomvcInputHiddenWidget('id_object', 'id_object'));
		$this->addValidator('id_object', new nomvcIntegerValidator(array('required' => FALSE)));


		//автор
		$this->addWidget(new nomvcInputHiddenWidget('id_author', 'id_author'));
		$this->addValidator('id_author', new nomvcIntegerValidator(array('required' => true)));


		//Фотографии
		$this->addWidget(new nomvcImageMultiFileWidget('Фотографии', 'photos',array("show-splash" => TRUE)));
		$this->addValidator('photos', new nomvcImageMultiFileValidator(array('required' => false)));


		//Название
		$this->addWidget(new nomvcInputTextWidget('Название', 'name'));
		$this->addValidator('name', new nomvcStringValidator(array('required' => true, 'min' => 2, 'max' => 100)));


		//Название на английском
		$this->addWidget(new nomvcInputTextWidget('Object name', 'name_eng'));
		$this->addValidator('name_eng', new nomvcStringValidator(array('required' => true, 'min' => 2, 'max' => 100)));

		//Описание
		$this->addWidget(new nomvcTextareaWidget("Описание", "description"), array(), array("maxlength" => 500));
		$this->addValidator('description', new nomvcStringValidator(array('required' => false, 'min' => 2, 'max' => 480)));

		//Тип объекта
		$this->addWidget(new nomvcSelectFromDbWidget('Тип', 'id_type', array(
		    'helper' => $this->context->getDbHelper(),
		    'table' => 'V_TYPE',
		)));
		$this->addValidator('id_type', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_TYPE', 'key' => 'id_type')));


		//Тэги
		$this->addWidget(new nomvcSelectFromMultipleDbWidget('Тэги', 'tags', array(
			'helper' => $this->context->getDbHelper(), 'table' => 'V_TAG', 'key' => 'id_tag'), array()));
		$this->addValidator('tags', new nomvcArrayValidator(array('required' => false)));


		//Широта
		$this->addWidget(new nomvcInputTextWidget('Широта', 'latitude'));
		$this->addValidator('latitude', new nomvcNumberValidator(array('required' => true)));

		//Долгота
		$this->addWidget(new nomvcInputTextWidget('Долгота', 'longtitude'));
		$this->addValidator('longtitude', new nomvcNumberValidator(array('required' => true)));


		//Город расположения
		$this->addWidget(new nomvcSelectFromDbWidget('Город', 'id_city', array(
		    'helper' => $this->context->getDbHelper(),
		    'table' => 'V_CITY',
		)));
		$this->addValidator('id_city', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_CITY', 'key' => 'id_city')));


		//Адрес
		$this->addWidget(new nomvcTextareaWidget("Адрес", "address"), array(), array("maxlength" => 500));
		$this->addValidator('address', new nomvcStringValidator(array('required' => false, 'min' => 5, 'max' => 480)));


		//График работы
		$this->addWidget(new nomvcOpeningTimeWidget("График работы", "openings", array(
		    'helper' => $this->context->getDbHelper(),
		    'label-width' => 4,
		    'collapse' => true), array()));
		$this->addValidator('openings', new nomvcArrayValidator(array('required' => false, 'min' => 0)));


		//Телефон
		$this->addWidget(new nomvcInputListWidget("Телефоны", "phones", array(
			'helper' => $this->context->getDbHelper(),
			'settings' => array(
			    'fields' => array(
				"Телефон" => array("name" => "msisdn", "type" => "input"),
				"Пометки" => array("name" => "notes", "type" => "input"),
				"Показывать?" => array("name" => "is_display", "type" => "checkbox")
			    )
			),
			'label-width' => 4,
			'collapse' => true), array()));
		$this->addValidator('phones', new nomvcArrayValidator(array('required' => false, 'min' => 0)));


		//Сайты
		$this->addWidget(new nomvcInputListWidget("Сайты", "sites", array(
			'helper' => $this->context->getDbHelper(),
			'settings' => array(
			    'fields' => array(
				"Сайт" => array("name" => "site_url", "type" => "input"),
				"Показывать?" => array("name" => "is_display", "type" => "checkbox")
			    )
			),
			'label-width' => 4,
			'collapse' => true), array()));
		$this->addValidator('sites', new nomvcArrayValidator(array('required' => false, 'min' => 0)));


		//Мыла
		$this->addWidget(new nomvcInputListWidget("Почты", "emails", array(
			'helper' => $this->context->getDbHelper(),
			'settings' => array(
			    'fields' => array(
				"Почта" => array("name" => "email", "type" => "input"),
				"Показывать?" => array("name" => "is_display", "type" => "checkbox")
			    )
			),
			'label-width' => 4,
			'collapse' => true), array()));
		$this->addValidator('emails', new nomvcArrayValidator(array('required' => false, 'min' => 0)));


		//Скидка
		$this->addWidget(new nomvcInputTextWidget('Скидка', 'discount'));
		$this->addValidator('discount', new nomvcNumberValidator(array("required" => false, "min" => 0, "max" => 100)));


		//Внутренний комментарий
		$this->addWidget(new nomvcTextareaWidget("Внутренний комментарий", "notes"), array(), array("maxlength" => 500));
		$this->addValidator('notes', new nomvcStringValidator(array('required' => false, 'min' => 5, 'max' => 480)));

		//Статус
		$this->addWidget(new nomvcSelectFromDbWidget('Статус', 'id_status', array(
		    'helper' => $this->context->getDbHelper(),
		    'table' => 'V_STATUS',
		)));
		$this->addValidator('id_status', new nomvcValueInDbValidator(array('required' => true, 'helper' => $this->context->getDbHelper(), 'table' => 'V_STATUS', 'key' => 'id_status')));
	}

}
