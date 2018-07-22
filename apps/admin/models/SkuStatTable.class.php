<?php

class SkuStatTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_task_data',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
//    $('.rowlink').rowlink({ target: '.field_id_task_data' });
//    $('.field_id_task_data').click(function () {
//        TableFormActions.getForm('sku-stat', $(this).closest('tr').attr('row-id'));
//    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('SkuStat');

        $this->addColumn('id_task_data', 'ID', 'string');

        $this->addColumn('id_pharmacy', 'id_pharmacy', 'string');
        $this->addColumn('category', 'category', 'string');
        $this->addColumn('pharmacy', 'pharmacy_name', 'string');
        $this->addColumn('address', 'pharmacy_address', 'string');
        $this->addColumn('region', 'region', 'string');
        $this->addColumn('city', 'city', 'string');
        $this->addColumn('area', 'area', 'string');
        $this->addColumn('fio', 'merchandiser', 'string');
        $this->addColumn('id_sku', 'id_sku', 'string');
        $this->addColumn('sku_type', 'sku_type', 'string');
        $this->addColumn('sku_producer', 'sku_producer', 'string');
        $this->addColumn('name', 'name', 'string');
        $this->addColumn('my_value', 'my_value', 'string');
        $this->addColumn('rest_cnt', 'rest_cnt', 'string');
        $this->addColumn('illiquid_cnt', 'illiquid_cnt', 'string');
        $this->addColumn('action_status', 'action_status', 'string');
        $this->addColumn('comment', 'comment', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
//        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new SkuStatFilterForm($this->context));
    }

}
