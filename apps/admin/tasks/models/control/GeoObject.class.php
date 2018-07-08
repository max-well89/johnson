<?php
/**
 * Тэги. Базовый класс
 */
class GeoObject {
	public static function getTableName() {
		return 'V_GEO_OBJECT';
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
		return $this->id_geo_object;
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
