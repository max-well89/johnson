<?php

class ApiGeneratorController extends agAbstractWebController
{

    public function exec()
    {
        return $this->processTemplate('main');
    }

    public function getContent()
    {
        $page = isset($_GET['p']) ? $_GET['p'] : 'projects';
        switch ($page) {
            case 'projects':
                return $this->processTemplate('projects');
                break;
            default:
                break;
        }


        return '---';
    }

    public function getProjects()
    {
        $config = sfYaml::load($this->context->getDir('config') . '/apilist.yml');
        asort($config['apilist']);
        return $config['apilist'];
    }

    public function getProjectInfo($path)
    {
        $projectConfig = sfYaml::load($path . 'config/context.yml');

        $baseUrl = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $baseUrl .= 's';
        $baseUrl .= '://' . $_SERVER['HTTP_HOST'] . dirname($path);

        $urls = array();
        if (file_exists($path . 'doc.php')) $urls['Документация'] = $baseUrl . 'doc.php';
        if (file_exists($path . 'test.php')) $urls['WEB-тестерер'] = $baseUrl . 'test.php';
        if (file_exists($path . 'json.php')) $urls['JSON - гейт'] = $baseUrl . 'json.php';

        //$path
        return array(
            'project_name' => $projectConfig['all']['project_name'],
            'db_dsn' => $projectConfig['all']['db']['dsn'],
            'db_user' => $projectConfig['all']['db']['user'],
            'log_prefix' => $projectConfig['all']['db']['logPrefix'],
            'urls' => $urls,
        );
    }

    /** Возвращает основной контент страницы * /
     * public function getContent() {
     * $logger = $this->context->getLogger();
     * $page = isset($_GET['p']) ? $_GET['p'] : 'main';
     * $logger->setAction($page);
     * switch($page) {
     * case 'cmd':
     * if (isset($_GET['cmd'])) {
     * $logger->setInput($_GET['cmd']);
     * $actionClass = self::toCamelCase('_'.$_GET['cmd']).'Action';
     * $this->action = new $actionClass($this->context);
     * } else {
     * $page = 'cmdlist';
     * }
     * case 'cmdlist':
     * $template = 'doc_'.$page;
     * break;
     * default:
     * $template = 'doc_main';
     * }
     * return $this->processTemplate($template);
     * }
     */

}
