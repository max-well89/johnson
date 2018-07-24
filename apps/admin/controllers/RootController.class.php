<?php

class RootController extends nomvcBaseControllerTwo
{

    public function __construct($context = null, $parentController = null)
    {
        if ($context) {
            $this->context = $context;
        } else {
            $this->context = Context::getInstance();
        }

        $this->user = $this->context->getUser();
        $this->init();
        $this->parentController = $parentController;
        if ($this->parentController == null) {
            $this->uri = $this->context->getRequest()->getUri();
        } else {
            $this->uri = $this->parentController->getNextUri();
        }

        preg_match('|^' . $this->baseUrl . '/([^/]+)(/(.*))?$|imu', $this->uri, $match);
        $this->currUriPart = isset($match[1]) ? $match[1] : null;
        $this->nextUri = isset($match[2]) ? $match[2] : null;
    }

    protected function init()
    {
        parent::init();
    }

    public function run()
    {
        $user = $this->context->getUser();
        $module = $this->getCurrentUriPart();

        //set language
        if (isset($_POST['action']) &&
            isset($_POST['action']) == 'set_language' &&
            isset($_POST['lang'])
        ) {
            Context::getInstance()->getTranslateHelper()->setLocale($_POST['lang']);
            $this->redirect($this->baseUrl);
        }

        if (!$user->hasAuth()) {
            $controller = new AuthController($this->context, $this);
            return $controller->run();
        }

        //по-умолчанию
        if ($module == null) {
            $this->redirect($this->baseUrl . '/stat');
        }

        switch ($module) {
            case null:
            case 'logout':
                $user->signout();
                $this->redirect($this->baseUrl);
                break;
            case 'stat':
                $controller = new StatController($this->context, $this);
                break;
            case 'backend':
                $controller = new BackendController($this->context, $this);
                break;
            default:
                $controller = new HTTPErrorController($this->context, $this);
                $controller->setErrorCode(404);
        }
        return $controller->run();
    }

    protected function makeUrl()
    {
        return $this->baseUrl;
    }

}
