<?php

/**
 * контроллер меню
 */
class MenuController extends nomvcBaseControllerTwo
{
    /** @var dbHelper Хелпер базы данных */
    //private $dbHelper;
    /** @var array Массив с пунктами меню */
    private $menu;

    public function run()
    {
        $this->menu = array();
        $generator = new OutputGenerator($this->context, $this);
        $this->menu = $this->getMenuPoint();

        return $generator->prepare('component/menu', array(
            'menu' => $this->menu,
            'current' => $this->parentController->makeUrl(true),
        ))->run();
    }

    /**
     * Возвращает массив пунктов меню
     */
    private function getMenuPoint()
    {
        $menu = array();
        $this->dbHelper->addQuery(get_class($this) . '/get-menu', '
            select 
            mdl.id_module, 
            mdl.name_' . Context::getInstance()->getUser()->getAttribute("lang") . ' as name, 
            mdl.module, 
            mdl.path
            from t_module mdl
            inner join t_module_role mdlr on mdlr.id_module = mdl.id_module
            inner join t_member_role mbrl on mdlr.id_role = mbrl.id_role
            where mbrl.id_member = :id_member
            and mdlr.is_hidden_in_menu = 0
            order by mdl.order_by_module
        ');
        $stmt = $this->dbHelper->select(get_class($this) . '/get-menu', array(":id_member" => $this->user->getUserID()));

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row = array_change_key_case($row, CASE_LOWER);
            $menu[$row['name']] = "{$this->baseUrl}" . $row['path'];
        }

        return $menu;
    }

    protected function init()
    {
        parent::init();
        //$this->dbHelper = $this->context->getDbHelper();
        $this->menu = array();
    }

    protected function makeUrl()
    {
        return '';
    }

}
