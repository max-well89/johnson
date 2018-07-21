<?php

class DealerTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_dealer',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_dealer' });
    $('.field_id_dealer').click(function () {
        TableFormActions.getForm('dealer', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Dealer');

        $this->addColumn('id_dealer', 'ID', 'string');
        $this->addColumn('dt', 'Дата регистрации', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('meta_filial', 'Мета филиал', 'string');
        $this->addColumn('filial', 'Филиал', 'string');
        $this->addColumn('cnt_point', 'Количество ТТ', 'string');
        $this->addColumn('cnt_member', 'Количество Участников', 'string');
        $this->addColumn('login', 'Логин', 'string');
        $this->addColumn('passwd', 'Пароль', 'string');

        $this->setFilterForm(new DealerFilterForm($this->context));
    }

}
