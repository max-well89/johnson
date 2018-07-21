<?php

class TaskPharmacyDetailFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "task-pharmacy-detail";

    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();

        $this->id_object = $this->context->getRequest()->getParameter('id');
        $this->object = $this->getObject();
    }

    public function run() {
        $request = $this->getCurrentUriPart();
        //var_dump($request); exit;

        switch ($request) {
            case 'get': return $this->processGetForm();    // получение формы
            case 'post': return $this->processSaveForm();   // сохранение формы
            case 'delete-confirm': return $this->processDeleteConfirmForm(); // подтверждение удаления формы
            case 'delete': return $this->processDeleteForm();   // удаление формы
            case 'add-parking-tag': return $this->addParkingTag();
            default: throw new nomvcPageNotFoundException('Page not found');
        }
    }

    /*
     * Открытие формы
     */
    protected function processGetForm() {
        //вяжем данные
        $form = new TaskPharmacyDetailForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Изменить цену";

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
        $form = new TaskPharmacyDetailForm($this->context, array('id' => $this->formId));

        $request_values = $this->getFormData($this->formId);
        if ($form->validate($request_values)) {
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
            if(empty($values["id_task_data"])){
                unset($values_base['id_task_data']);
                $this->id_object = null;

//                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
//					insert into t_task (
//                        name,
//                        dt_task
//					)
//					values (
//                        :name,
//                        :dt_task
//					)
//					returning id_task");
//                $this->id_object = $this->dbHelper->selectValue(get_class($this) . '/insert-object', $values_base, array());
//                $values['id_task'] = $this->id_object;
//
//                if ($this->id_object){
//                    $this->dbHelper->addQuery(get_class($this) . '/insert-object-rel', "
//					insert into t_task_member_pharmacy (
//                        id_task,
//                        id_member,
//                        id_pharmacy
//					)
//					select :id_task, id_member, id_pharmacy
//					from t_pharmacy
//					where t_pharmacy.id_status = 1
//					");
//                    $this->dbHelper->execute(get_class($this) . '/insert-object-rel', array('id_task' => $this->id_object));
//                }

            }
            //обновляем данные
            else{

                $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_task_data
					set 
					    value = :value,
					    rest_cnt = :rest_cnt,
					    illiquid_cnt = :illiquid_cnt,
					    is_action = :is_action,
					    comment = :comment
					where id_task_data = :id_task_data");
                $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                $this->id_object = $values["id_task_data"];

//                //добавляем недостающие
//                if ($this->id_object){
//                    $this->dbHelper->addQuery(get_class($this) . '/update-object-rel', "
//					insert into t_task_member_pharmacy (
//                        id_task,
//                        id_member,
//                        id_pharmacy
//					)
//					select :id_task, tp.id_member, tp.id_pharmacy
//					from t_pharmacy tp
//					left join t_task_member_pharmacy ttmp on ttmp.id_task = :id_task and ttmp.id_member = tp.id_member and ttmp.id_pharmacy = tp.id_pharmacy
//					where tp.id_status = 1
//					and ttmp.id_task_mp is null
//					");
//                    $this->dbHelper->execute(get_class($this) . '/update-object-rel', array('id_task' => $this->id_object));
//                }
            }

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
			select
			*
			from t_task_data
			where id_task_data = :id_object");
        $object = $this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object));

        //$object["restaurant_types"] = $this->getRestaurantTypes();

        //$object["restaurants"] = $this->getRestaurants();

        //if ($this->context->getUser()->getAttribute('id_restaurant')){
        //    $object["base_result"] = $this->getBaseResult();
        //    $object["actual_result"] = $this->getActualResult();
        //}
        //else{
        //$object["base_results"] = $this->getBaseResults();
        //$object["base_results"] = $this->getResults();
        //}

        //       $object["id_tag"] = $this->getTags($this->id_object);

        //фотки
        //$object["photos"] = $this->getPhotos($this->id_object, "parking", $this->dbHelper);

        return $object;
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
