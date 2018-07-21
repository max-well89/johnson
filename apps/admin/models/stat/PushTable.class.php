<?

class PushTable extends AbstractMapObjectTable {
    public function init($options = array()) {
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

        $this->addColumn('id_push', 'ID', 'integer');
        $this->addColumn('message', 'Текст сообщения', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_start', 'Время начала отправки', 'date',  array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('cnt_member', 'Количество участников', 'string');
        $this->addColumn('cnt_device', 'Количество устройств', 'string');
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new PushFilterForm($this->context));
    }
}