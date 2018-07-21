<?php

class TranslateHelper
{
    protected $context;

    protected $available_locales = array(
        'en',
        'ru'
    );

    public function __construct($context, $locale = null)
    {
        $this->context = $context;
        $this->setLocale($locale);
        $baseLangDir = NOMVC_BASEDIR . '/apps/admin/i18n/';
        $translate = realpath($baseLangDir . $this->getLocale() . '/' . 'messages.xml');
        $loader = new Symfony\Component\Translation\Loader\XliffFileLoader();
        $this->catalogue = $loader->load($translate, $this->getLocale());
    }

    public function setLocale($locale)
    {
        if ($locale && in_array($locale, $this->available_locales))
            $this->context->getUser()->setAttribute('lang', $locale);
    }

    protected function getLocale()
    {
        return $this->context->getUser()->getAttribute('lang');
    }

    public function translate($source)
    {
        return $this->catalogue->get($source);
    }
}