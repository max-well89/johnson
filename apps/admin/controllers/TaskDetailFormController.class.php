<?php

class TaskDetailFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "task-detail";

    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();

        $this->id_object = $this->context->getRequest()->getParameter('id');
        $this->object = $this->getObject();
    }

    public function run() {
        $request = $this->getCurrentUriPart();

        switch ($request) {
            case 'get': return $this->processGetForm();    // получение формы
            case 'post': return $this->processSaveForm();   // сохранение формы
            case 'finished': return $this->batchFinished($this->context->getRequest()->getParameter('idss'));
            case 'continue': return $this->batchContinue($this->context->getRequest()->getParameter('idss'));
            case 'delete-confirm': return $this->processDeleteConfirmForm(); // подтверждение удаления формы
            case 'delete': return $this->processDeleteForm();   // удаление формы
            case 'add-parking-tag': return $this->addParkingTag();
            default: throw new nomvcPageNotFoundException('Page not found');
        }
    }

    protected function batchFinished($idss){
        $sql = 'update t_task_member_pharmacy
                set 
                    id_status = :id_status,
                    dt_status = CURRENT_TIMESTAMP 
                where id_task_mp = :id_task_mp';
        $conn = $this->context->getDb();
        $stmt = $conn->prepare($sql);

        if (is_array($idss))
            foreach ($idss as $key => $val){
                $stmt->bindValue('id_task_mp', $val);
                $stmt->bindValue('id_status', 1);
                $stmt->execute();
            }

        return true;
    }

    protected function batchContinue($idss){
        $sql = 'update t_task_member_pharmacy
                set 
                    id_status = :id_status,
                    dt_status = null 
                where id_task_mp = :id_task_mp';
        $conn = $this->context->getDb();
        $stmt = $conn->prepare($sql);

        if (is_array($idss))
            foreach ($idss as $key => $val){
                $stmt->bindValue('id_task_mp', $val);
                $stmt->bindValue('id_status', 0);
                $stmt->execute();
            }

        return true;
    }
    /*
     * Открытие формы
     */
    protected function processGetForm() {
        //вяжем данные
        $form = new TaskDetailForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = Context::getInstance()->translate($this->formId);

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
        $form = new TaskDetailForm($this->context, array('id' => $this->formId));

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
            if(empty($values["id_task_mp"])){
                unset($values_base['id_task_mp']);
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
					update t_task_member_pharmacy
					set 
					    id_status = :id_status,
					    dt_status = CURRENT_TIMESTAMP 
					where id_task_mp = :id_task_mp");
                $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                $this->id_object = $values["id_task_mp"];

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
			from v_task_detail
			where id_task_mp = :id_object");
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
