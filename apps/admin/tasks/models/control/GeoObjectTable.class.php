<?php
/**
 * Description of NewsTable

 */
class GeoObjectTable extends AbstractMapObjectTable {

	public function init($options = array()) {
		$options = array(
		    'sort_by' => 'id_type, name',
		    'sort_order' => 'asc',
		    'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_geo_object' });
	$('.field_id_geo_object').click(function () {
		TableFormActions.getForm('geoobject', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
		);

		parent::init($options);

		$this->setRowModelClass('GeoObject');

		$this->addColumn('id_geo_object',	'ID',			'integer');
		$this->addColumn('name',		'Название',		'string');
		$this->addColumn('name_eng',		'Title',		'string');
		$this->addColumn('name_type',		'Тип',			'string');
		$this->addColumn('gmc',			'Координаты',		'string');
		$this->addColumn('slave_object',	'Подчинённые объекты',	'string');
		$this->addColumn('is_display_nme',	'Опубликовано?',	'string');

		$this->setFilterForm(new GeoObjectFilterForm($this->context));
	}

}
