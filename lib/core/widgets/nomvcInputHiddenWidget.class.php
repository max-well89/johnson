<?php

class nomvcInputHiddenWidget extends nomvcInputWidget {

	protected function init() {
		parent::init();
		$this->setAttribute('type', 'hidden');
	}
	
	public function renderForForm($formName, $value = null) {
		$id = sprintf('%s_%s', $formName, $this->getName());
		$name = sprintf('%s[%s]', $formName, $this->getName());
		
		return $this->renderControl($value, array_merge(array('id' => $id, 'name' => $name), $this->getAttributes()));
	}

    public function renderForFilter($formName, $value = null) {
        $id = sprintf('%s_%s', $formName, $this->getName());
        $name = sprintf('%s[%s]', $formName, $this->getName());

        return sprintf('<div id="form_group_%s" class="form-group%s">%s%s</div>',
            $this->getName(),
            $this->getOption('has-error', false) ? ' has-error' : '',
            '',//$this->renderLabel($id, false),
            $this->renderControl($value, array_merge(array(
                'id' => $id,
                'name' => $name,
                'form-id' => $formName
            ), $this->getAttributes())));
    }

}
