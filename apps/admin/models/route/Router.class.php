<?php

/** Построитель маршрута */
class Router {

	private $minDistance = false;

	// маршруты
	private $routePoints;

	public function __construct($routePoints) {
		$this->routePoints = $routePoints;
	}

	// получить маршрут из вершины $from в вершину $to
	public function getRoute($from, $to) {
		$route = new Route();
		$route->addPoint($this->routePoints[$from]);
		$route = $this->nextStep($route, $to);
		if ($route) {
			return $route->asArray();
		}
	}
	
	/**
	 *	рекурсивно вызывается на каждом шаге поиска маршрута
	 *	$route - пройденный маршрут
	 *	$to - камо грядеши
	 */
	public function nextStep($route, $to) {
		// проверка на то, что бы исходный маршрут не был длинее найденного ранее
		if ($this->minDistance != false && $route->getDistance() > $this->minDistance) {
			return null;
		}
		$minDistance = false;	// дистанция текущего оптимального маршрута на данном шаге (в данной ветви)
		$prefRoute = null;		// ссылка на описатель наиболее оптимального маршрута
		// смотрим на варианты пути
		$variants = array_flip($route->getVariants());
		$basePoint = $route->getLastRoutePoint();	// текущая точка пути
		$aPoint = $this->routePoints[$to];			// конечная точка пути
		// азимут-вектор из текущей до конечной точки
		$aVector = array($aPoint['x'] - $basePoint['x'], $aPoint['y'] - $basePoint['y']);
		$aVectorL = sqrt(pow($aVector[0], 2) + pow($aVector[1], 2));	// длинна азимут-вектора
		// проверяем все варианты маршрута
		foreach($variants as $i => $val) {
			if ($i == $to) {	//	если эта новая точка- конечная
				$variants[$i] = 0;
			} else {
				$bPoint = $this->routePoints[$i];
				// азимут-вектор до следующей точки пути
				$bVector = array($bPoint['x'] - $basePoint['x'], $bPoint['y'] - $basePoint['y']);
				$bVectorL = sqrt(pow($bVector[0], 2) + pow($bVector[1], 2));	// длинна азимут-вектора
				if ($aVectorL > 0 && $bVectorL > 0) {
					$variants[$i] = abs(acos(($aVector[0] * $bVector[0] + $aVector[1] * $bVector[1]) / ($aVectorL * $bVectorL)));
				}
			}
		}
		asort($variants);
		$variants = array_keys($variants);
		
		
		foreach ($variants as $nextPoint) {	// для каждого варианта пути из данной точки
			$nextPoint = $this->routePoints[$nextPoint];	// получаем описатель новой точки маршрута
			if ($nextPoint['id'] == $to) {	// если следующий шаг - конечная точка маршрута
				$route->addPoint($nextPoint);			// добавляем эту точку к маршруту
				return $route;							// и на этом успокаиваемся
			} elseif (!$route->containPoint($nextPoint)) {	// во избежание циклов - проверяем, не проходили ли мы эту точку ранее
				$routeTemp = clone($route);				// клонируем текущий маршрут
				$routeTemp->addPoint($nextPoint);		// и добавляем к клону новую точку
				$routeTemp = $this->nextStep($routeTemp, $to);	// делаем еще шаг
				if ($routeTemp != null) {	// и если в результате этого шага у нас получился маршрут - проверяем,
					// насколько он оптимален, по сравнению с текущим оптимальным маршрутом
					if ($minDistance === false || ($routeTemp->getDistance() < $minDistance)) {
						$prefRoute = $routeTemp;
						$minDistance = $routeTemp->getDistance();
					}
					// проверка на минимальность дистанции
					if ($this->minDistance === false || ($routeTemp->getDistance() < $this->minDistance)) {
						$this->minDistance = $routeTemp->getDistance();
						//return $prefRoute;
					}
				}
			}
		}
		// возвращаем оптимальный маршрут этого шага или же ничего, если эта ветвь тупиковая
		return $prefRoute;
	}

}
