<?php

class PharmacyTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_pharmacy',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_pharmacy' });
    $('.field_id_pharmacy').click(function () {
        TableFormActions.getForm('pharmacy', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Pharmacy');

        $this->addColumn('id_pharmacy', 'ID', 'string');
        $this->addColumn('id_crm', 'ID CRM', 'string');
        $this->addColumn('category', 'Категория', 'string');
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('address', 'Адрес', 'string');
        $this->addColumn('region', 'Регион', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('area', 'Район', 'string');
        $this->addColumn('fio', 'Мерчендайзер', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_updated', 'Дата обновления', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new PharmacyFilterForm($this->context));
    }

}
