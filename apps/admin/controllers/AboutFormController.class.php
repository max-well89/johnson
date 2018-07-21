<?php

class AboutFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "about";


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
        $form = new AboutForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "О проекте";

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
        $form = new AboutForm($this->context, array('id' => $this->formId));

        if ($form->validate($this->getFormData($this->formId))) {
            $values = $form->getValues();

            $values_base = array();
            foreach ($values as $key => $object_value) {
                if(!is_array($object_value))
                    $values_base[$key] = $object_value;
            }

            //Начинаем транзакцию
            //$this->dbHelper->beginTransaction();

            //var_dump($values_base); exit;

            //вставляем данные, новый объект
            if(empty($values["id_about"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_about (
					name,
					short_description,
					description,
					id_status,
					dt_public
					)
					values (
                    :name,
                    :short_description,
					empty_blob(),
					:id_status,
					decode(:id_status, 1, sysdate, null)
					)
					returning id_about, description into :id_object, :description");
                $this->dbHelper->execute(get_class($this) . '/insert-object',
                    array(
                        'name' => $values_base['name'],
                        'short_description' => $values_base['short_description'],
                        //'dt_public' => $values_base['dt_public'],
                        'id_status' => $values_base['id_status']
                    ),
                    array(
                        'id_object' => &$this->id_object
                    ),
                    array(
                        'description' =>fopen('data://text/plain;base64,' . base64_encode($values_base['description']),'r')
                    )
                );
                $values['id_about'] = $this->id_object;
            }
            //обновляем данные
            else{
                $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_about
					set 
					name = :name,
					short_description = :short_description,
					description = empty_blob(),
					id_status = :id_status,
					dt_public = decode(:id_status, 1, sysdate, null)
					where id_about = :id_about
					returning description into :description");
                $this->dbHelper->execute(get_class($this) . '/update-object',
                    array(
                        'id_about' => $values_base['id_about'],
                        'short_description' => $values_base['short_description'],
                        'name' => $values_base['name'],
                        //'dt_public' => $values_base['dt_public'],
                        'id_status' => $values_base['id_status']
                    ),
                    array(),
                    array(
                        'description' =>fopen('data://text/plain;base64,' . base64_encode($values_base['description']),'r')
                    )
                );
                $this->id_object = $values["id_about"];
            }

            //обработка фоток
            $this->setPhotos($values, "about", $this->dbHelper);

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
			id_about, 
			name,
			short_description,
			description,
			id_status
			from t_about
			where id_about = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));

        $object['description'] = $object['description']?stream_get_contents($object['description']):null;

        $object["photos"] = $this->getPhotos($this->id_object, "about", $this->dbHelper);

        return $object;
    }
}