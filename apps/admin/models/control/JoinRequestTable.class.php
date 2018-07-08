<?php
/**
 * Description of NewsTable
 */
class JoinRequestTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_join_request',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_join_request' });
    $('.field_id_join_request').click(function () {
        TableFormActions.getForm('join-request', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('JoinRequest');

        $this->addColumn('id_join_request', 'ID', 'string');
        $this->addColumn('fio', 'ФИО', 'string');
        $this->addColumn('msisdn', 'Телефон для участия', 'string');
        $this->addColumn('dt', 'Дата поступления запроса', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('id_dealer', 'ID Дилера', 'string');
        $this->addColumn('name_dealer', 'Дилер', 'string');
        $this->addColumn('meta_filial', 'Мета филиал', 'string');
        $this->addColumn('filial', 'Филиал', 'string');
        $this->addColumn('name_of_sales', 'Имя и фамилия торгового представителя МегаФон', 'string');
        $this->addColumn('dt_status', 'Дата присвоения текущего статуса', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');
        $this->addColumn('member_status', 'Пользователь CMS', 'string');

        $this->setFilterForm(new JoinRequestFilterForm($this->context));
    }

}
