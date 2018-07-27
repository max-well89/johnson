<?php

/**
 * Контроллер документации
 */
class agApiDocController extends agAbstractWebController
{

    public function exec()
    {
        return $this->processTemplate('doc_layout');
    }

    /** Возвращает основной контент страницы */
    public function getContent()
    {
        $logger = $this->context->getLogger();
        $page = isset($_GET['p']) ? $_GET['p'] : 'main';
        $logger->setAction($page);
        switch ($page) {
            case 'cmd':
                if (isset($_GET['cmd'])) {
                    $logger->setInput($_GET['cmd']);
                    $actionClass = self::toCamelCase('_' . $_GET['cmd']) . 'Action';
                    $this->action = new $actionClass($this->context);
                } else {
                    $page = 'cmdlist';
                }
            case 'cmdlist':
                $template = 'doc_' . $page;
                break;
            default:
                $template = 'doc_main';
        }
        return $this->processTemplate($template);
    }

}
