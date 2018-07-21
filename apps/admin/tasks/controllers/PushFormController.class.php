<?php

class PushFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "push";


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
        $form = new PushForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Пуш";

        $buttons = array();
        $buttons[] = $this->getButton('save');
        //$buttons[] = $this->getButton('delete-confirm', $this->id_object);
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
        $form = new PushForm($this->context, array('id' => $this->formId));

        if ($form->validate($this->getFormData($this->formId))) {
            $values = $form->getValues();

            $values_base = array();
            foreach ($values as $key => $object_value) {
                if(!is_array($object_value))
                    $values_base[$key] = $object_value;
            }

            //Начинаем транзакцию
            //$this->dbHelper->beginTransaction();

            if (!$values['dt_start'])
                $values_base['dt_start'] = null;

            //вставляем данные, новый объект
            if(empty($values["id_push"])){
                unset($values_base["id_push"]);
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_push (
                        dt,
                        message,
                        dt_start,
                        id_status,
                        id_os
					)
					values (
                        :dt,
                        :message,
                        :dt_start,
                        :id_status,
                        1
					)
					returning id_push");

                $values_base['dt'] = date('Y-m-d H:i:s', strtotime('now'));
                $this->id_object = $this->dbHelper->selectValue(get_class($this) . '/insert-object', $values_base, array());
                $values['id_push'] = $this->id_object;
            }
            //обновляем данные
            else{
                $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_push
					set 
					message = :message,
					dt_start = :dt_start,
					id_status = :id_status,
					id_os = 1
					where id_push = :id_push");
                $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                $this->id_object = $values["id_push"];
            }
            //коммитим транзакцию
            //$this->dbHelper->commit();

            return json_encode(array('result' => 'success'));
        } else {
            //откатываем транзакцию
            //$this->dbHelper->rollback();

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
			select *
			from t_push
			where id_push = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));


        return $object;
    }


    /**
     * Подтверждение удаления
     *
     */
    /*
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
*/

    /** удаление объекта */
    /*
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
    */
}