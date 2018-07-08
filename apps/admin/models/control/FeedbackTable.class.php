<?php

class FeedbackTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_feedback',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_feedback' });
    $('.field_id_feedback').click(function () {
        TableFormActions.getForm('feedback', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Feedback');

        $this->addColumn('id_feedback', 'ID', 'string');
        $this->addColumn('type_member', 'Тип пользователя', 'string');
        $this->addColumn('dt', 'Дата сообщения', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('method', 'Способ связи', 'string');
        $this->addColumn('fio', 'ФИО', 'string');
        $this->addColumn('msisdn', 'Телефон для участия', 'string');
        $this->addColumn('email', 'Контактный email', 'string');
        $this->addColumn('msisdn_two', 'Контактный телефон', 'string');
        $this->addColumn('question', 'Вопрос', 'string');
        $this->addColumn('answer', 'Ответ', 'string');
        $this->addColumn('dt_status', 'Дата присвоения текущего статуса', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');
        $this->addColumn('member_status', 'Пользователь CMS', 'string');
        
        $this->setFilterForm(new FeedbackFilterForm($this->context));
    }

}
