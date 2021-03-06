<?php

/**
 * Контроллер отвечающий за меню статистики и определение типа статистики
 */
class StatController extends nomvcBaseControllerTwo
{

    /** наследуется из nomvcBaseController */
    public function run()
    {
        $tableClass = $this->getTable();
        //var_dump($tableClass); exit;
        $this->table = new $tableClass($this->context, $this);
        $generator = new OutputGenerator($this->context, $this);
        $menu = new MenuController($this->context, $this);
        $tableOutput = $this->table->run();
        $outputMode = $this->table->getOutputMode();
        if ($outputMode == OutputGenerator::MODE_HTML) {
            return $generator->prepare('main', array(
                'menu' => $menu->run(),
                'content' => $tableOutput,
            ))->run();
        } else {
            return $tableOutput;
        }
    }

    /**
     * Метод, возвращающий модель по адресу
     */
    protected function getTable()
    {
        //куда хотим попасть
        $module = $this->getCurrentUriPart();


        if ($module == null) {
            $module = $this->context->getUser()->getModuleDefault();

            if ($module != null) {
                $this->redirect("{$this->baseUrl}$module");
            } else
                $this->redirect("{$this->baseUrl}/logout");
        } else {
            $access_type = $this->context->getUser()->checkAccess($module);

            if ($access_type == 0) {
                $module = $this->context->getUser()->getModuleDefault();

                if ($module != null)
                    $this->redirect("{$this->baseUrl}$module");
                else
                    $this->redirect("{$this->baseUrl}/logout");
            }
        }

        switch ($module) {
            case 'member':
                return 'MemberTable';
                break;
            case 'pharmacy':
                return 'PharmacyTable';
                break;
            case 'sku':
                return 'SkuTable';
                break;
            case 'task':
                return 'TaskTable';
                break;
            case 'task-detail':
                return 'TaskDetailTable';
                break;
            case 'task-pharmacy-detail':
                return 'TaskPharmacyDetailTable';
                break;
            case 'sku-stat':
                return 'SkuStatTable';
            case 'push':
                return 'PushTable';
                break;
            default:
                return false;
        }
    }

    /** наследуется из nomvcBaseController */
    public function makeUrl($full = false)
    {
        $model = $this->getCurrentUriPart();

        $key = 'session_user_data/stat/' . $this->getTable() . '/filters';
        if ($full && isset($_SESSION[$key]) && isset($_SESSION[$key]['id_map'])) {
            return $this->parentController->makeUrl() . '/stat/' . $model . '/' . $_SESSION[$key]['id_map'];
        }
        return $this->parentController->makeUrl() . '/stat/' . $model;
    }

    /** наследуется из nomvcBaseController */
    protected function init()
    {
        parent::init();
    }
}
