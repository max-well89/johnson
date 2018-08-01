<?php

class TaskPharmacyDetailTable extends AbstractMapObjectTable
{

    public function init($options = array())
    {
        $options = array(
            'sort_by' => 'id_task_data',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_task_data' });
    $('.field_id_task_data').click(function () {
        TableFormActions.getForm('task-pharmacy-detail', $(this).closest('tr').attr('row-id'));
    });
</script>
EOF
        );

        parent::init($options);

        $this->setRowModelClass('TaskPharmacyDetail');

        $this->addColumn('id_task_data', 'id', 'string');
        //$this->addColumn('id_task', 'ID задания', 'string');
        //$this->addColumn('name', 'Название', 'string');
        //       $this->addColumn('id_pharmacy', 'ID аптеки', 'string');
        //      $this->addColumn('category', 'Категория', 'string');
        $this->addColumn('pharmacy', 'pharmacy_name', 'string');
        //  $this->addColumn('address', 'Адрес аптеки', 'string');
        //$this->addColumn('region', 'Регион', 'string');
        // $this->addColumn('city', 'Город', 'string');
        //$this->addColumn('area', 'Район', 'string');
        $this->addColumn('fio', 'merchandiser', 'string');
        $this->addColumn('id_sku', 'id_sku', 'string');
        $this->addColumn('name', 'sku', 'string');
        $this->addColumn('sku_type', 'sku_type', 'string');
        $this->addColumn('sku_producer', 'sku_producer', 'string');
        $this->addColumn('my_value', 'my_value', 'string');
        $this->addColumn('rest_cnt', 'rest_cnt', 'string');
        $this->addColumn('illiquid_cnt', 'illiquid_cnt', 'string');
        $this->addColumn('action_status_'.Context::getInstance()->getUser()->getLanguage(), 'action_status', 'string');
        $this->addColumn('comment', 'comment', 'string');
        $this->addColumn('dt', 'dt', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));

        $this->setFilterForm(new TaskPharmacyDetailFilterForm($this->context));

