<?php

class ParkingTariffFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "parking-tariff";
    
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
        $form = new ParkingTariffForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Тариф на парковку";

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
        ///if (!empty($_FILES)){
         //   return $this->saveResults();
        //}
        
        $form = new ParkingTariffForm($this->context, array('id' => $this->formId));

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
            if(empty($values["id_parking_tariff"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_parking_tariff (
                        id_parking_tariff, 
                        name,
                        cost_hour,
                        cost_day,
                        cost_month,
                        cost_month_daily,
                        cost_month_nightly,
                        cost_month3,
                        cost_month3_daily,
                        cost_month3_nightly,
                        cost_month6,
                        cost_month6_daily,
                        cost_month6_nightly,
                        cost_month12,
                        cost_month12_daily,
                        cost_month12_nightly,
                        id_status
					)
					values (
                        :id_parking_tariff,
                        :name,
                        :cost_hour,
                        :cost_day,
                        :cost_month,
                        :cost_month_daily,
                        :cost_month_nightly,
                        :cost_month3,
                        :cost_month3_daily,
                        :cost_month3_nightly,
                        :cost_month6,
                        :cost_month6_daily,
                        :cost_month6_nightly,
                        :cost_month12,
                        :cost_month12_daily,
                        :cost_month12_nightly,
                        :id_status
					)
					returning id_parking_tariff into :id_object");
                $this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
                $values['id_parking'] = $this->id_object;
            }
            //обновляем данные
            else{
                //if (!$this->context->getUser()->getAttribute('id_restaurant')) {
                    $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_parking_tariff
					set 
                        name = :name,
                        cost_hour = :cost_hour,
                        cost_day = :cost_day,
                        cost_month = :cost_month,
                        cost_month_daily = :cost_month_daily,
                        cost_month_nightly = :cost_month_nightly,
                        cost_month3 = :cost_month3,
                        cost_month3_daily = :cost_month3_daily,
                        cost_month3_nightly = :cost_month3_nightly,
                        cost_month6 = :cost_month6,
                        cost_month6_daily = :cost_month6_daily,
                        cost_month6_nightly = :cost_month6_nightly,
                        cost_month12 = :cost_month12,
                        cost_month12_daily = :cost_month12_daily,
                        cost_month12_nightly = :cost_month12_nightly,
                        id_status = :id_status
					where id_parking_tariff = :id_parking_tariff");
                    $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                    $this->id_object = $values["id_parking_tariff"];
                //}
                //else {
                //    $this->id_object = $values["id_purpose"];
                    
                //    if (isset($request_values['actual_result']))
                //        $this->setActualResult($values_base['actual_result']);
                //}
            }
            
//            if (!$this->context->getUser()->getAttribute('id_restaurant')) {
 //               $this->setRestaurantTypes($values["restaurant_types"]);
   //             $this->setRestaurants($values["restaurants"]);

     //           if (!empty($values["id_purpose"]))
       //             $this->syncBaseResults($request_values);
         //   }
            
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
			select 
			*
			from t_parking_tariff
			where id_parking_tariff = :id_object");
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
