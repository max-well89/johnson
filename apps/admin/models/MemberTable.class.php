<?php

/**
 * Description of NewsTable
 */
class MemberTable extends AbstractMapObjectTable
{

    public function init($options = array())
    {
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

        $this->addColumn('id_member', 'id', 'string');
        $this->addColumn('name', 'member_name', 'string');
        $this->addColumn('surname', 'member_surname', 'string');
        $this->addColumn('region', 'region', 'string');
        $this->addColumn('city', 'city', 'string');
        $this->addColumn('area', 'area', 'string');
//        $this->addColumn('msisdn', 'Телефон', 'string');
//        $this->addColumn('email', 'Email', 'string');
        $this->addColumn('login', 'login', 'string');
        $this->addColumn('passwd', 'password', 'string');
        $this->addColumn('language', 'language', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'status', 'string');

        $this->setFilterForm(new MemberFilterForm($this->context));
    }

}
