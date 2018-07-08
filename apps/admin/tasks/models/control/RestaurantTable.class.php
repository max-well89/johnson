<?php
/**
 * Description of NewsTable

 */
class RestaurantTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_restaurant',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_restaurant' });
	$('.field_id_restaurant').click(function () {
		TableFormActions.getForm('restaurant', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Restaurant');

        $this->addColumn('id_restaurant', 'ID', 'integer');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('address', 'Адрес', 'string');
        $this->addColumn('restaurant_type', 'Формат ресторана', 'string');
        $this->addColumn('cnt_member_all', 'Количество сотрудников', 'string');
        $this->addColumn('cnt_member', 'Количество участников ПЛ', 'string');
        $this->addColumn('cnt_point', 'Количество набранных баллов', 'number', array('format' => '%0.2f'));
        $this->addColumn('rating', 'Рейтинг', 'string');
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new RestaurantFilterForm($this->context));
    }

}
