<?php

class MapLinkingPoint {

	private $gx;
	private $gy;
	private $lx;
	private $ly;
	
	public function __construct($gx, $gy, $lx, $ly) {
		$this->gx = $gx;
		$this->gy = $gy;
		$this->lx = $lx;
		$this->ly = $ly;
	}
	
	public function getLocal() {
		return array($this->lx, $this->ly);
	}
	
	public function getGlobal() {
		return array($this->gx, $this->gy);
	}
	
}
