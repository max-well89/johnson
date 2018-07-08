<?php
/**
 * Description of NewsTable

 */
class TagTable extends AbstractMapObjectTable {

	public function init($options = array()) {
		$options = array(
		    'sort_by' => 'order_by_type, name',
		    'sort_order' => 'asc',
		    'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_tag' });
	$('.field_id_tag').click(function () {
		TableFormActions.getForm('tag', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
		);

		parent::init($options);

		$this->setRowModelClass('Tag');

		$this->addColumn('id_tag',		'ID',			'integer');
		$this->addColumn('name',		'Название',		'string');
		$this->addColumn('name_eng',		'Title',		'string');
		$this->addColumn('is_display_nme',	'Публиковать?',		'string');
		$this->addColumn('order_by_type',	'Сортировка',		'string');

		$this->setFilterForm(new TagFilterForm($this->context));
	}

}
