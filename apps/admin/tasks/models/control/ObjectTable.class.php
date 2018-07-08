<?php
/**
 * Description of ActionsTable
 *
 * @author sefimov
 */
class ObjectTable extends AbstractMapObjectTable {
	public function init($options = array()) {

		$options = array(
		    'sort_by' => 'dt_created',
		    'sort_order' => 'desc',
		    'rowlink' =>
<<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_object' });
	$('.field_id_object').click(function () {
		TableFormActions.getForm('object', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
		);

		parent::init($options);

		$this->setRowModelClass('Object');

		$this->addColumn('id_object',		'ID',				'integer');
		$this->addColumn('object_name',		'Название',			'string');
		$this->addColumn('dt_created',		'Дата создания',		'date',		array('format' => DateHelper::HTMLDTS_FORMAT));
		$this->addColumn('cat_name',		'Категория',			'string');
		$this->addColumn('tpe_name',		'Тип',				'string');
		$this->addColumn('city_name',		'Город',			'string');
		$this->addColumn('gmc',			'GMC',				'string');
		$this->addColumn('opening_times',	'Графика работы',		'string');
		$this->addColumn('address',		'Адрес',			'string');
		$this->addColumn('email_list',		'Почта',			'string');
		$this->addColumn('msisdn_list',		'Телефон',			'string');
		$this->addColumn('url_list',		'WEB',				'string');
		$this->addColumn('author_name',		'Автор',			'string');
		$this->addColumn('status_name',		'Статус',			'string');

		$this->setFilterForm(new ObjectFilterForm($this->context));
	}
}
