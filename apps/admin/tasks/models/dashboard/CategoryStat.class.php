<?php

class CategoryStat {
	public static function getTableName() {
		return 'v_category_stat';
	}
	
	public function getRowStatusClass() {
		return '';	
	}
	
	public function getRowTitle() {
		return '';
	}
	
	public static function getTotal() {
		return array();
	}

	public function __get($name) { return $this->get($name); }
	
	public function getRowId() {
		return $this->id_user;
	}
	
	public function get($name) {
		$name = strtoupper($name);
		if (isset($this->$name)) {
			return $this->$name;
		} else {
			return null;
		}
	}
}
