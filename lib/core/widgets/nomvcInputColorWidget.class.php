<?php

class nomvcInputColorWidget extends nomvcInputWidget
{

    public function renderControl($value, $attributes = array())
    {
        $attributes = array_merge($this->getAttributes(), $attributes);
        $attributes['class'] = implode(' ', array($attributes['class'], 'text-right'));
        if ($value) $attributes['value'] = '#' . str_pad(dechex($value), 6, '0');
        $attributesCompiled = $this->compileAttribute($attributes);
        return sprintf("<input %s></input><script>$('#%s').colorpicker();</script>",
            implode(' ', $attributesCompiled), $attributes['id'], $this->getOption('accuracy'));
    }

    protected function init()
    {
        parent::init();
        $this->setAttribute('type', 'text');
    }
}
