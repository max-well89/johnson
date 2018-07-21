<?php

/** описатель маршрута */
class Route {

	private $routePoints = array();			// пройденные точки маршрута
	private $routePointsShort = array();	// пройденные точки маршрута (только id, для проверки циклов)
	private $lastRoutePoint = null;
	private $distance = 0;
	
	// добавление точки маршрута
	public function addPoint($routePoint) {
		// если это не первая точка - заодно и увеличим пройденную дистанцию
		if ($this->lastRoutePoint) {
			// вариант с тригонометрией по мировым координатам
			/*$this->distance+= PositionCalculator::getDistance(
				$this->lastRoutePoint['latitude'], $this->lastRoutePoint['longitude'],
				$routePoint['latitude'], $routePoint['longitude']);*/
			// для декартовых координат расстояние можем посчитать по sqrt(pow(x2 - x1, 2) + pow(y2 - y1, 2))
			$this->distance+= sqrt(pow($this->lastRoutePoint['x'] - $routePoint['x'], 2)
				+ pow($this->lastRoutePoint['y'] - $routePoint['y'], 2));
		}
		// дополняем маршрут и обновляем указатель на последнюю точку
		$this->routePointsShort[] = $routePoint['id'];
		$this->routePoints[] = $routePoint;
		$this->lastRoutePoint = $routePoint;
	}	
	
	// возвращает варианты движения из крайней точки маршрута
	public function getVariants() {
		if (isset($this->lastRoutePoint['routes'])) {
			return $this->lastRoutePoint['routes'];
		} else {
			return array();
		}
	}
	
	public function getLastRoutePoint() {
		return $this->lastRoutePoint;
	}
	
	// проверка на то, что точка принадлежит маршруту
	public function containPoint($routePoint) {
		return in_array($routePoint['id'], $this->routePointsShort);
	}
	
	public function getDistance() {
		return $this->distance;
	}
	
	public function asArray() {
		$routePoints = array();
		foreach ($this->routePoints as $routePoint) {
			$routePoints[] = $routePoint['id'];
		}
		return $routePoints;
	}

}
