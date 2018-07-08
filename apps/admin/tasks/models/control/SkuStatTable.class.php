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

        $this->addColumn('id_pharmacy', 'ID аптеки', 'string');
        $this->addColumn('category', 'Категория', 'string');
        $this->addColumn('pharmacy', 'Название аптеки', 'string');
        $this->addColumn('address', 'Адрес аптеки', 'string');
        $this->addColumn('region', 'Регион', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('area', 'Район', 'string');
        $this->addColumn('fio', 'Мерчендайзер', 'string');
        $this->addColumn('id_sku', 'ID SKU', 'string');
        $this->addColumn('sku_type', 'Тип', 'string');
        $this->addColumn('sku_producer', 'Производитель', 'string');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('my_value', 'Цена', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
//        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new SkuStatFilterForm($this->context));
    }

}
