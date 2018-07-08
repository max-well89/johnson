<?

class ParkingTariffTable extends AbstractMapObjectTable {
    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_parking_tariff',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
	$('.rowlink').rowlink({ target: '.field_id_parking_tariff' });
	$('.field_id_parking_tariff').click(function () {
		TableFormActions.getForm('parking-tariff', $(this).closest('tr').attr('row-id'));
	});
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('ParkingTariff');

        $this->addColumn('id_parking_tariff', 'ID', 'integer');
        $this->addColumn('name', 'Название', 'string');
        //$this->addColumn('time_interval', 'Время', 'string');
        //$this->addColumn('val', 'Стоимость', 'string');
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');
        
        $this->setFilterForm(new ParkingTariffFilterForm($this->context));
    }
}

?>
