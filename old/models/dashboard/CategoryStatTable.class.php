<?php

class CategoryStatTable extends AbstractMapObjectTable {

	public function init($options = array()) {
		$options = array(
		    'sort_by' => 'cnt_member',
		    'sort_order' => 'desc',
		);
		parent::init($options);

		$this->setRowModelClass('CategoryStat');
		$this->setFilterForm(new CategoryStatFilterForm($this->context));
	}
	
	public function prepareData($data, $params) {
		$series = array(array('name' => 'Интересы', 'data' => array()));
		$names = array();
		foreach ($data as $row) {
			$names[] = $row->name;
			$series[0]['data'][] = intval($row->cnt_member);
		}
		return array($names, $series);
	}
	
	public function runForDashboard($params) {
		$criteria = new Criteria();
		$criteria->addContext('this_id_map',	$params['id_map']);
		$criteria->addContext('id_mobile_os',	$params['id_mobile_os']);
		$criteria->addContext('id_sex',			$params['id_sex']);
		$criteria->addContext('id_age_range',	$params['id_age_range']);
		$criteria->setOrderBy('cnt_member desc');
		
		$data = $this->context->getModelFactory()->select($this->rowModelClass, $criteria, $this->fetchByClass);		
		$data = array_chunk($data, 5, true);
		if (is_array($data) && count($data) > 0) {
			list($data) = $data;
		} else {
			$data = array();
		}
		list($names, $series) = $this->prepareData($data, $params);
		$generator = new OutputGenerator($this->context, $this->controller);		
							
		// собственно рендерим
		return $generator->prepare('component/graph_dashboard', array(
			'title'		=> sprintf('<a href="/stat/category-stat/%s">Интересы</a>', $params['id_map']),
			'name'		=> 'Интересы',
			'graphType'	=> 'bar',
			'series'	=> $series,
			'names'		=> $names,
			'id'		=> 'categories'
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
			'title'		=> sprintf('<h4 class="path"><a href="/dashboard/%s">Аналитика</a> > Интересы</h4>', $filters['id_map']),
			'graphType'	=> 'bar',
			'series'	=> $series,
			'names'		=> $names,
			'name'		=> 'Интересы',
			'filters'	=> $this->filterForm,
		))->run();
	}
}
