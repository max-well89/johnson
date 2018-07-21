<?php

class SelectBeaconsWidget extends nomvcInputWidget {

	protected function init() {
		parent::init();
		$this->addOption('helper', true, false);
		$this->addOption('map-width', false, 560);
		$this->addOption('map-height', false, 560);
		$this->addOption('id_map', false, false);
		$this->addOption('mapkey', false, 'PROD');
	}
	
	public function renderForForm($formName, $value = null) {
		$id = sprintf('%s_%s', $formName, $this->getName());
		$name = sprintf('%s[%s]', $formName, $this->getName());
	
		$dbHelper = $this->getOption('helper');
		$dbHelper->addQuery(get_class($this).'/get-raster', 'select width, height from t_map_raster where id_map = :id_map and mapkey = :mapkey');
		list($rasterWidth, $rasterHeight) = $dbHelper->selectRow(get_class($this).'/get-raster', array(
			'id_map' => $this->getOption('id_map'),
			'mapkey' => $this->getOption('mapkey')));
						
		return sprintf(<<<EOF
<div id="form_group_%s" class="container-fluid form-group%s" style="display: none;"><div class="map"></div></div>
<input type="hidden" id="%s" name="%s" value="%s">
<style>

.map {
	width: %spx;
	height: %spx;
}

.map svg {
	width: 100%%;
	height: 100%%;
}

.map svg image.beacon {
	cursor: pointer;
}

</style>

<script>

$('.map').svg({
	onLoad: function(svg) {
		Beacon = Beacon4Select;
		Beacon4Select.widgetId = '%s';
	
		Map.svg = svg;
		Map.rasterWidth = %s;
		Map.rasterHeight = %s;
		Map.id_map = '%s';
		Map.mapkey = '%s';
		
		Map.view.beacons = true;
		
		Map.init();
	}
});

$('#push_id_target').change(function() {
	if (Number($(this).val()) == 3) {
		$('#form_group_beacons').show();
	} else {
		$('#form_group_beacons').hide();
	}
	Map.redraw();
});
$('#push_id_target').change();

</script>

EOF
			, $this->getName(), $this->getOption('has-error', false) ? ' has-error' : '',
			$id, $name, $value, $this->getOption('map-width'), $this->getOption('map-height'),
			$id, $rasterWidth, $rasterHeight, $this->getOption('id_map'), $this->getOption('mapkey'));
	}
}


