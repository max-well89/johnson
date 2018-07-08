<?php

class BeaconData {

	protected $x;	
	protected $y;
	protected $R;
	protected $A;
	protected $longitude;
	protected $latitude;
	
	
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
	public function getR() { return $this->R; }
	public function getA() { return $this->A; }
	public function setLongitude($longitude)	{ $this->longitude = $longitude; }
	public function setLatitude($latitude)		{ $this->latitude  = $latitude; }
	public function getLongitude()	{ return $this->longitude; }
	public function getLatitude()	{ return $this->latitude; }
	
	public function __construct($x, $y, $R, $A = 1) {
		$this->x = $x;
		$this->y = $y;
		$this->R = $R;
		$this->A = $A;
	}
	
	public static function loadByIDs($major, $minor, $rssi, $accuracy, $mapLinking) {
		$beacon = Doctrine::getTable('T_BEACON')->createQuery()
			->where('signal_power = 3')
			->andWhere('major = ?', $major)
			->andWhere('minor = ?', $minor)->fetchOne();
		if (!$beacon) return false;
		list(list($x, $y)) = $mapLinking->toLocal($beacon->getLongitude(), $beacon->getLatitude());
		$R = ($rssi * -3 - 60) / $mapLinking->metresInPixel;
		//var_dump($R);
		$beaconObj = new self($x, $y, $R, $accuracy);
		$beaconObj->setLongitude($beacon->getLongitude());
		$beaconObj->setLatitude($beacon->getLatitude());
		return $beaconObj;
	}
}
