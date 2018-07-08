<?php
/**
 * Description of NewsTable

 */
class RestaurantsTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_restaurant',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_route' });
	$('.field_id_route').click(function () {
		TableFormActions.getForm('route', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Restaurants');
        $this->addColumn('id_restaurant', '5ID', 'integer');
       // $this->addColumn('name',		'Название',			'string');
      //  $this->addColumn('object_list',		'Подчинённые объекты',		'string');
       // $this->addColumn('is_display_nme',	'Опубликовано?',		'string');
       // $this->addColumn('is_edited_nme',	'Редактирование пользователем?','string');
       // $this->addColumn('author',		'Автор',			'string');


        $this->setFilterForm(new RestaurantsFilterForm($this->context));
    }

}
