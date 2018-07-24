<?php

class SkuTable extends AbstractMapObjectTable
{

    public function init($options = array())
    {
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

        $this->addColumn('id_sku', 'id', 'string');
        $this->addColumn('sku_type', 'sku_type', 'string');
        $this->addColumn('sku_producer', 'sku_producer', 'string');
        $this->addColumn('name', 'name', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('priority', 'priority', 'string');
        $this->addColumn('status', 'status', 'string');

        $this->setFilterForm(new SkuFilterForm($this->context));
    }

}
