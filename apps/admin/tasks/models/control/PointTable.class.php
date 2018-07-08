<?php

class PointTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_point',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_point' });
    $('.field_id_point').click(function () {
        TableFormActions.getForm('point', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Point');

        $this->addColumn('id_point', 'ID', 'string');
        $this->addColumn('dt', 'Дата регистрации', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('code', 'Код', 'string');
        $this->addColumn('region', 'Регион', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('address', 'Адрес', 'string');
        $this->addColumn('dealer', 'Дилер', 'string');
        $this->addColumn('meta_filial', 'Мета филиал', 'string');
        $this->addColumn('filial', 'Филиал', 'string');
//        $this->addColumn('cnt_member', 'Количество Участников', 'string');
  
        $this->setFilterForm(new PointFilterForm($this->context));
    }

}
