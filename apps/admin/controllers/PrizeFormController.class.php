<?php

class PrizeFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "prize";


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
        $form = new PrizeForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Приз";

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
        $form = new PrizeForm($this->context, array('id' => $this->formId));

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
            if(empty($values["id_prize"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_prize (
                        name,
                        description,
                        price,
                        cnt_all,
                        id_prize_type,
                        id_status
					)
					values (
                        :name,
                        :description,
                        :price,
                        :cnt_all,
                        :id_prize_type,
                        :id_status
					)
					returning id_prize into :id_object");
                $this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
                $values['id_prize'] = $this->id_object;
            }
            //обновляем данные
            else{
                $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_prize
					set 
                        name = :name,
                        description = :description,
                        price = :price,
                        cnt_all = :cnt_all,
                        id_prize_type = :id_prize_type,
                        id_status = :id_status
					where id_prize = :id_prize");
                $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                $this->id_object = $values["id_prize"];
            }
            //коммитим транзакцию
            //$this->dbHelper->commit();

            //обработка фоток
            $this->setPhotos($values, "prize", $this->dbHelper);

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
			id_prize, 
			name, 
			description,
			price,
			cnt_all,
			id_prize_type,
			id_status
			from t_prize
			where id_prize = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));

        $object["photos"] = $this->getPhotos($this->id_object, "prize", $this->dbHelper);

        return $object;
    }
}