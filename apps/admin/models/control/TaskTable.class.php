<?php

class TaskTable extends AbstractMapObjectTable {

    public function init($options = array()) {
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

        $this->addColumn('id_task', 'ID', 'string');
        $this->addColumn('dt_task', 'Дата задания', 'date', array('format' => DateHelper::HTMLD_FORMAT));
        $this->addColumn('name', 'Название', 'string');
        $this->addColumn('cnt_pharmacy', 'Количество аптек', 'string');
        $this->addColumn('cnt_member', 'Количество мерчендайзеров', 'string');
        $this->addColumn('detail', 'Детально', 'string', array(), array('class' => 'rowlink-skip'));
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new TaskFilterForm($this->context));
    }

}
