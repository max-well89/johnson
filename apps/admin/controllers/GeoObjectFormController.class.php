<?php
/**
 * Тэг. Контроллер.
 */
class GeoObjectFormController extends AbstractFormController {
	/** @var int ID объекта */
	private $id_geo_object = 0;
	/** @var array данные объекта */
	private $geo_object;
	/** @var dbHelper экземпляр хелпера данных */
	private $dbHelper;
	/** @var string Тип, с которым мы выведем форму */
	private $form_type_array = array();
	/** @var string Текущий тип формы */
	private $form_type;

	/** @var string название формы */
	protected $formId = "geoobject";

	protected function init() {
		$this->dbHelper = $this->context->getDbHelper();
		$this->id_geo_object = $this->context->getRequest()->getParameter('id');
		$this->getFormTypeArray();
	}

	/** Открытие формы */
	protected function processGetForm() {
		$buttons = array();
		$buttons[] = $this->getButton('save');
		$buttons[] = $this->getButton('delete-confirm', $this->id_geo_object);
		$buttons[] = $this->getButton('cancel');

		$this->geo_object = $this->getObject();

		$form = new GeoObjectForm($this->context, array("id" => $this->formId, "type" => $this->form_type));
		$form->bind($this->geo_object);
		$formTitle = $this->form_type_array[$this->form_type]["name_type"];

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/** Сохранение формы, здесь вставка и редактирование */
	protected function processSaveForm() {
		$values = $this->getFormData($this->formId);
		$this->setFormType($values["id_type"]);

		$form = new GeoObjectForm($this->context, array("id" => $this->formId, "type" => $this->form_type));

		if ($form->validate($values)) {
			//а теперь это отвалидированные значения
			$values = $form->getValues();

			foreach ($values as $key => $value) {
				if(!is_array($value))
					$values_prepared[$key] = $value;
			}

			if(empty($values["id_geo_object"])){
				$this->dbHelper->addQuery(get_class($this) . '/insert-geo-object', "
					insert into t_geo_object(name, name_eng, is_display, id_type, latitude, longitude)
					values(:name, :name_eng, :is_display, :id_type, :latitude, :longitude)
						returning id_geo_object into :id_geo_object");
				$this->dbHelper->execute(get_class($this) . '/insert-geo-object', $values_prepared, array(':id_geo_object' => &$this->id_geo_object));
				$values['id_geo_object'] = $this->id_geo_object;
			} else{
				$this->dbHelper->addQuery(get_class($this) . '/update-geo-object', "
					update t_geo_object
					set name = :name, name_eng = :name_eng, is_display = :is_display, id_type = :id_type, latitude = :latitude, longitude = :longitude
					where id_geo_object = :id_geo_object");
				$this->dbHelper->execute(get_class($this) . '/update-geo-object', $values_prepared);
				$this->id_geo_object = $values['id_geo_object'];
			}

			//сохранение подчинённых объектов
			$this->setGeoObjectSlave($values);

			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => $form->getValueErrors(),
			    'message' => ''
			));
		}
	}

 	/** Гео-объект. Формируем объект данных для привязки и валидации в форму */
	protected function getObject() {
		//если объект новый
		if (empty($this->id_geo_object)) {
			return array("id_type" => $this->form_type_array[$this->form_type]["id_type"]);
		}

		$this->dbHelper->addQuery(get_class($this) . '/select-geo-object', "select id_geo_object,name,name_eng,is_display,id_type,latitude,longitude
			from t_geo_object where id_geo_object = :id_geo_object");
		$geo_object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-geo-object', array(':id_geo_object' => $this->id_geo_object), PDO::FETCH_ASSOC));

		if($this->form_type == "geoobject"){
			$this->setFormType($geo_object["id_type"]);
		}

		$geo_object["geo_object_slaves"] = $this->getGeoObjectSlave($geo_object);

		return $geo_object;
	}

	/**
	 * Получение подчинённых объектов
	 * @param array $geo_object Гео-объект
	 */
	private function getGeoObjectSlave($geo_object) {
		foreach ($geo_object as $key => $value) {
			$$key = $value;
		}
		$geo_object_slave = array();
		//страна
		if($id_type == 1){
			$this->dbHelper->addQuery(get_class($this) . '/select-geo-object-slave', "
				select id_region id_geo_object_slave from t_country_region crc
				inner join t_geo_object go on crc.id_region = go.id_geo_object where id_country = :id_country order by go.name");
			$stmt = $this->dbHelper->select(get_class($this) . '/select-geo-object-slave', array(':id_country' => $this->id_geo_object));
		}
		//регион
		elseif ($id_type == 2) {
			$this->dbHelper->addQuery(get_class($this) . '/select-geo-object-slave', "
				select crc.id_city id_geo_object_slave from t_region_city crc
				inner join t_geo_object go on crc.id_city = go.id_geo_object where id_region = :id_region order by go.name");
			$stmt = $this->dbHelper->select(get_class($this) . '/select-geo-object-slave', array(':id_region' => $this->id_geo_object));
		}

		if($id_type == 1 || $id_type == 2){
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$row = array_change_key_case($row);
				$geo_object_slave[] = $row["id_geo_object_slave"];
			}
		}
		return $geo_object_slave;
	}

	/**
	 * Сохраняет подчинённые гео-объекты
	 * @param array $geo_object геообъект для которого мы сохраняем подчинённых
	 */
	private function setGeoObjectSlave($geo_object) {
		foreach ($geo_object as $key => $value) {
			$$key = $value;
		}
		//страна
		if ($id_type == 1) {
			//чистим
			$this->dbHelper->addQuery(get_class($this) . '/clear-geo-object-slave', "delete from t_country_region where id_country = :id_country");
			$this->dbHelper->execute(get_class($this) . '/clear-geo-object-slave', array(':id_country' => $this->id_geo_object));
			//добавляем
			$this->dbHelper->addQuery(get_class($this) . '/add-geo-object-slave', "insert into t_country_region(id_country, id_region) values(:id_country, :id_region)");
			foreach ($geo_object_slaves as $value) {
				$this->dbHelper->execute(get_class($this) . '/add-geo-object-slave', array(':id_country' => $this->id_geo_object, ":id_region" => $value));
			}
		}
		//регион
		elseif ($id_type == 2) {
			//чистим
			$this->dbHelper->addQuery(get_class($this) . '/clear-geo-object-slave', "delete from t_region_city where id_region = :id_region");
			$this->dbHelper->execute(get_class($this) . '/clear-geo-object-slave', array(':id_region' => $this->id_geo_object));
			//добавляем
			$this->dbHelper->addQuery(get_class($this) . '/add-geo-object-slave', "insert into t_region_city(id_region, id_city) values(:id_region, :id_city)");
			foreach ($geo_object_slaves as $value) {
				$this->dbHelper->execute(get_class($this) . '/add-geo-object-slave', array(':id_region' => $this->id_geo_object, ":id_city" => $value));
			}
		}
	}

	/** Подтверждение удаления, открываем форму */
	protected function processDeleteConfirmForm() {
		$buttons = array();
		$buttons[] = $this->getButton('delete');
		$buttons[] = $this->getButton('cancel');

		$this->geo_object = $this->getObject();

		//вяжем данные
		$form = new GeoObjectDeleteConfirmForm($this->context, array('id' => $this->formId));
		$form->bind($this->geo_object);

		$formTitle = 'Вы действительно хотите удалить Гео-объект';

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/** Удаление Гео-объекта */
	protected function processDeleteForm() {
		$this->dbHelper = $this->context->getDbHelper();
		$values_keys = $this->context->getRequest()->getParameter('formkey', array());

//		var_dump($values_keys["id_geo_object"]);
//		exit();

		if (isset($values_keys["id_geo_object"])) {
			$this->dbHelper->addQuery(get_class($this) . '/delete-geo-object', "delete from t_geo_object where id_geo_object = :id_geo_object");
			$this->dbHelper->execute(get_class($this) . '/delete-geo-object', array('id_geo_object' => $values_keys["id_geo_object"]));
			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => array("id_geo_object" => "required"),
			    'message' => ''
			));
		}
	}

	/**
	 * Функция устанавливает имя формы, предварительно сверившись, а есть ли она в разрешённых
	 * @param string $formID Строка, с которой стукнулись в бэкенд
	 */
	public function setFormId($formID) {
		$matches = array();
		if(preg_match("/^(\w+)\-.*/", $formID, $matches) !== 1)
			throw new nomvcGlobalException("Не верно указана форма!");

		if(!array_key_exists($matches[1], $this->form_type_array))
			throw new nomvcGlobalException("Стучимся куда то не туда!");

		$this->form_type = $matches[1];
	}


	/** Заполняет массив допустимых значений формы */
	private function getFormTypeArray() {
		$this->dbHelper->addQuery(get_class($this) . '/select-type', "select id_dic id_type, name name_type from t_dictionary where id_dic_type = 3");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-type');
		while ($type = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$type = array_change_key_case($type);
			switch ($type["id_type"]) {
				case 1:
					$this->form_type_array["country"] = $type;
					break;
				case 2:
					$this->form_type_array["region"] = $type;
					break;
				case 3:
					$this->form_type_array["city"] = $type;
					break;
			}
		}
		$this->form_type_array["geoobject"] = array("id_type" => 0, "name_type" => "универсальный");
	}

	/**
	 * Возвращает тип формы в обмен на ID типа формы
	 * @param integer $id_type ID типа формы
	 */
	private function setFormType($id_type) {
		foreach ($this->form_type_array as $key => $form_type) {
			if ($form_type["id_type"] == $id_type)
				$this->form_type = $key;
		}
	}
}
