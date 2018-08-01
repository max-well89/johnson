<?php

class PushTable extends AbstractMapObjectTable
{
    public function init($options = array())
    {
        $options = array(
            'sort_by' => 'id_push',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_push' });
    $('.field_id_push').click(function () {
        TableFormActions.getForm('push', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );
        parent::init($options);

        $this->setRowModelClass('Push');

        $this->addColumn('id_push', 'id', 'integer');
        $this->addColumn('message', 'message_text', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_start', 'dt_start_send', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('cnt_member', 'cnt_member', 'string');
        $this->addColumn('cnt_device', 'cnt_device', 'string');
        $this->addColumn('status_'.Context::getInstance()->getUser()->getLanguage(), 'status', 'string');

        $this->setFilterForm(new PushFilterForm($this->context));
    }
}