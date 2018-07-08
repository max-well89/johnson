<?php

class nomvcMsisdnValidator extends nomvcBaseValidator {

    public function clean($value) {
		$value = parent::clean($value);
        
        $value = preg_replace('/[^\d]/i', '', $value);
        
        if (!preg_match('/^7\d{10}$/', $value)) {
            throw new nomvcInvalidValueException($value);
        }
        
		return $value;
	}
    
    public function makeFormat($value){
        return preg_replace('/[^\d]/i', '', $value);
    }
}

?>
