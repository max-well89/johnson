<?php
/**
 * Маршруты. Контроллер.
 */
class RoutesFormController extends AbstractFormController {
	/** @var int ID объекта */
	private $id_route = 0;
	/** @var array данные объекта */
	private $route;
	/** @var dbHelper экземпляр хелпера данных */
	private $dbHelper;
	/** @var string название формы */
	protected $formId = "route";


	protected function init() {
		$this->dbHelper = $this->context->getDbHelper();
		$this->id_route = $this->context->getRequest()->getParameter('id');
	}

	/** Открытие формы */
	protected function processGetForm() {
		$buttons = array();
		$buttons[] = $this->getButton('save');
		$buttons[] = $this->getButton('delete-confirm', $this->id_route);
		$buttons[] = $this->getButton('cancel');

		$this->route = $this->getObject();

		$form = new RoutesForm($this->context, array('id' => $this->formId));
		$form->bind($this->route);
		$formTitle = 'Маршрут';

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/** Сохранение формы, здесь вставка и редактирование */
	protected function processSaveForm() {
		$form = new RoutesForm($this->context, array('id' => $this->formId));

		if ($form->validate($this->getFormData($this->formId))) {
			$values = $form->getValues();
			$values_prepared = array();
			foreach ($values as $key => $value) {
				if(!is_array($value))
					$values_prepared[$key] = $value;
			}

			//вставка нового маршрута
			if(empty($values["id_route"])){
				//данные для вставки, новая новость
				$this->dbHelper->addQuery(get_class($this) . '/insert-route', "
					insert into t_route(name, name_eng, is_display, is_edited, id_author)
					values(:name, :name_eng, :is_display, :is_edited, :id_author)
						returning id_route into :id_route");
				$this->dbHelper->execute(get_class($this) . '/insert-route', $values_prepared, array(':id_route' => &$this->id_route));
				$values['id_route'] = $this->id_route;
			//обновление существующего маршрута
			} else{
				$this->dbHelper->addQuery(get_class($this) . '/update-route', "
					update t_route
					set name = :name, name_eng = :name_eng, is_display = :is_display, is_edited = :is_edited, id_author = :id_author
					where id_route = :id_route");
				$this->dbHelper->execute(get_class($this) . '/update-route', $values_prepared);
				$this->id_route = $values["id_route"];
			}

			$this->setRouteObject($values["objects"]);

			$this->setPhotos($values, "route", $this->dbHelper);

			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => $form->getValueErrors(),
			    'message' => ''
			));
		}
	}

 	/** Маршруты. Формируем объект данных для привязки и валидации в форму */
	protected function getObject() {
		if (empty($this->id_route)) {
			$routes["id_author"] = $this->context->getUser()->getUserID();
			return $routes;
		}

		$this->dbHelper->addQuery(get_class($this) . '/select-route', "select id_route, name, name_eng, is_display, is_edited, id_author from t_route where id_route = :id_route");
		$routes = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-route', array(':id_route' => $this->id_route), PDO::FETCH_ASSOC));

		$routes["objects"] = $this->getRouteObject();

		$routes["photos"] = $this->getPhotos($this->id_route, "route", $this->dbHelper);

		return $routes;
	}

	/** Возвращает объекты маршрута */
	private function getRouteObject() {
		$this->dbHelper->addQuery(get_class($this) . '/select-route-object', "select id_object from t_route_object where id_route = :id_route");
		$stmt = $this->dbHelper->select(get_class($this) . '/select-route-object', array(':id_route' => $this->id_route));

		$objects = array();
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$objects[] = $row[0];
		}
		return $objects;
	}

	/**
	 * Сохраняет объекты маршрута
	 * @param array $objects массив объектов маршрута
	 */
	private function setRouteObject($objects) {
		$this->dbHelper->addQuery(get_class($this) . '/delete-route-object', "delete from t_route_object where id_route = :id_route");
		$this->dbHelper->execute(get_class($this) . '/delete-route-object', array(':id_route' => $this->id_route));

		$this->dbHelper->addQuery(get_class($this) . '/insert-route-object', "insert into t_route_object(id_route, id_object) values(:id_route, :id_object)");

		foreach ($objects as $id_object) {
			$this->dbHelper->execute(get_class($this) . '/insert-route-object', array(':id_route' => $this->id_route, ":id_object" => $id_object));
		}
	}


	/** Подтверждение удаления, открываем форму */
	protected function processDeleteConfirmForm() {
		$buttons = array();
		$buttons[] = $this->getButton('delete');
		$buttons[] = $this->getButton('cancel');

		$this->route = $this->getObject();

		//вяжем данные
		$form = new RoutesDeleteConfirmForm($this->context, array('id' => $this->formId));
		$form->bind($this->route);

		$formTitle = 'Вы действительно хотите удалить маршрут?';

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/** Удаление тэга */
	protected function processDeleteForm() {
		$this->dbHelper = $this->context->getDbHelper();
		$values_keys = $this->context->getRequest()->getParameter('formkey', array());

		if (isset($values_keys["id_route"])) {
			$this->dbHelper->addQuery(get_class($this) . '/delete-route', "delete from t_route where id_route = :id_route");
			$this->dbHelper->execute(get_class($this) . '/delete-route', array('id_route' => $values_keys["id_route"]));
			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => array("id_route" => "required"),
			    'message' => ''
			));
		}
	}




}