        if (
            $this->context->getRequest()->getParameter('id_task') &&
            $this->context->getRequest()->getParameter('id_pharmacy') &&
            $this->context->getRequest()->getParameter('id_member')
        ) {
            $id_task = (int)$this->context->getRequest()->getParameter('id_task');
            $id_pharmacy = (int)$this->context->getRequest()->getParameter('id_pharmacy');
            $id_member = (int)$this->context->getRequest()->getParameter('id_member');

            $filters = $this->getFilters();
            $filters['id_task'] = $id_task;
            $filters['id_pharmacy'] = $id_pharmacy;
            $filters['id_member'] = $id_member;

            $this->setFilters($filters);

            //$this->setDBContextParameter('id_task_mp', $id_task_mp);
        }
    }

    protected function setFilterForm($filterForm)
    {
        $this->filterForm = $filterForm;
        $this->filterForm->setAttribute('method', 'post');
        $this->filterForm->setAttribute('action', $this->controller->makeUrl() . '/filter/');
        $this->filterForm->setAttribute('reset', $this->controller->makeUrl() . '/filter/reset/');
        $this->filterForm->setAttribute('export', $this->controller->makeUrl() . '/export/xls');
        $filters = $this->getFilters();

        if ($this->context->getRequest()->getParameter('id_task') &&
            $this->context->getRequest()->getParameter('id_pharmacy') &&
            $this->context->getRequest()->getParameter('id_member')
        ) {
            $id_task = (int)$this->context->getRequest()->getParameter('id_task');
            $id_pharmacy = (int)$this->context->getRequest()->getParameter('id_pharmacy');
            $id_member = (int)$this->context->getRequest()->getParameter('id_member');

            $filters['id_task'] = $id_task;
            $filters['id_pharmacy'] = $id_pharmacy;
            $filters['id_member'] = $id_member;

            $this->filterForm->setAttribute('reset', $this->controller->makeUrl() . "/filter/reset/?id_task={$id_task}&id_pharmacy={$id_pharmacy}&id_member={$id_member}");
        }

        $this->filterForm->bind($filters);
    }

    /** выполняет различные действия, такие как сортировка/лимиты и проч. */
    public function doAction()
    {
        // готовимся внимать тому, чего от нас хотят
        $uri = $this->controller->getNextUri();
        $action = explode('/', $uri);
        $action = isset($action[1]) ? $action[1] : '';
        switch ($action) {
            case 'sort':    // смена сортировки
                if (preg_match('/\/sort\/([^\/]*)\/(asc|desc)/imu', $uri, $match)) {
                    if (isset($this->columns[$match[1]]) && !in_array($this->columns[$match[1]]['type'], array('custom'))) {
                        $this->setSortBy($match[1]);
                        $this->setSortOrder($match[2]);
                    }
                }
                break;
            case 'page':    // пейджинг
                if (preg_match('/\/page\/(\d+)/imu', $uri, $match)) {
                    $page = $match[1];
                    if ($page > 0) {
                        $this->setOffset($this->getLimit() * ($page - 1));
                    }
                }
                break;
            case 'limit':    // установка нового ограничения строк на страницу
                if (preg_match('/\/limit\/(\d+)/imu', $uri, $match)) {
                    $limit = $match[1];
                    if ($limit > 0) {
                        $offset = floor($this->getOffset() / $limit) * $limit;
                        $this->setOffset($offset);
                        $this->setLimit($limit);
                    }
                }
                break;
            case 'filter':    // фильтрация данных
                $form_data = $this->context->getRequest()->getParameter('filters');
                if (preg_match('/\/filter\/reset/imu', $uri, $match)) {    // сброс фильтров

                    if (
                        $this->context->getRequest()->getParameter('id_task') &&
                        $this->context->getRequest()->getParameter('id_pharmacy') &&
                        $this->context->getRequest()->getParameter('id_member')
                    ) {
                        $id_task = (int)$this->context->getRequest()->getParameter('id_task');
                        $id_pharmacy = (int)$this->context->getRequest()->getParameter('id_pharmacy');
                        $id_member = (int)$this->context->getRequest()->getParameter('id_member');

                        $filters['id_task'] = $id_task;
                        $filters['id_pharmacy'] = $id_pharmacy;
                        $filters['id_member'] = $id_member;

                        $this->setFilters($filters);
                        $this->controller->redirect($this->controller->makeUrl() . "/?id_task={$id_task}&id_pharmacy={$id_pharmacy}&id_member={$id_member}");
                    } else {
                        $this->setFilters(array());
                        $this->controller->redirect($this->controller->makeUrl());
                    }
                } elseif ($form_data) {    // установка фильтров
                    $this->applyFilters($form_data);
                } else {    // непонятно чего от нас хотят
                    $this->controller->redirect($this->controller->makeUrl());
                }
                break;
            case 'export':    // экспорт данных
                if (preg_match('/\/export\/(xls|csv)/imu', $uri, $match)) {
                    $this->export = $match[1];
                } else {
                    $this->controller->redirect($this->controller->makeUrl());
                }
                break;
            default:
                $action .= 'Action';
                $classMethods = get_class_methods($this);
                if (in_array($action, $classMethods)) {
                    $this->$action();
                }
        }
    }

    /** применение фильтров */
    public function applyFilters($values)
    {
        $model = $this->rowModelClass;
        $filters = $this->getFilters();

        if ($this->filterForm->validate($values)) {
            $filters = $this->filterForm->getValues();
            $this->setFilters($filters);

            if (
                isset($filters['id_task']) &&
                isset($filters['id_pharmacy']) &&
                isset($filters['id_member'])
            ) {
                $id_task = $filters['id_task'];
                $id_pharmacy = $filters['id_pharmacy'];
                $id_member = $filters['id_member'];

                $this->controller->redirect($this->controller->makeUrl() . "/?id_task={$id_task}&id_pharmacy={$id_pharmacy}&id_member={$id_member}");
            } else
                $this->controller->redirect($this->controller->makeUrl());
        }
    }
}
