<?php

class AboutTable extends AbstractMapObjectTable {
    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_about',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_about' });
	$('.field_id_about').click(function () {
		TableFormActions.getForm('about', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('About');

        $this->addColumn('id_about', 'ID', 'integer');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('short_description', 'Краткое описание', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_public', 'Дата публикации', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new AboutFilterForm($this->context));
    }

}
