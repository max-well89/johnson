<?php

class MemberImportFormController extends AbstractFormController{
    /** @var int ID объекта */
    private $id_object = 0;
    /** @var array данные объекта */
    private $object;
    /** @var dbHelper экземпляр хелпера данных */
    private $dbHelper;
    /** @var string название формы */
    protected $formId = "member-import";

    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();

        $this->id_object = $this->context->getRequest()->getParameter('id');
        $this->object = $this->getObject();
    }

    protected function getButton($button, $id = null) {
        switch ($button) {
            case 'save':
                $buttonObj = new nomvcButtonWidget('Сохранить', 'save', array('type' => 'button', 'icon' => 'ok'), array('onclick' => "TableFormActions.postFormClassic('{$this->formId}');"));
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
    
    /*
     * Открытие формы
     */
    protected function processGetForm() {
        //вяжем данные
        $form = new MemberImportForm($this->context, array('id' => $this->formId));
        $form->bind($this->object);

        $formTitle = "Подгрузка списка пользователей";

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
            if (!empty($_FILES))
            foreach ($_FILES as $file){
                $inputFileType = 'Excel5';
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setReadDataOnly(true);
                
                try {
                    $objPHPExcel = $objReader->load($file['tmp_name']['file']);
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
                                elseif ($cell->getCalculatedValue() == 'ID_POSITION')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'SURNAME')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'NAME')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'LEARNING_ID')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'ID_ROLE')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                                elseif ($cell->getCalculatedValue() == 'VAL')
                                    $header[$cell->getCalculatedValue()] = $cell->getColumn();
                            } else {
                                $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
                            }
                        }
                    }
                    /*$sql = 'select id_restaurant
                    from v_purpose_restaurant
                    where id_purpose = :id_purpose
                    and id_restaurant = :id_restaurant';
                    $this->dbHelper->addQuery('check-purpose-rest-exist', $sql);

                    $sql = 'delete from t_purpose_base_result_tmp where id_purpose = :id_purpose';
                    $this->dbHelper->addQuery('clean-base-results-tmp', $sql);
                    $this->dbHelper->execute('clean-base-results-tmp', array(
                        'id_purpose' => $this->id_object
                    ));
                    */
                    
                    $sql = 'insert into t_member(
                        id_restaurant,
                        id_position,
                        surname,
                        name,
                        learning_id
                     ) values(
                        :id_restaurant,
                        :id_position,
                        :surname,
                        :name,
                        :learning_id
                    ) returning id_member into :id_member';
                    $this->dbHelper->addQuery('insert-member-one', $sql);

                    $this->dbHelper->addQuery('insert-member-check1','
                        select count(*)
                        from T_RESTAURANT 
                        where id_restaurant = :id_restaurant');

                    $this->dbHelper->addQuery('insert-member-check2','
                        select count(*)
                        from V_POSITION 
                        where id_position = :id_position');
                    
                    $sql = 'insert into t_member_role(
                        id_member,
                        id_role
                     ) values(
                        :id_member,
                        :id_role
                    )';
                    $this->dbHelper->addQuery('insert-member-role', $sql);
                    
//                    $this->dbHelper->beginTransaction();
                    try {
                        foreach ($array_data as $data) {
                            $cnt1 = $this->dbHelper->selectValue('insert-member-check1' , array(
                                'id_restaurant' => $data[$header['ID_RESTAURANT']]
                            ));
                            
                            $cnt2 = $this->dbHelper->selectValue('insert-member-check2' , array(
                                'id_position' => $data[$header['ID_POSITION']]
                            ));
                            
                            if ($cnt1 == 0 || $cnt2 == 0)
                                continue;
                            
                            if (in_array($data[$header['ID_ROLE']], array(2,3))){
                                $id_member = null;
                                $stmt = $this->dbHelper->getStmt('insert-member-one' , array(
                                    'id_restaurant' => $data[$header['ID_RESTAURANT']],
                                    'id_position' => $data[$header['ID_POSITION']],
                                    'surname' => $data[$header['SURNAME']],
                                    'name' =>$data[$header['NAME']],
                                    'learning_id' => $data[$header['LEARNING_ID']]
                                ), array(
                                    'id_member' => &$id_member
                                ), array());
                                $stmt->execute();

                                if (!empty($id_member)){
                                    $stmt2 = $this->dbHelper->getStmt('insert-member-role', array(
                                        'id_member' => $id_member,
                                        'id_role' => $data[$header['ID_ROLE']]
                                    ));
                                    $stmt2->execute();
                                }
                            }
                        }
                    } catch (exception $e) {
                        var_dump($e->getMessage()); exit;
                    }

  //                  $this->dbHelper->commit();
                }
                catch (exception $e){
                    return json_encode(array(
                        'result' => 'error',
                        'fields' => array('file'=>'invalid'),
                        'message' => ''
                    ));
                }
            }
        return json_encode(array(
            'result' => 'success',
            'message' => ''
        ));
    }

    /*
     * Сохранение формы, здесь вставка и редактирование
     */
    protected function processSaveForm() {
        //if (!empty($_FILES)){
            return $this->saveResults();
        //}
    }

    /** Формируем объект для формы */
    private function getObject() {
        
    }
}