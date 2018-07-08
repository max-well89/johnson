<?php

class nomvcTextareaWidget extends nomvcBaseWidget {
    private $classes = [];
    
    protected function init() {
        parent::init();
        $this->setAttribute('class', 'form-control');
        $this->setAttribute('placeholder', $this->getLabel());
    }
    
    public function renderForForm($formName, $value = null) {
        $id = sprintf('%s_%s', $formName, $this->getName());
        $name = sprintf('%s[%s]', $formName, $this->getName());
        
        return sprintf('<div id="form_group_%s" class="form-group%s">%s<div class="%s">%s</div></div>%s',
            $this->getName(),
            $this->getOption('has-error', false) ? ' has-error' : '',
            $this->renderLabel($id),
            $this->genColumnClass(12 - $this->getOption('label-width')),
            $this->renderControl($value, array_merge(array(
                'id' => $id,
                'name' => $name
            ), $this->getAttributes())),
            $this->getJSHandler($formName));
    }

    //for site render
    public function renderForFormSimple($formName, $value = null) {
        $id = sprintf('%s_%s', $formName, $this->getName());
        $name = sprintf('%s[%s]', $formName, $this->getName());

        $attr = $this->getAttributes();
        $classUnion = $this->getOption('has-error', false) ? ' error' : '';

        foreach ($this->classes as $nameClass => $valClass)
            $classUnion .= ' '.$nameClass;

        $attr['class'] = $classUnion;

        return sprintf('%s',
            $this->renderControl($value, array_merge(array(
                'id' => $id,
                'name' => $name,
                'form-id' => $formName
            ), $attr))
        );
    }

    public function addCssClass($class){
        $this->classes[$class] = $class;
    }

    public function removerCssClass($class){
        if (isset($this->classes[$class]))
            unset($this->classes[$class]);
    }
    
    public function renderForFilter($formName, $value = null) {
        $id = sprintf('%s_%s', $formName, $this->getName());
        $name = sprintf('%s[%s]', $formName, $this->getName());
        
        return sprintf('<div id="form_group_%s" class="form-group%s">%s%s</div>',
            $this->getName(),
            $this->getOption('has-error', false) ? ' has-error' : '',
            $this->renderLabel($id, false),
            $this->renderControl($value, array_merge(array(
                'id' => $id,
                'name' => $name
            ), $this->getAttributes())));
    }
    
    public function renderControl($value, $attributes = array()) {
        $attributes = array_merge($this->getAttributes(), $attributes);
        $attributesCompiled = $this->compileAttribute($attributes);
        return sprintf('<textarea %s>%s</textarea>', implode(' ', 	$attributesCompiled), $value);
    }
    
    public function renderLabel($id, $with_class = true) {
        $attributes = array('for'	=> $id);
        if ($with_class) $attributes['class'] = $this->genColumnClass($this->getOption('label-width')).' control-label';
        $attributesCompiled = $this->compileAttribute($attributes);
        return sprintf('<label %s>%s</label>', implode(' ', $attributesCompiled), $this->getLabel());
    }
    
    protected function genColumnClass($width) {
        return sprintf('col-%s-%s', $this->getOption('size'), $width);
    }
    
    protected function genColumnOffsetClass($width) {
        return sprintf('col-%s-offset-%s', $this->getOption('size'), $width);
    }

}
