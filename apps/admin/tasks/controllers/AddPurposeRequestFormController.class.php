<?php

class AddPurposeRequestFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "add-purpose-request";


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
        $form = new AddPurposeRequestForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Запрос на дополнительные баллы";

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

    private function checkStatus($values){
        $errors = [];

        if (isset($values['id_status'])) {
            $this->dbHelper->addQuery(get_class($this) . '/select-status', "
            select id_status
            from t_add_purpose_member_point 
            where id_add_member_point = :id_add_member_point
            ");
            if ($old_status = $this->dbHelper->selectValue(get_class($this) . '/select-status', array(
                ':id_add_member_point' => $values['id_add_member_point']
            ), PDO::FETCH_ASSOC)
            ) {
                if ($old_status == 1 && $values['id_status'] != $old_status)
                    $errors = array("id_status" => "invalid");
                elseif ($old_status == 2 && $values['id_status'] == 0)
                    $errors = array("id_status" => "invalid");
            }
        }

        return $errors;
    }

    /*
     * Сохранение формы, здесь вставка и редактирование
     */
    protected function processSaveForm() {
        $form = new AddPurposeRequestForm($this->context, array('id' => $this->formId));
        
        if ($form->validate($this->getFormData($this->formId))) {
            $values = $form->getValues();

            $values_base = array();
            foreach ($values as $key => $object_value) {
                if(!is_array($object_value))
                    $values_base[$key] = $object_value;
            }

            //проверка на статус
            if($errors = $this->checkStatus($values)){
                return json_encode(array(
                    'result' => 'error',
                    'fields' => $errors,
                    'message' => ''));
            }
            
            //Начинаем транзакцию
            //$this->dbHelper->beginTransaction();
            
            //вставляем данные, новый объект
            if(empty($values["id_add_member_point"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_add_purpose_member_point (
					  id_status
					)
					values (
                      :id_status
					)
					returning id_add_member_point into :id_object");
                $this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
                $values['id_add_member_point'] = $this->id_object;
            }
            //обновляем данные
            else{
                if (isset($values['id_status'])) {
                    $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_add_purpose_member_point
					set 
					id_status = :id_status
					where id_add_member_point = :id_add_member_point");
                    $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                }
                $this->id_object = $values["id_add_member_point"];
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

        //var_dump($this->id_object); exit;
        $this->dbHelper = $this->context->getDbHelper();
        $this->dbHelper->addQuery(get_class($this) . '/select-object', "
			select 
			id_add_member_point, 
			id_add_purpose, 
			id_member,
			learning_id,
			surname,
			id_position,
			id_city, 
			name,
			id_restaurant,
			dt,
			id_status
			from v_add_purpose_member_point
			where id_add_member_point = :id_object");
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