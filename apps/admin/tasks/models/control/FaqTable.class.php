<?php

class FaqTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_faq',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_faq' });
    $('.field_id_faq').click(function () {
        TableFormActions.getForm('faq', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Faq');

        $this->addColumn('id_faq', 'ID', 'string');
        $this->addColumn('faq_group', 'Группа', 'string');
        $this->addColumn('name', 'Заголовок', 'string');
        //$this->addColumn('description', 'Описание', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');
        
        $this->setFilterForm(new FaqFilterForm($this->context));
    }

}
