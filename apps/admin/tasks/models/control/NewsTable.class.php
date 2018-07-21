<?php

class NewsTable extends AbstractMapObjectTable {
    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_news',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_news' });
	$('.field_id_news').click(function () {
		TableFormActions.getForm('news', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('News');

        $this->addColumn('id_news', 'ID', 'integer');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('short_description', 'Краткое описание', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_public', 'Дата публикации', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new NewsFilterForm($this->context));
    }

}
