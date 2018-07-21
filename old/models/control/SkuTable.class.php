<?php

class SkuTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_priority',
            'sort_order' => 'desc nulls last',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_sku' });
    $('.field_id_sku').click(function () {
        TableFormActions.getForm('sku', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Sku');

        $this->addColumn('id_sku', 'ID', 'string');
        $this->addColumn('sku_type', 'Тип', 'string');
        $this->addColumn('sku_producer', 'Производитель', 'string');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('priority', 'Приоритет', 'string');
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new SkuFilterForm($this->context));
    }

}
