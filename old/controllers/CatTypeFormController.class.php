<?php
/**
 * Категории и типы. Контроллер.
 */
class CatTypeFormController extends AbstractFormController {
	/** @var int ID объекта */
	private $id_tag = 0;
	/** @var array данные объекта */
	private $tag;
	/** @var dbHelper экземпляр хелпера данных */
	private $dbHelper;
	/** @var string название формы */
	protected $formId = "cattype";

	/** @const int значение типа для тэга  */
	const ID_TYPE_FOR_TAG = 3;


	protected function init() {
		$this->dbHelper = $this->context->getDbHelper();
		$this->id_tag = $this->context->getRequest()->getParameter('id');
	}

	/** Открытие формы */
	protected function processGetForm() {
		$buttons = array();
		$buttons[] = $this->getButton('save');
		$buttons[] = $this->getButton('delete-confirm', $this->id_tag);
		$buttons[] = $this->getButton('cancel');

		$this->tag = $this->getObject();

		$form = new TagForm($this->context, array('id' => $this->formId));
		$form->bind($this->tag);
		$formTitle = 'Тэг';

		return json_encode(array(
		    'title' => $formTitle,
		    'form' => $form->render($this->formId),
		    'buttons' => implode('', $buttons)
		));
	}

	/** Сохранение формы, здесь вставка и редактирование */
	protected function processSaveForm() {
		$form = new TagForm($this->context, array('id' => $this->formId));

		if ($form->validate($this->getFormData($this->formId))) {
			$values = $form->getValues();

			//если не указано обратное - это  Тэг
			$values["id_types_type"] = empty($values["id_types_type"])? self::ID_TYPE_FOR_TAG : $values["id_types_type"];

			//вставка нового тэга
			if(empty($values["id_tag"])){
				//данные для вставки, новая новость
				$this->dbHelper->addQuery(get_class($this) . '/insert-tag', "
					insert into t_type_classifier (name, name_eng, order_by_type, id_types_type, is_display, is_edited)
					values (:name, :name_eng, :order_by_type, :id_types_type, :is_display, 1)
						returning id_type_classifier into :id_tag");
				$this->dbHelper->execute(get_class($this) . '/insert-tag', $values, array(':id_tag' => &$this->id_tag));
				$values['id_tag'] = $this->id_tag;
			//обновление существующего тэга
			} else{
				//редактируем данные в таблице T_NEW
				$this->dbHelper->addQuery(get_class($this) . '/update-tag', "
					update t_type_classifier
					set name = :name, name_eng = :name_eng, order_by_type = :order_by_type,
						id_types_type = :id_types_type, is_display = :is_display
					where id_type_classifier = :id_tag");
				$this->dbHelper->execute(get_class($this) . '/update-tag', $values);
			}

			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => $form->getValueErrors(),
			    'message' => ''
			));
		}
	}

 	/** Тэги. Формируем объект данных для привязки и валидации в форму */
	protected function getObject() {
		if (empty($this->id_tag)) {
			return array();
		}

		$this->dbHelper->addQuery(get_class($this) . '/select-tag', "select id_tag, name, name_eng, is_display, order_by_type from v_tag_list where id_tag = :id_tag");
		$tags = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-tag', array(':id_tag' => $this->id_tag), PDO::FETCH_ASSOC));

		return $tags;
	}

	/** Подтверждение удаления, открываем форму */
	protected function processDeleteConfirmForm() {
		$buttons = array();
		$buttons[] = $this->getButton('delete');
		$buttons[] = $this->getButton('cancel');

		$this->tag = $this->getObject();

		//вяжем данные
		$form = new TagDeleteConfirmForm($this->context, array('id' => $this->formId));
		$form->bind($this->tag);

		$formTitle = 'Вы действительно хотите удалить тэг';

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

		if (isset($values_keys["id_tag"])) {
			$this->dbHelper->addQuery(get_class($this) . '/delete-tag', "delete from t_type_classifier where id_type_classifier = :id_tag");
			$this->dbHelper->execute(get_class($this) . '/delete-tag', array('id_tag' => $values_keys["id_tag"]));
			return json_encode(array('result' => 'success'));
		} else {
			return json_encode(array(
			    'result' => 'error',
			    'fields' => array("id_tag" => "required"),
			    'message' => ''
			));
		}
	}
}
