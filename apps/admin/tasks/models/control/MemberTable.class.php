<?php
/**
 * Description of NewsTable
 */
class MemberTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'ID_MEMBER',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_member' });
    $('.field_id_member').click(function () {
        TableFormActions.getForm('member', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Member');

        $this->addColumn('id_member', 'ID', 'string');
        $this->addColumn('surname', 'Фамилия', 'string');
        $this->addColumn('name', 'Имя', 'string');
        $this->addColumn('region', 'Регион', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('area', 'Район', 'string');
//        $this->addColumn('msisdn', 'Телефон', 'string');
//        $this->addColumn('email', 'Email', 'string');
        $this->addColumn('login', 'Логин', 'string');
        $this->addColumn('passwd', 'Пароль', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new MemberFilterForm($this->context));
    }

}
