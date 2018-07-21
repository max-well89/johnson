<?php

class PrizeTable extends AbstractMapObjectTable {
    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_prize',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_prize' });
	$('.field_id_prize').click(function () {
		TableFormActions.getForm('prize', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Prize');

        $this->addColumn('id_prize', 'ID', 'integer');
        $this->addColumn('name', 'Название', 'string');
        //$this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('description', 'Текстовое описание', 'string');
        $this->addColumn('price', 'Стоимость в баллах', 'string');
        $this->addColumn('cnt_all', 'Общее количество', 'string');
        $this->addColumn('cnt_spend', 'Разыгранное количество', 'string');
        $this->addColumn('prize_type', 'Категория', 'string');
        $this->addColumn('cnt_like', 'Количество лайков', 'string');
        $this->addColumn('status', 'Статус', 'string');
        
        $this->setFilterForm(new PrizeFilterForm($this->context));
    }

}
