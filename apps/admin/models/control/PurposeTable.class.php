<?php

class PurposeTable extends AbstractMapObjectTable {
    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_purpose',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_purpose' });
	$('.field_id_purpose').click(function () {
		TableFormActions.getForm('purpose', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('Purpose');

        $this->addColumn('id_purpose', 'ID', 'integer');
        $this->addColumn('purpose_type', 'Тип цели', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_from', 'Начало периода', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_to', 'Окончание периода', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('month', 'Месяц для статистики', 'string');
        
        if (!$this->context->getUser()->getAttribute('id_restaurant')){
            $this->addColumn('cnt_result_il', 'Количество ресторанов формата IL', 'string');
            $this->addColumn('cnt_result_fc', 'Количество ресторанов формата FC', 'string');
            $this->addColumn('cnt_result_dt', 'Количество ресторанов формата DT', 'string');
        }
        else {
            $this->addColumn('progress', 'Текущий % выполнения', 'number', array('format' => '%0.2f'));
            //$this->addColumn('base_result', 'Базовый показатель', 'string');
            //$this->addColumn('actual_result', 'Фактический показатель', 'string');
            //$this->addColumn('cnt_response', 'Количество участников', 'string');
            //$this->addColumn('cnt_member', 'Количество участников', 'string');

            // new hotelka
            $this->removeColumn('dt_from');
            $this->removeColumn('dt_to');
            //$this->removeColumn('month');
        }

        $this->addColumn('description', 'Текстовое описание', 'string');
        $this->addColumn('status', 'Статус', 'string');

        $this->setFilterForm(new PurposeFilterForm($this->context));
    }

}
