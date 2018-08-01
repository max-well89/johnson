<?php

class PharmacyTable extends AbstractMapObjectTable
{

    public function init($options = array())
    {
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

        $this->addColumn('id_pharmacy', 'id', 'string');
        $this->addColumn('id_crm', 'id_crm', 'string');
        $this->addColumn('category', 'category', 'string');
        $this->addColumn('name', 'name', 'string');
        $this->addColumn('address', 'address', 'string');
        $this->addColumn('region', 'region', 'string');
        $this->addColumn('city', 'city', 'string');
        $this->addColumn('area', 'area', 'string');
        $this->addColumn('fio', 'merchandiser', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_updated', 'dt_updated', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status_'.Context::getInstance()->getUser()->getLanguage(), 'status', 'string');

        $this->setFilterForm(new PharmacyFilterForm($this->context));
    }

}
