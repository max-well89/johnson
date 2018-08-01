<?php

class TaskTable extends AbstractMapObjectTable
{

    public function init($options = array())
    {
        $options = array(
            'sort_by' => 'id_task',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_task' });
    $('.field_id_task').click(function () {
        TableFormActions.getForm('task', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Task');

        $this->addColumn('id_task', 'id', 'string');
        $this->addColumn('dt_task', 'dt_task', 'date', array('format' => DateHelper::HTMLD_FORMAT));
        $this->addColumn('name', 'name', 'string');
        $this->addColumn('cnt_pharmacy', 'cnt_pharmacy', 'string');
        $this->addColumn('cnt_member', 'cnt_member', 'string');
        $this->addColumn('detail', 'detail', 'string', array(), array('class' => 'rowlink-skip'));
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status_'.Context::getInstance()->getUser()->getLanguage(), 'status', 'string');

        $this->setFilterForm(new TaskFilterForm($this->context));
    }

}
