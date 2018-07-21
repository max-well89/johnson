<?php

class PharmacyFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "pharmacy";

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
//
//    protected function addParkingTag(){
//        if (isset($_GET['element'])){
//            try {
//                $id_tag = false;
//                $stmt = $this->context->getDb()->prepare('insert into T_TAG (name) values(:name) returning id_tag into :id_tag');
//                $stmt->bindValue('name', $_GET['element']);
//                $stmt->bindParam('id_tag', $id_tag, PDO::PARAM_STR, 100);
//                $stmt->execute();
//
//                if ($id_tag)
//                    return json_encode(array('id'=> $id_tag, 'element' => $_GET['element']));
//            }
//            catch(exception $e){}
//        }
//
//        return;
//    }
//
//    protected function getTags($id_object){
//        $stmt = $this->context->getDb()->prepare('select id_tag from T_PARKING_TAG where id_parking = :id_parking');
//
//        $tags = array();
//        try {
//            $stmt->bindValue('id_parking', $id_object);
//            $stmt->execute();
//
//            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                $row = array_change_key_case($row);
//
//                $tags[$row['id_tag']] = $row['id_tag'];
//            }
//        }
//        catch(exception $e){}
//
//        return $tags;
//    }
//
//    //по принципу полной синхронизации
//    protected function setTags($values){
//        if (!empty($values['id_parking'])){
//            //delete
//            $stmt = $this->context->getDb()->prepare('delete from T_PARKING_TAG where id_parking = :id_parking');
//            $stmt->bindValue('id_parking', $values['id_parking']);
//            $stmt->execute();
//
//            //insert
//            $stmt = $this->context->getDb()->prepare('insert into T_PARKING_TAG (id_parking, id_tag) values(:id_parking, :id_tag)');
//
//            foreach ($values['id_tag'] as $val){
//                try {
//                    $stmt->bindValue('id_parking', $values['id_parking']);
//                    $stmt->bindValue('id_tag', $val);
//                    $stmt->execute();
//                }
//                catch(exception $e){}
//            }
//        }
//    }

    /*
     * Открытие формы
     */
    protected function processGetForm() {
        //вяжем данные
        $form = new PharmacyForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Аптека";

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
        $form = new PharmacyForm($this->context, array('id' => $this->formId));

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
            if(empty($values["id_pharmacy"])){
                unset($values_base['id_pharmacy']);
                $this->id_object = null;
                
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_pharmacy (
                        name,
                        address,
                        id_category,
                        id_region,
                        id_city,
                        id_area,
                        id_member,
                        id_crm,
                        id_status
					)
					values (
                        :name,
                        :address,
                        :id_category,
                        :id_region,
                        :id_city,
                        :id_area,
                        :id_member,
                        :id_crm,
                        :id_status
					)
					returning id_pharmacy");
                $this->id_object = $this->dbHelper->selectValue(get_class($this) . '/insert-object', $values_base, array());
                $values['id_pharmacy'] = $this->id_object;
            }
            //обновляем данные
            else{

                $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_pharmacy
					set 
					    name = :name,
                        address = :address,
                        id_category = :id_category,
                        id_region = :id_region,
                        id_city = :id_city,
                        id_area = :id_area,
                        id_member = :id_member,
                        id_crm = :id_crm,
                        id_status = :id_status
					where id_pharmacy = :id_pharmacy");
                $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                $this->id_object = $values["id_pharmacy"];
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
			from t_pharmacy
			where id_pharmacy = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));

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
