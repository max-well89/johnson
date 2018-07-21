<?php

class PurposeOneFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "purpose-one";

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
        $form = new PurposeOneForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Цель на увеличение продаж";

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

    protected function saveResults(){
        require_once dirname(__FILE__).'/../../../lib/external/xls/PHPExcel/IOFactory.php';
        $this->id_object = @$_REQUEST['purpose-one']['id_purpose'];
        if (!empty($this->id_object))
            foreach ($_FILES as $file){

                $inputFileType = 'Excel5';
                //$inputFileType = 'Excel2007';
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setReadDataOnly(true);

                try {
                    $objPHPExcel = $objReader->load($file['tmp_name']['base_results']);

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
                                elseif ($cell->getCalculatedValue() == 'CNT1')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'CNT2')
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
                        cnt1,
                        cnt2,
                        id_creator
                     ) values(
                        :id_purpose, 
                        :id_restaurant,
                        :cnt1,
                        :cnt2,
                        :id_creator
                    )';
                    $this->dbHelper->addQuery('insert-base-results-tmp', $sql);

                    $this->dbHelper->beginTransaction();
                    $stmt = $this->dbHelper->getStmt('insert-base-results-tmp');
                    try {
                        foreach ($array_data as $data) {
                            if ($data[$header['ID_RESTAURANT']] && $data[$header['CNT1']] && $data[$header['CNT2']]) {
                                //if ($this->dbHelper->selectValue('check-purpose-rest-exist', array(
                                //    'id_purpose' => $this->id_object,
                                //    'id_restaurant' => $data[$header['ID_RESTAURANT']]
                                //))){
                                $stmt->bindValue(':id_purpose', $this->id_object);
                                $stmt->bindValue(':id_restaurant', $data[$header['ID_RESTAURANT']]);
                                $stmt->bindValue(':cnt1', $data[$header['CNT1']]);
                                $stmt->bindValue(':cnt2', $data[$header['CNT2']]);
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
            'path-upload' => 'purpose-one-form/post'
        ));

        return $widget->renderForForm($this->formId, $this->getResultsTmp());
        //return $widget->renderForForm($this->formId, $this->getBaseResultsTmp());
    }

    /*
     * Сохранение формы, здесь вставка и редактирование
     */
    protected function processSaveForm() {
        //отдельно
        if (!empty($_FILES)){
            return $this->saveResults();
        }

        $form = new PurposeOneForm($this->context, array('id' => $this->formId));
        $request_values = $this->getFormData($this->formId);

        //insert default values
        if (isset($request_values['id_purpose']) && true) {
            $this->id_object = $request_values['id_purpose'];

            $original_values = $this->getObject();

            if (isset($original_values['id_purpose_type']) && empty($request_values['id_purpose_type']))
                $request_values['id_purpose_type'] = $original_values['id_purpose_type'];
        }

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
            if(empty($values["id_purpose"])){
                $this->id_object = null;
                $this->dbHelper->addQuery(get_class($this) . '/insert-object', "
					insert into t_purpose (
                        id_purpose_type,
                        id_month,
                        percent_one,
                        point_one,
                        percent_two,
                        point_two,
                        percent_three,
                        point_three,
                        dt_from,
                        dt_to,
                        description
                        --id_measure_type
					)
					values (
                        :id_purpose_type,
                        :id_month,
                        :percent_one,
                        :point_one,
                        :percent_two,
                        :point_two,
                        :percent_three,
                        :point_three,
                        :dt_from,
                        :dt_to,
                        :description
                        --:id_measure_type
					)
					returning id_purpose into :id_object");
                $this->dbHelper->execute(get_class($this) . '/insert-object', $values_base, array('id_object' => &$this->id_object));
                $values['id_purpose'] = $this->id_object;
            }
            //обновляем данные
            else{
                if (!$this->context->getUser()->getAttribute('id_restaurant')) {
                    $this->dbHelper->addQuery(get_class($this) . '/update-object', "
					update t_purpose
					set 
                        id_purpose_type = :id_purpose_type,
                        id_month = :id_month,
                        percent_one = :percent_one,
                        point_one = :point_one,
                        percent_two = :percent_two,
                        point_two = :point_two,
                        percent_three = :percent_three,
                        point_three = :point_three,
                        dt_from = :dt_from,
                        dt_to = :dt_to,
                        description = :description
                        --id_measure_type = :id_measure_type
					where id_purpose = :id_purpose");
                    $this->dbHelper->execute(get_class($this) . '/update-object', $values_base);
                    $this->id_object = $values["id_purpose"];
                }
                else {
                    $this->id_object = $values["id_purpose"];

                    if (isset($request_values['actual_result_cnt1']) || isset($request_values['actual_result_cnt2']))
                        $this->setActualResult($values_base);
                }
            }

            if (!$this->context->getUser()->getAttribute('id_restaurant')) {
                $this->setRestaurantTypes($values["restaurant_types"]);
                $this->setRestaurants($values["restaurants"]);

                if (!empty($values["id_purpose"]))
                    $this->syncBaseResults($request_values);
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
			select 
			id_purpose, 
			id_purpose_type,
			id_month,
			percent_one,
			point_one,
			percent_two,
			point_two,
			percent_three,
			point_three,
			dt_from,
			dt_to,
			dt,
			description
			--id_measure_type
			from t_purpose
			where id_purpose = :id_object");
        $object = array_change_key_case($this->dbHelper->selectRow(get_class($this) . '/select-object', array('id_object' => $this->id_object)));

        $object["restaurant_types"] = $this->getRestaurantTypes();

        $object["restaurants"] = $this->getRestaurants();

        if ($this->context->getUser()->getAttribute('id_restaurant')){
            $base = $this->getBaseResult();
            $object["base_result_cnt1"] = $base['base_result_cnt1'];
            $object["base_result_cnt2"] = $base['base_result_cnt2'];
            $object["base_result"] = $base['base_result'];

            $actual = $this->getActualResult();
            $object["actual_result_cnt1"] = $actual['actual_result_cnt1'];
            $object["actual_result_cnt2"] = $actual['actual_result_cnt2'];
            $object["actual_result"] = $actual['actual_result'];
        }
        else{
            //$object["base_results"] = $this->getBaseResults();
            $object["base_results"] = $this->getResults();
        }

        //фотки
        ///$object["photos"] = $this->getPhotos($this->id_object, "object", $this->dbHelper);

        return $object;
    }

    private function getRestaurantTypes(){
        $types = [];
        $this->dbHelper->addQuery(get_class($this) . '/select-purpose-restaurant-types', "
			select id_restaurant_type
			from t_purpose_restaurant_type
			where id_purpose = :id_object");
        $stmt = $this->dbHelper->select(get_class($this) . '/select-purpose-restaurant-types', array("id_object" => $this->id_object));

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($row);
            $types[] = $row["id_restaurant_type"];
        }
        return $types;
    }

    private function setRestaurantTypes($types){
        if (isset($types['set_all']))
            unset($types['set_all']);

        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-restaurant-types', "
        delete 
        from t_purpose_restaurant_type 
        where id_purpose = :id_object");
        $this->dbHelper->execute(get_class($this) . '/clear-purpose-restaurant-types', array(":id_object" => $this->id_object));

        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-restaurant-type', "
        insert into t_purpose_restaurant_type (id_purpose, id_restaurant_type)
		values (:id_object, :id_restaurant_type)");

        foreach ($types as $value) {
            $value = array("id_object" => $this->id_object, "id_restaurant_type" => $value);
            $this->dbHelper->execute(get_class($this) . '/insert-purpose-restaurant-type', $value);
        }
    }

    private function getResults(){
        $this->dbHelper->addQuery(get_class($this) . '/select-base-results', '
            select 
            tr.id_restaurant, 
            tr.name as restaurant, 
            tpbr.cnt1 as base_result_cnt1,
            tpbr.cnt2 as base_result_cnt2,
            round(tpbr.cnt1 / decode(tpbr.cnt2, 0, null, tpbr.cnt2) * 100, 2) as base_result,
            tpar.cnt1 as actual_result_cnt1,
            tpar.cnt2 as actual_result_cnt2,
            round(tpar.cnt1 / decode(tpar.cnt2, 0, null, tpar.cnt2) * 100, 2) as actual_result,
            round(
                (
                    (tpar.cnt1 / decode(tpar.cnt2, 0, null, tpar.cnt2) * 100)
                    -
                    (tpbr.cnt1 / decode(tpbr.cnt2, 0, null, tpbr.cnt2) * 100)
                )
                /
                (tpbr.cnt1 / decode(tpbr.cnt2, 0, null, tpbr.cnt2) * 100)
                *
                100
            , 2) as result
            from t_purpose_base_result tpbr 
            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
            left join t_purpose_actual_result tpar on tpbr.id_restaurant = tpar.id_restaurant and 
            tpbr.id_purpose = tpar.id_purpose
            where tpbr.id_purpose = :id_object
            order by id_base_result--tr.name
		');

        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results', array(
            "id_object" => $this->id_object
        ));

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $results[] = array_change_key_case($row);
        }

        return $results;
    }

    private function getResultsTmp(){
        $this->dbHelper->addQuery(get_class($this) . '/select-base-results-tmp', '
            select
            tr.id_restaurant, 
            tr.name as restaurant, 
            tpbrt.cnt1 as base_result_cnt1,
            tpbrt.cnt2 as base_result_cnt2,
            round(tpbrt.cnt1 / decode(tpbrt.cnt2, 0, null, tpbrt.cnt2) * 100, 2) as base_result,
            tpar.cnt1 as actual_result_cnt1,
            tpar.cnt2 as actual_result_cnt2,
            round(tpar.cnt1 / decode(tpar.cnt2, 0, null, tpar.cnt2) * 100, 2) as actual_result,
            round(
                (
                    (tpar.cnt1 / decode(tpar.cnt2, 0, null, tpar.cnt2) * 100)  
                    -
                    (tpbrt.cnt1 / decode(tpbrt.cnt2, 0, null, tpbrt.cnt2) * 100)
                )
                /
                (tpbrt.cnt1 / decode(tpbrt.cnt2, 0, null, tpbrt.cnt2) * 100)
                *
                100
            , 2) as result
            from t_purpose_base_result_tmp tpbrt 
            inner join T_RESTAURANT tr on tpbrt.id_restaurant = tr.id_restaurant 
            left join t_purpose_actual_result tpar on tpbrt.id_restaurant = tpar.id_restaurant and
            tpbrt.id_purpose = tpar.id_purpose
            where tpbrt.id_purpose = :id_object
            order by id_base_result_tmp--tr.name
		');

        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results-tmp', array(
            "id_object" => $this->id_object
        ));

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $results[] = array_change_key_case($row);
        }

        return $results;
    }

    private function getBaseResults(){
        $this->dbHelper->addQuery(get_class($this) . '/select-base-results', '
            select 
            tr.id_restaurant, 
            tr.name as restaurant, 
            tpbr.val as result
            from t_purpose_base_result tpbr 
            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
            where id_purpose = :id_object
            order by id_base_result--tr.name
		');

        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results', array(
            "id_object" => $this->id_object
        ));

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $results[] = array_change_key_case($row);
        }

        return $results;
    }

    private function getBaseResultsTmp(){
        $this->dbHelper->addQuery(get_class($this) . '/select-base-results-tmp', '
            select 
            tr.id_restaurant, 
            tr.name as restaurant, 
            tpbr.val as result
            from t_purpose_base_result_tmp tpbr 
            inner join T_RESTAURANT tr on tpbr.id_restaurant = tr.id_restaurant 
            where id_purpose = :id_object
            order by id_base_result_tmp
		');

        $stmt = $this->dbHelper->select(get_class($this) . '/select-base-results-tmp', array(
            "id_object" => $this->id_object
        ));

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $results[] = array_change_key_case($row);
        }

        return $results;
    }

    private function syncBaseResults($request_values){
        if (isset($request_values['base_results_is_new'])) {
            $sql = 'delete from t_purpose_base_result where id_purpose = :id_purpose';
            $this->dbHelper->addQuery('clean-base-results', $sql);
            $this->dbHelper->execute('clean-base-results', array(
                'id_purpose' => $this->id_object
            ));

            $this->dbHelper->addQuery(get_class($this) . '/sync-base-results', "
                insert into t_purpose_base_result(id_purpose, id_restaurant, cnt1, cnt2)
                select tpbr.id_purpose, tpbr.id_restaurant, tpbr.cnt1, tpbr.cnt2 
                from t_purpose_base_result_tmp tpbr
                inner join v_purpose_restaurant vpr on tpbr.id_restaurant = vpr.id_restaurant and 
                tpbr.id_purpose = vpr.id_purpose
                where tpbr.id_purpose = :id_purpose
                order by tpbr.id_base_result_tmp
		    ");

            $this->dbHelper->execute(get_class($this) . '/sync-base-results', array(
                'id_purpose' => $this->id_object
            ));
        }

        $sql = 'delete from t_purpose_base_result_tmp where id_purpose = :id_purpose';
        $this->dbHelper->addQuery('clean-base-results-tmp', $sql);
        $this->dbHelper->execute('clean-base-results-tmp', array(
            'id_purpose' => $this->id_object
        ));

        $sql = '
        delete from t_purpose_base_result tpbr
        where tpbr.id_restaurant not in (
          select vpr.id_restaurant 
          from v_purpose_restaurant vpr
          where vpr.id_purpose = :id_purpose
        )
        and tpbr.id_purpose = :id_purpose';
        $this->dbHelper->addQuery('clean-base-results', $sql);
        $this->dbHelper->execute('clean-base-results', array(
            'id_purpose' => $this->id_object
        ));
    }

    private function getBaseResult(){
        $this->dbHelper->addQuery(get_class($this) . '/select-base-result', "
			select 
			cnt1 as base_result_cnt1, 
			cnt2 as base_result_cnt2,
			round(cnt1 / decode(cnt2, 0, null, cnt2) * 100, 2) base_result
			from t_purpose_base_result
			where id_purpose = :id_object
			and id_restaurant = :id_restaurant
			and rownum = 1
		");

        $row = $this->dbHelper->selectRow(get_class($this) . '/select-base-result', array(
            "id_object" => $this->id_object,
            "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant')
        ));

        return $row;
    }

    private function getActualResult(){
        $this->dbHelper->addQuery(get_class($this) . '/select-actual-result', "
			select 
	    	cnt1 as actual_result_cnt1, 
			cnt2 as actual_result_cnt2,
			round(cnt1 / decode(cnt2, 0, null, cnt2) * 100, 2) actual_result
			from t_purpose_actual_result
			where id_purpose = :id_object
			and id_restaurant = :id_restaurant
			and rownum = 1
		");

        $row = $this->dbHelper->selectRow(get_class($this) . '/select-actual-result', array(
            "id_object" => $this->id_object,
            "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant')
        ));

        return $row;
    }

    private function setActualResult($values){
        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-actual-result', "
        delete 
        from t_purpose_actual_result 
        where id_purpose = :id_object
        and id_restaurant = :id_restaurant");
        $this->dbHelper->execute(get_class($this) . '/clear-purpose-actual-result', array(
            ":id_object" => $this->id_object,
            ':id_restaurant' => $this->context->getUser()->getAttribute('id_restaurant')
        ));

        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-actual-result', "
        insert into t_purpose_actual_result (id_purpose, id_restaurant, cnt1, cnt2)
		values (:id_object, :id_restaurant, :cnt1, :cnt2)");

        $this->dbHelper->execute(get_class($this) . '/insert-purpose-actual-result', array(
            "id_object" => $this->id_object,
            "id_restaurant" => $this->context->getUser()->getAttribute('id_restaurant'),
            "cnt1" => $values['actual_result_cnt1'],
            "cnt2" => $values['actual_result_cnt2']
        ));
    }

    private function getRestaurants(){
        $restaurants = [];
        $this->dbHelper->addQuery(get_class($this) . '/select-purpose-restaurants', "
			select id_restaurant 
			from t_purpose_restaurant
			where id_purpose = :id_object");
        $stmt = $this->dbHelper->select(get_class($this) . '/select-purpose-restaurants', array("id_object" => $this->id_object));

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($row);
            $restaurants[] = $row["id_restaurant"];
        }
        return $restaurants;
    }

    private function setRestaurants($restaurants){
        $this->dbHelper->addQuery(get_class($this) . '/clear-purpose-restaurants', "
        delete 
        from t_purpose_restaurant 
        where id_purpose = :id_object");
        $this->dbHelper->execute(get_class($this) . '/clear-purpose-restaurants', array(":id_object" => $this->id_object));

        $this->dbHelper->addQuery(get_class($this) . '/insert-purpose-restaurant', "
        insert into t_purpose_restaurant (id_purpose, id_restaurant)
		values (:id_object, :id_restaurant)");

        foreach ($restaurants as $value) {
            $value = array("id_object" => $this->id_object, "id_restaurant" => $value);
            $this->dbHelper->execute(get_class($this) . '/insert-purpose-restaurant', $value);
        }
    }

}
