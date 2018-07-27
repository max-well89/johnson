<?php

/**
 * Контроллер для приложения - веб-тестера
 */
class agTestController extends agAbstractWebController
{

    public function exec()
    {
        //$logger = $this->context->getLogger();
        if (isset($_GET['cmd'])) {
            //$logger->setAction($_GET['cmd']);
            switch ($_GET['cmd']) {
                case 'get_users':
                    return json_encode($this->getUsersList());
                case 'get_actions':
                    return json_encode($this->getActionsList());
                case 'get_action_params':
                    //$logger->setInput($_GET['action']);
                    return json_encode($this->getActionParameters($_GET['action']));
                case 'get_action_doc':
                    //$logger->setInput($_GET['action']);
                    $actionClass = self::toCamelCase('_' . $_GET['action']) . 'Action';
                    $this->action = new $actionClass($this->context);
                    return $this->processTemplate('doc_cmd');
                case 'run_json':
                    //$logger->setInput($_POST);
                    $resp = $this->runJson($_POST['request'], $_POST['user']);
                    if ($resp_json = json_decode($resp)) {
                        $printer = new JsonPrettyPrinter();
                        return $printer->format(json_encode($resp_json));
                    }
                    return $resp;
                default:
                    header("HTTP/1.0 404 Not Found");
                    $this->processTemplate('http_404');
                    return;
            }
        } else {
            return $this->processTemplate('test_layout');
        }
    }

    /** получение списка пользователей */
    public function getUsersList()
    {
        $users = sfYaml::load($this->context->getDir('config') . '/users.yml');
        $users = array_keys($users);
        asort($users);
        return $users;
    }

    /** получение списка параметров экшена */
    public function getActionParameters($action)
    {
        $actionClass = self::toCamelCase('_' . $action) . 'Action';
        $action = new $actionClass($this->context);
        $parameters = array();
        foreach ($action->getParameters() as $param => $conf) {
            $validator = $conf['validator'];
            $parameters[] = array(
                'name' => $param,
                'example' => $validator->getExample(),
                'required' => $validator->getOption('required', false),
            );
        }
        return $parameters;
    }

    /** выполнение JSON запроса к API */
    public function runJson($request, $user)
    {
        $users = sfYaml::load($this->context->getDir('config') . '/users.yml');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->context->getApiUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $users[$user]['password']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        return curl_exec($ch);
    }

}
