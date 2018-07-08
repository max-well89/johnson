<?php
/**
 * Контроллер формы объектов
 *
 * @author sefimov
 */
class ObjectFormController extends AbstractFormController{
	/** @var int ID объекта */
	private $id_object = 0;
	/** @var array данные объекта */
	private $object;
	/** @var dbHelper экземпляр хелпера данных */
	private $dbHelper;
	/** @var string название формы */
	protected $formId = "object";


	protected function init() {
		$this->dbHelper = $this->context->getDbHelper();

		$this->id_object = $this->context->getRequest()->getParameter('id');
		$this->object = $this->getObject();
	}


	/*
	 * Открытие формы
	 */
	protected function processGetForm() {
		//вяжем данные
		$form = new ObjectForm($this->context, array('id' => $this->formId));
		$form->bind($this->object);

		$formTitle = "Объект";

		$buttons = array();
		$buttons[] = $this->getButton('save');
		$buttons[] = $this->getButton('delete-confirm', $this->id_object);
		$buttons[] = $this->getButton('cancel');

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/*
	 * Сохранение формы, здесь вставка и редактирование
	 */
	protected function processSaveForm() {
		$form = new ObjectForm($this->context, array('id' => $this->formId));

		if ($form->validate($this->getFormData($this->formId))) {
			$values = $form->getValues();

			$values_base = array();
			foreach ($values as $key => $object_value) {
				if(!is_array($object_value))
					$values_base[$key] = $object_value;
			}

			//Начинаем транзакцию
//			$this->dbHelper->beginTransaction();

			//вставляем данные, новый объект
			if(empty($values["id_object"])){
				$this->id_object = null;
				$this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_object (name, name_eng, id_type, longtitude, latitude, id_status, address, notes, description, discount, id_author, id_city)
					values (:name, :name_eng, :id_type, :longtitude, :latitude, :id_status, :address, :notes, :description, :discount, :id_author, :id_city)
						returning id_object into :id_object");
				$this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
				$values['id_object'] = $this->id_object;
			}
			//обновляем данные
			else{
				$this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_object
					set name = :name, name_eng = :name_eng, id_type = :id_type, longtitude = :longtitude, latitude = :latitude, id_status = :id_status,
						address = :address, notes = :notes, description = :description, discount = :discount, id_author = :id_author, id_city =:id_city
					where id_object = :id_object");
				$this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
				$this->id_object = $values["id_object"];
			}


			//обработка графика работы
			$this->setOpeningTimes($values["openings"]);

			//обработка телефонов
			$this->setPhones($values["phones"]);

			//обработка сайтов
			$this->setSites($values["sites"]);

			//обработка почт
			$this->setEmails($values["emails"]);

			//обработка тэгов
			$this->setTags($values["tags"]);

			//обработка фоток
			$this->setPhotos($values, "object", $this->dbHelper);

			//коммитим транзакцию
//			$this->dbHelper->commit();

			return json_encode(array('result' => 'success'));
		} else {
			//откатываем транзакцию
//			$this->dbHelper->rollback();

			return json_encode(array(
			    'result' => 'error',
			    'fields' => $form->getValueErrors(),
			    'message' => ''
			));
		}
	}

	/** Формируем объект для формы */
	private function getObject() {
		if (empty($this->id_object)) {
			$object["id_author"] = $this->context->getUser()->getUserID();
			return $object;
		}

		$this->dbHelper = $this->context->getDbHelper();
		$this->dbHelper->addQuery(get_class($this) . '/select-object', "
			select id_object, name, name_eng, id_type, longtitude, latitude, id_status, address, notes, description, discount, id_author, id_city from t_object
			where id_object = :id_object");
		$object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));

		//график работы
		$object["openings"] = $this->getOpeningTimes();
		//телефоны
		$object["phones"] = $this->getPhones();
		//мыла
		$object["emails"] = $this->getEmails();
		//Тэги
		$object["tags"] = $this->getTags();
		//сайты
		$object["sites"] = $this->getSites();
		//фотки
		$object["photos"] = $this->getPhotos($this->id_object, "object", $this->dbHelper);

		return $object;
	}

