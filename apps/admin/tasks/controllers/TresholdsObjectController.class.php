<?php

class TresholdsObjectController extends nomvcBaseController{
    protected function init() {
        $this->dbHelper = $this->context->getDbHelper();
        
        $this->id_object = $this->context->getRequest()->getParameter('id');
    }

    public function run() {
        return $this->getTresholds();
        
        $request = $this->getCurrentUriPart();
        switch ($request) {
        }
    }
    
    private function getTresholds(){
        $sql = '
        select percent, point
        from T_PURPOSE_TYPE_PROP
        where id_purpose_type = :id_purpose_type
        order by order_num';
        
        $tresholds = [];
        try {
            $this->dbHelper->addQuery(get_class($this) . '/select-object', $sql);
            $stmt = $this->dbHelper->getStmt(get_class($this) . '/select-object', array(
                'id_purpose_type' => $this->id_object
            ));
            $stmt->execute();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $tresholds[] = array_change_key_case($row);
            }
        }
        catch(exception $e){}
        
        return json_encode($tresholds);
    }
}