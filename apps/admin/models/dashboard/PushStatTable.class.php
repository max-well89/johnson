<?php

class PushStatTable extends AbstractMapObjectTable {

	public function init($options = array()) {
		$options = array(
		    'sort_by' => 'dt',
		    'sort_order' => '',
		);
		parent::init($options);

		$this->setRowModelClass('PushStat');
		$this->setFilterForm(new PushStatFilterForm($this->context));
	}
	
	public function prepareData($data, $params) {	
		$series = array(
			array('name' => 'Отправлено', 'data' => array()),
			array('name' => 'Доставлено', 'data' => array())
		);
		$names = array();
		foreach ($data as $row) {
			if ($params['dt_divide'] == 'MM') {
				$names[] =  DateHelper::dateConvert(DateHelper::DBD_FORMAT, DateHelper::HTMLD_MONTH_FORMAT, $row->dt);
			} else {
				$names[] =  DateHelper::dateConvert(DateHelper::DBD_FORMAT, DateHelper::HTMLD_FORMAT, $row->dt);			
			}
			$series[0]['data'][] = intval($row->cnt_sended);
			$series[1]['data'][] = intval($row->cnt_delivered);
		}
		
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
			'title'		=> sprintf('<a href="/stat/push-stat/%s">Push-уведомления</a>', $params['id_map']),
			'name'		=> 'Push-уведомления',
			'graphType'	=> 'column',
			'series'	=> $series,
			'names'		=> $names,
			'id'		=> 'pushes'
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
			'title'		=> sprintf('<h4 class="path"><a href="/dashboard/%s">Аналитика</a> > Push-уведомления</h4>', $filters['id_map']),
			'graphType'	=> 'column',
			'series'	=> $series,
			'names'		=> $names,
			'name'		=> 'Push-уведомления',
			'filters'	=> $this->filterForm,
		))->run();
	}
}
