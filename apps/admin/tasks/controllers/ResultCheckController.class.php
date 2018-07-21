<?php

class ResultCheckController extends nomvcBaseController {
    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();
        $this->id_object = $this->context->getRequest()->getParameter('id');
    }
    
    public function run()
    {
        $request = $this->getCurrentUriPart();
        
        $result = false;
        $stmt = $this->context->getDb()->prepare('
        update T_PURPOSE_ACTUAL_RESULT 
        set is_approve = decode(is_approve, 0, 1, 0) 
        where id_actual_result = :id_actual_result
        ');
        
        if ($this->id_object)
            try {
                $stmt->bindValue('id_actual_result', $this->id_object);
//                $stmt->bindValue('id_member', $this->context->getUser()->getAttribute('id_member'));
                $result = $stmt->execute();

                if ($result)
                    return json_encode(array('result' => 'success'));
            }
            catch(exception $e){}


        return json_encode(array(
            'result' => 'error',
            'message' => ''
        ));

    }
}