<?php

class ParkingFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "parking";
    
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
    
    protected function addParkingTag(){
        if (isset($_GET['element'])){
            try {
                $id_tag = false;
                $stmt = $this->context->getDb()->prepare('insert into T_TAG (name) values(:name) returning id_tag into :id_tag');
                $stmt->bindValue('name', $_GET['element']);
                $stmt->bindParam('id_tag', $id_tag, PDO::PARAM_STR, 100);
                $stmt->execute();

                if ($id_tag)
                    return json_encode(array('id'=> $id_tag, 'element' => $_GET['element']));
            }
            catch(exception $e){}
        }
        
        return;
    }
    
    protected function getTags($id_object){
        $stmt = $this->context->getDb()->prepare('select id_tag from T_PARKING_TAG where id_parking = :id_parking');
             
        $tags = array();
        try {
            $stmt->bindValue('id_parking', $id_object);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $row = array_change_key_case($row);
            
                $tags[$row['id_tag']] = $row['id_tag'];
            } 
        }
        catch(exception $e){}
        
        return $tags;
    }
    
    //по принципу полной синхронизации
    protected function setTags($values){
        if (!empty($values['id_parking'])){
            //delete
            $stmt = $this->context->getDb()->prepare('delete from T_PARKING_TAG where id_parking = :id_parking');
            $stmt->bindValue('id_parking', $values['id_parking']);
            $stmt->execute();
            
            //insert
            $stmt = $this->context->getDb()->prepare('insert into T_PARKING_TAG (id_parking, id_tag) values(:id_parking, :id_tag)');
            
            foreach ($values['id_tag'] as $val){
                try {
                    $stmt->bindValue('id_parking', $values['id_parking']);
                    $stmt->bindValue('id_tag', $val);
                    $stmt->execute();
                }
                catch(exception $e){}
            }
        }
    }
    
    /*
     * Открытие формы
     */
    protected function processGetForm() {
        //вяжем данные
        $form = new ParkingForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Парковка";

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
    protected function saveResults(){
        require_once dirname(__FILE__).'/../../../lib/external/xls/PHPExcel/IOFactory.php';
        $this->id_object = @$_REQUEST['purpose']['id_purpose'];
        
        if (!empty($this->id_object))
        foreach ($_FILES as $file){
            $inputFileType = 'Excel5';
            //$inputFileType = 'Excel2007';
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            
            try {
                $objPHPExcel = $objReader->load($file['tmp_name']['base_results']);

                //$sql = 'select ID_FILE_SEQ.nextval from dual';
                //$this->dbHelper->addQuery('select-next-file-id', $sql);
                //$id_file = $this->dbHelper->selectValue('select-next-file-id');
                    
                $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

                $header = [];
                $array_data = array();
                foreach ($rowIterator as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $rowIndex = $row->getRowIndex();

                    foreach ($cellIterator as $cell) {
                        if (1 == $row->getRowIndex()) {
                            if ($cell->getCalculatedValue() == 'ID_RESTAURANT')
                                $header[$cell->getCalculatedValue()] = $cell->getColumn();
                            elseif ($cell->getCalculatedValue() == 'VAL')
                                $header[$cell->getCalculatedValue()] = $cell->getColumn();
                        } else {
                            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                        }
                    }
                }

                $sql = 'select id_restaurant
                from v_purpose_restaurant
                where id_purpose = :id_purpose
                and id_restaurant = :id_restaurant';
                $this->dbHelper->addQuery('check-purpose-rest-exist', $sql);

                $sql = 'delete from t_purpose_base_result_tmp where id_purpose = :id_purpose';
                $this->dbHelper->addQuery('clean-base-results-tmp', $sql);
                $this->dbHelper->execute('clean-base-results-tmp', array(
                    'id_purpose' => $this->id_object
                ));
                
                $sql = 'insert into t_purpose_base_result_tmp(
                    id_purpose, 
                    id_restaurant,
                    val,
                    id_creator
                 ) values(
                    :id_purpose, 
                    :id_restaurant,
                    :val,
                    :id_creator
                )';
                $this->dbHelper->addQuery('insert-base-results-tmp', $sql);
                
                $this->dbHelper->beginTransaction();
                $stmt = $this->dbHelper->getStmt('insert-base-results-tmp');
                try {
                    foreach ($array_data as $data) {
                        if ($data[$header['ID_RESTAURANT']] && $data[$header['VAL']]) {
                            //if ($this->dbHelper->selectValue('check-purpose-rest-exist', array(
                            //    'id_purpose' => $this->id_object,
                            //    'id_restaurant' => $data[$header['ID_RESTAURANT']]
                            //))){
                                $stmt->bindValue(':id_purpose', $this->id_object);
                                $stmt->bindValue(':id_restaurant', $data[$header['ID_RESTAURANT']]);
                                $stmt->bindValue(':val', $data[$header['VAL']]);
                                $stmt->bindValue(':id_creator', $this->context->getUser()->getAttribute('id_member'));
                                $stmt->execute();
                            //}
                        }
                    }
                } catch (exception $e) {}
                
                $this->dbHelper->commit();
            }
            catch (exception $e){
                return 'INCORRECT FILE';
            }
        }

        $widget = new nomvcResultsWidget('Базовые показатели', 'base_results', array(
            'path-upload' => 'purpose-form/post'
        ));
        
        return $widget->renderForForm($this->formId, $this->getResultsTmp());
        //return $widget->renderForForm($this->formId, $this->getBaseResultsTmp());
    }
    */

    /*
     * Сохранение формы, здесь вставка и редактирование
     */
    protected function processSaveForm() {
        ///if (!empty($_FILES)){
         //   return $this->saveResults();
        //}
        
        $form = new ParkingForm($this->context, array('id' => $this->formId));

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
            if(empty($values["id_parking"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_parking (
                        id_parking,
                        ext_num,
                        name,
                        path_video,
                        address,
                        capacity,
                        latitude,
                        longitude,
                        id_parking_tariff,
                        id_status
					)
					values (
                        :id_parking,
                        :ext_num,
                        :name,
                        :path_video,
                        :address,
                        :capacity,
                        :latitude,
                        :longitude,
                        :id_parking_tariff,
                        :id_status
					)
					returning id_parking into :id_object");
                $this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
                $values['id_parking'] = $this->id_object;
            }
            //обновляем данные
            else{
                //var_dump($values_base); exit;
                //if (!$this->context->getUser()->getAttribute('id_restaurant')) {
                    $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_parking
					set 
					    ext_num = :ext_num,
                        name = :name,
                        path_video = :path_video,
                        address = :address,
                        capacity = :capacity,
                        latitude = :latitude,
                        longitude = :longitude,
                        id_parking_tariff = :id_parking_tariff,
                        id_status = :id_status  
					where id_parking = :id_parking");
                    $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                    $this->id_object = $values["id_parking"];
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

            $this->setTags($values);
            
            $this->setPhotos($values, "parking", $this->dbHelper);
            
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
			id_parking,
			ext_num,
			name,
			path_video,
            address,
            capacity,
            latitude,
            longitude,
            id_parking_tariff,
			id_status
			from t_parking
			where id_parking = :id_object");
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

        $object["id_tag"] = $this->getTags($this->id_object);

        //фотки
        $object["photos"] = $this->getPhotos($this->id_object, "parking", $this->dbHelper);

        return $object;
    }
//
//    private function getRestaurantTypes(){
//        $types = [];
//        $this->dbHelper->addQuery(get_class($this) . '/select-purpose-restaurant-types', "
//			select id_restaurant_type
//			from t_purpose_restaurant_type
//			where id_purpose = :id_object");
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-purpose-restaurant-types', array("id_object" => $this->id_object));
//
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//            $row = array_change_key_case($row);
//            $types[] = $row["id_restaurant_type"];
//        }
//        return $types;
//    }
//
//    private function setRestaurantTypes($types){
//        if (isset($types['set_all']))
//            unset($types['set_all']);
//            
//        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-restaurant-types', "
//        delete 
//        from t_purpose_restaurant_type 
//        where id_purpose = :id_object");
//        $this->dbHelper->execute(get_class($this) . '/clear-purpose-restaurant-types', array(":id_object" => $this->id_object));
//
//        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-restaurant-type', "
//        insert into t_purpose_restaurant_type (id_purpose, id_restaurant_type)
//		values (:id_object, :id_restaurant_type)");
//
//        foreach ($types as $value) {
//            $value = array("id_object" => $this->id_object, "id_restaurant_type" => $value);
//            $this->dbHelper->execute(get_class($this) . '/insert-purpose-restaurant-type', $value);
//        }
//    }
//
//    private function getResults(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-base-results', '
//            select 
//            tr.id_restaurant, 
//            tr.name as restaurant, 
//            tpbr.val as result,
//            tpar.val as result_actual
//            from t_purpose_base_result tpbr 
//            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
//            left join t_purpose_actual_result tpar on tpbr.id_restaurant = tpar.id_restaurant and 
//            tpbr.id_purpose = tpar.id_purpose
//            where tpbr.id_purpose = :id_object
//            order by id_base_result--tr.name
//		');
//
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results', array(
//            "id_object" => $this->id_object
//        ));
//
//        $results = [];
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $results[] = array_change_key_case($row);
//        }
//
//        return $results;
//    }
//
//    private function getResultsTmp(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-base-results-tmp', '
//            select 
//            tr.id_restaurant, 
//            tr.name as restaurant, 
//            tpbrt.val as result,
//            tpar.val as result_actual
//            from t_purpose_base_result_tmp tpbrt 
//            inner join T_RESTAURANT tr on tpbrt.id_restaurant = tr.id_restaurant 
//            left join t_purpose_actual_result tpar on tpbrt.id_restaurant = tpar.id_restaurant and
//            tpbrt.id_purpose = tpar.id_purpose
//            where tpbrt.id_purpose = :id_object
//            order by id_base_result_tmp--tr.name
//		');
//
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results-tmp', array(
//            "id_object" => $this->id_object
//        ));
//
//        $results = [];
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $results[] = array_change_key_case($row);
//        }
//
//        return $results;
//    }
//    
//    private function getBaseResults(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-base-results', '
//            select tr.id_restaurant, tr.name as restaurant, tpbr.val as result
//            from t_purpose_base_result tpbr 
//            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
//            where id_purpose = :id_object
//            order by id_base_result--tr.name
//		');
//
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results', array(
//            "id_object" => $this->id_object
//        ));
//
//        $results = [];
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $results[] = array_change_key_case($row);
//        }
//
//        return $results;
//    }
//
//    private function getBaseResultsTmp(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-base-results-tmp', '
//            select tr.id_restaurant, tr.name as restaurant, tpbr.val as result
//            from t_purpose_base_result_tmp tpbr 
//            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
//            where id_purpose = :id_object
//            order by id_base_result_tmp
//		');
//
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results-tmp', array(
//            "id_object" => $this->id_object
//        ));
//
//        $results = [];
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            $results[] = array_change_key_case($row);
//        }
//
//        return $results;
//    }
//    
//    private function syncBaseResults($request_values){
//        if (isset($request_values['base_results_is_new'])) {
//            $sql = 'delete from t_purpose_base_result where id_purpose = :id_purpose';
//            $this->dbHelper->addQuery('clean-base-results', $sql);
//            $this->dbHelper->execute('clean-base-results', array(
//                'id_purpose' => $this->id_object
//            ));
//
//            $this->dbHelper->addQuery(get_class($this) . '/sync-base-results', "
//                insert into t_purpose_base_result(id_purpose, id_restaurant, val)
//                select tpbr.id_purpose, tpbr.id_restaurant, tpbr.val 
//                from t_purpose_base_result_tmp tpbr
//                inner join v_purpose_restaurant vpr on tpbr.id_restaurant = vpr.id_restaurant and 
//                tpbr.id_purpose = vpr.id_purpose
//                where tpbr.id_purpose = :id_purpose
//                order by tpbr.id_base_result_tmp
//		    ");
//
//            $this->dbHelper->execute(get_class($this) . '/sync-base-results', array(
//                'id_purpose' => $this->id_object
//            ));
//        }
//        
//        $sql = 'delete from t_purpose_base_result_tmp where id_purpose = :id_purpose';
//        $this->dbHelper->addQuery('clean-base-results-tmp', $sql);
//        $this->dbHelper->execute('clean-base-results-tmp', array(
//            'id_purpose' => $this->id_object
//        ));
//
//        $sql = '
//        delete from t_purpose_base_result tpbr
//        where tpbr.id_restaurant not in (
//          select vpr.id_restaurant 
//          from v_purpose_restaurant vpr
//          where vpr.id_purpose = :id_purpose
//        )
//        and tpbr.id_purpose = :id_purpose';
//        $this->dbHelper->addQuery('clean-base-results', $sql);
//        $this->dbHelper->execute('clean-base-results', array(
//            'id_purpose' => $this->id_object
//        ));
//    }
//    
//    private function getBaseResult(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-base-result', "
//			select val 
//			from t_purpose_base_result
//			where id_purpose = :id_object
//			and id_restaurant = :id_restaurant
//			and rownum = 1
//		");
//
//        $value = $this->dbHelper->selectValue(get_class($this) . '/select-base-result', array(
//            "id_object" => $this->id_object,
//            "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant')
//        ));
//
//        return $value;
//    }
//    
//    private function getActualResult(){
//        $this->dbHelper->addQuery(get_class($this) . '/select-actual-result', "
//			select val 
//			from t_purpose_actual_result
//			where id_purpose = :id_object
//			and id_restaurant = :id_restaurant
//			and rownum = 1
//		");
//        
//        $value = $this->dbHelper->selectValue(get_class($this) . '/select-actual-result', array(
//            "id_object" => $this->id_object,
//            "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant')
//        ));
//        
//        return $value;
//    }
//
//    private function setActualResult($val){
//        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-actual-result', "
//        delete 
//        from t_purpose_actual_result 
//        where id_purpose = :id_object
//        and id_restaurant = :id_restaurant");
//        $this->dbHelper->execute(get_class($this) . '/clear-purpose-actual-result', array(
//            ":id_object" => $this->id_object,
//            ':id_restaurant' => $this->context->getUser()->getAttribute('id_restaurant')
//        ));
//
//        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-actual-result', "
//        insert into t_purpose_actual_result (id_purpose, id_restaurant, val)
//		values (:id_object, :id_restaurant, :val)");
//
//         $this->dbHelper->execute(get_class($this) . '/insert-purpose-actual-result', array(
//             "id_object" => $this->id_object,
//             "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant'),
//             "val" => $val
//         ));
//    }
//
//    private function getRestaurants(){
//        $restaurants = [];
//        $this->dbHelper->addQuery(get_class($this) . '/select-purpose-restaurants', "
//			select id_restaurant 
//			from t_purpose_restaurant
//			where id_purpose = :id_object");
//        $stmt = $this->dbHelper->select(get_class($this) . '/select-purpose-restaurants', array("id_object" => $this->id_object));
//
//        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//            $row = array_change_key_case($row);
//            $restaurants[] = $row["id_restaurant"];
//        }
//        return $restaurants;
//    }
//
//    private function setRestaurants($restaurants){
//        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-restaurants', "
//        delete 
//        from t_purpose_restaurant 
//        where id_purpose = :id_object");
//        $this->dbHelper->execute(get_class($this) . '/clear-purpose-restaurants', array(":id_object" => $this->id_object));
//
//        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-restaurant', "
//        insert into t_purpose_restaurant (id_purpose, id_restaurant)
//		values (:id_object, :id_restaurant)");
//
//        foreach ($restaurants as $value) {
//            $value = array("id_object" => $this->id_object, "id_restaurant" => $value);
//            $this->dbHelper->execute(get_class($this) . '/insert-purpose-restaurant', $value);
//        }
//    }
//    

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
/*
    protected function getButton($button, $id = null) {
        switch ($button) {
            case 'save':
                $buttonObj = new nomvcButtonWidget('Сохранить', 'save', array('type' => 'button', 'icon' => 'ok'), array('onclick' => "TableFormActions.postFormSync('{$this->formId}');"));
                break;
            case 'cancel': $buttonObj = new nomvcButtonWidget('Отменить', 'cancel', array('type' => 'button', 'icon' => 'cancel'), array('onclick' => "TableFormActions.closeForm('{$this->formId}');", 'class' => 'btn btn-warning'));
                break;
            //подтверждение удаления
            case 'delete-confirm':
                $buttonObj = new nomvcButtonWidget('Удалить', 'delete', array('type' => 'button', 'icon' => 'trash'), array('data-toggle' => 'modal', 'onclick' => "TableFormActions.deleteConfirmObject('{$this->formId}', {$id});", 'class' => 'btn btn-danger'));
                break;
            //удаление
            case 'delete':
                $buttonObj = new nomvcButtonWidget('Удалить', 'delete', array('type' => 'button', 'icon' => 'trash'), array('data-toggle' => 'modal', 'onclick' => "TableFormActions.deleteObject('{$this->formId}');", 'class' => 'btn btn-danger'));
                break;
        }

        if (isset($buttonObj))
            return $buttonObj->renderControl(null);
    }
*/
}