	/**
	 * Возвращает массив Графика работы
	 */
	private function getOpeningTimes() {
		$this->dbHelper->addQuery(get_class($this) . '/select-object-time', "
			select time_from, time_to, id_week_day from t_object_opening_time
			where id_object = :id_object");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-object-time', array("id_object" => $this->id_object));
		$openings = array();
		$time_from = $time_to = $i = 0;

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row = array_change_key_case($row);

			if($time_from != $row["time_from"] || $time_to != $row["time_to"]){
				$i++;
				$time_from = $row["time_from"];
				$time_to = $row["time_to"];
			}

			$openings[$i]["from_hour"] = floor($row["time_from"]);
			$openings[$i]["from_min"] = ($row["time_from"] - floor($row["time_from"])) * 60;
			$openings[$i]["to_hour"] = floor($row["time_to"]);
			$openings[$i]["to_min"] = ($row["time_to"] - floor($row["time_to"])) * 60;
			$openings[$i]["day"][$row["id_week_day"]] = 1;
		}
		return $openings;
	}

	/**
	 * Сохраняем массив графика работы
	 * @param array $openings Массив данных графика работы
	 */
	private function setOpeningTimes($openings) {
		$this->dbHelper->addQuery(get_class($this) . '/clear-object-time', "delete from t_object_opening_time where id_object = :id_object");
		$this->dbHelper->execute(get_class($this) . '/clear-object-time', array(":id_object" => $this->id_object));

		$this->dbHelper->addQuery(get_class($this) . '/insert-object-time', "insert into t_object_opening_time (id_object, time_from, time_to, id_week_day)
			values (:id_object, :time_from, :time_to, :id_week_day)");

		//финальный массив с данными для вставки
		$opening_time[":id_object"] = $this->id_object;
		foreach ($openings as $value) {
			//если пустая строка - следующий
			if (empty($value["day"])) continue;

			//сформируем массив
			foreach ($value as $key => $value_values) {
				$$key = $value_values;
			}
			$opening_time[":time_from"] = $from_hour + $from_min/60;
			$opening_time[":time_to"] = $to_hour + $to_min/60;

			foreach ($day as $key => $value) {
				$opening_time[":id_week_day"] = $key;
				$this->dbHelper->execute(get_class($this) . '/insert-object-time', $opening_time);
			}
		}

	}


	/**
	 * Возвращает массив телефонов объекта
	 */
	private function getPhones() {
		$this->dbHelper->addQuery(get_class($this) . '/select-object-msisdn', "
			select msisdn, notes, is_display from t_object_msisdn
			where id_object = :id_object");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-object-msisdn', array("id_object" => $this->id_object));
		$phones = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$phones[] = array_change_key_case($row);
		}

		return $phones;
	}

	/**
	 * Сохраняет массив телефонов
	 * @param array $phones Массив телефонов
	 */
	private function setPhones($phones) {
		$this->dbHelper->addQuery(get_class($this) . '/clear-object-msisdn', "delete from t_object_msisdn where id_object = :id_object");
		$this->dbHelper->execute(get_class($this) . '/clear-object-msisdn', array(":id_object" => $this->id_object));

		$this->dbHelper->addQuery(get_class($this) . '/insert-object-msisdn', "insert into t_object_msisdn (id_object, msisdn, notes, is_display)
			values (:id_object, :msisdn, :notes, :is_display)");

		foreach ($phones as $value) {
			//если пустой телефон, значит это пустая строка, пока
			if(empty($value["msisdn"])) continue;
			$value["id_object"] = $this->id_object;
			if(!array_key_exists("is_display", $value)) $value["is_display"] = 0;
			$this->dbHelper->execute(get_class($this) . '/insert-object-msisdn', $value);
		}
	}



	/**
	 * Возвращает массив сайтов объекта
	 */
	private function getSites() {
		$this->dbHelper->addQuery(get_class($this) . '/select-object-sites', "
			select id_object, site_url, is_display from t_object_web
			where id_object = :id_object");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-object-sites', array("id_object" => $this->id_object));
		$sites = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$sites[] = array_change_key_case($row);
		}
		return $sites;
	}

	/**
	 * Сохраняет массив сайтов объекта
	 * @param array $sites		Массив сайтов
	 */
	private function setSites($sites) {
		$this->dbHelper->addQuery(get_class($this) . '/clear-object-sites', "delete from t_object_web where id_object = :id_object");
		$this->dbHelper->execute(get_class($this) . '/clear-object-sites', array(":id_object" => $this->id_object));

		$this->dbHelper->addQuery(get_class($this) . '/insert-object-sites', "insert into t_object_web (id_object, site_url, is_display)
			values (:id_object, :site_url, :is_display)");

		foreach ($sites as $value) {
			//если пустой телефон, значит это пустая строка, пока
			if (empty($value["site_url"])) continue;
			$value["id_object"] = $this->id_object;
			if (!array_key_exists("is_display", $value)) $value["is_display"] = 0;
			$this->dbHelper->execute(get_class($this) . '/insert-object-sites', $value);
		}
	}


	/**
	 * Возвращает массив почт объекта
	 */
	private function getEmails() {
		$this->dbHelper->addQuery(get_class($this) . '/select-object-emails', "
			select id_object, email, is_display from t_object_email
			where id_object = :id_object");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-object-emails', array("id_object" => $this->id_object));
		$emails = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$emails[] = array_change_key_case($row);
		}
		return $emails;
	}

	/**
	 * Сохраняет массив почт объекта
	 * @param array $emails		Массив сайтов
	 */
	private function setEmails($emails) {
		$this->dbHelper->addQuery(get_class($this) . '/clear-object-emails', "delete from t_object_email where id_object = :id_object");
		$this->dbHelper->execute(get_class($this) . '/clear-object-emails', array(":id_object" => $this->id_object));

		$this->dbHelper->addQuery(get_class($this) . '/insert-object-emails', "insert into t_object_email (id_object, email, is_display)
			values (:id_object, :email, :is_display)");

		foreach ($emails as $value) {
			//если пустой телефон, значит это пустая строка, пока
			if (empty($value["email"])) continue;
			$value["id_object"] = $this->id_object;
			if (!array_key_exists("is_display", $value)) $value["is_display"] = 0;
			$this->dbHelper->execute(get_class($this) . '/insert-object-emails', $value);
		}
	}


	/**
	 * Возвращает массив тэгов объекта
	 */
	private function getTags() {
		$this->dbHelper->addQuery(get_class($this) . '/select-object-tags', "
			select id_tag from t_object_tag
			where id_object = :id_object");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-object-tags', array("id_object" => $this->id_object));
		$tags = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$row = array_change_key_case($row);
			$tags[] = $row["id_tag"];
		}
		return $tags;
	}

	/**
	 * Сохраняет массив тэгов объекта
	 * @param array $tags Массив тэгов
	 */
	private function setTags($tags) {
		$this->dbHelper->addQuery(get_class($this) . '/clear-object-tags', "delete from t_object_tag where id_object = :id_object");
		$this->dbHelper->execute(get_class($this) . '/clear-object-tags', array(":id_object" => $this->id_object));

		$this->dbHelper->addQuery(get_class($this) . '/insert-object-tags', "insert into t_object_tag (id_object, id_tag)
			values (:id_object, :id_tag)");

		foreach ($tags as $value) {
			$value = array("id_object" => $this->id_object, "id_tag" => $value);
			$this->dbHelper->execute(get_class($this) . '/insert-object-tags', $value);
		}
	}

	/**
	 * Подтверждение удаления
	 *
	 */
	protected function processDeleteConfirmForm() {
		$buttons = array();
		$buttons[] = $this->getButton('delete');
		$buttons[] = $this->getButton('cancel');

		//вяжем данные
		$form = new ObjectsDeleteConfirmForm($this->context, array('id' => $this->formId));
		$form->bind($this->object);

		$formTitle = 'Вы действительно хотите удалить объект';

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}


	/** удаление объекта */
	protected function processDeleteForm() {
		$values_keys = $this->context->getRequest()->getParameter('formkey', array());

		if (isset($values_keys["id_object"])) {
			$this->dbHelper->addQuery(get_class($this) . '/delete-object', "delete from t_object where id_object = :id_object");
			$this->dbHelper->execute(get_class($this) . '/delete-object', array('id_object' => $values_keys["id_object"]));
			return json_encode(array('result' => 'success'));
		}
		else{
			return json_encode(array(
			    'result' => 'error',
			    'fields' => array("id_object" => "required"),
			    'message' => ''
			));
		}


	}



}