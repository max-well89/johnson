<?php

class TokenStatTable extends AbstractMapObjectTable {

	public function init($options = array()) {
		$options = array(
		    'sort_by' => 'dt',
		    'sort_order' => '',
		);
		parent::init($options);

		$this->setRowModelClass('TokenStat');
		$this->setFilterForm(new TokenStatFilterForm($this->context));
	}
	
	public function prepareData($data, $params) {
		$series = array(
			1 => array('name' => 'Android', 'data' => array()),
			2 => array('name' => 'iOS', 'data' => array())
		);
		$names = array();
		foreach ($data as $row) {
			if ($params['dt_divide'] == 'MM') {
				$names[] =  DateHelper::dateConvert(DateHelper::DBD_FORMAT, DateHelper::HTMLD_MONTH_FORMAT, $row->dt);
			} else {
				$names[] =  DateHelper::dateConvert(DateHelper::DBD_FORMAT, DateHelper::HTMLD_FORMAT, $row->dt);			
			}
			$series[1]['data'][] = intval($row->cnt_android);
			$series[2]['data'][] = intval($row->cnt_ios);
		}
		if ($params['id_mobile_os'] == 1) { unset($series[2]); }
		if ($params['id_mobile_os'] == 2) { unset($series[1]); }
		
		$series = array_values($series);
		return array($names, $series);
	}
	
	public function runForDashboard($params) {
		$criteria = new Criteria();
		$criteria->addContext('this_id_map',	$params['id_map']);
		$criteria->addContext('id_mobile_os',	$params['id_mobile_os']);
		$criteria->addContext('date_from',	$params['date']['from']);
		$criteria->addContext('date_to',	$params['date']['to']);
		$criteria->addContext('dt_divide',	$params['dt_divide']);
		$criteria->setOrderBy('dt');
		
		$data = $this->context->getModelFactory()->select($this->rowModelClass, $criteria, $this->fetchByClass);
		list($names, $series) = $this->prepareData($data, $params);
		$generator = new OutputGenerator($this->context, $this->controller);		
							
		// собственно рендерим
		return $generator->prepare('component/graph_dashboard', array(
			'title'		=> sprintf('<a href="/stat/tokens/%s">Токены</a>', $params['id_map']),
			'name'		=> 'Токены',
			'graphType'	=> 'line',
			'series'	=> $series,
			'names'		=> $names,
			'id'		=> 'tokens'
		))->run();
	}
	
	protected function runAsHtml() {
		$generator = new OutputGenerator($this->context, $this->controller);
		$criteria = $this->getCriteria();	// формируем условия выборки
		$filters = $this->getFilters();
		$data = $this->context->getModelFactory()->select($this->rowModelClass, $criteria, $this->fetchByClass);
		list($names, $series) = $this->prepareData($data, $filters);
			
		// собственно рендерим
		return $generator->prepare('component/graph', array(
			'title'		=> sprintf('<h4 class="path"><a href="/dashboard/%s">Аналитика</a> > Токены</h4>', $filters['id_map']),
			'graphType'	=> 'line',
			'series'	=> $series,
			'names'		=> $names,
			'name'		=> 'Токены',
			'filters'	=> $this->filterForm,
		))->run();
	}
}
