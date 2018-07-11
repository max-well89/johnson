<?php

class TaskDetailTable extends AbstractMapObjectTable {

    public function init($options = array()) {
        $options = array(
            'sort_by' => 'id_task_mp',
            'sort_order' => 'desc',
            'rowlink' => <<<EOF
<script>
    $('.rowlink').rowlink({ target: '.field_id_task_mp' });
    $('.field_id_task_mp').click(function () {
        //var rowId = $(this).closest('tr').attr('row-id');
        //TableFormActions3.getForm('task-detail', rowId);
        
        TableFormActions.getForm('task-detail', $(this).closest('tr').attr('row-id'));
    });
    
    $(document).ready(function() {
        var checkBatchPanel = function() {
            if ($('.batch_select_row:checked').length > 0) {
                if ($(document).height() - $(window).scrollTop() - $(window).height() > 100) {
                        $('#batch-panel').addClass('fixed-batch-panel');
                } else {
                        $('#batch-panel').removeClass('fixed-batch-panel');
                }
            } else {
                $('#batch-panel').removeClass('fixed-batch-panel');
            }
        };

        $(window).bind("scroll", checkBatchPanel);
        $('.batch_select_row').bind("change", checkBatchPanel);
        $('.batch_select_all').bind("change", checkBatchPanel);
    });
</script>

EOF
        );

        parent::init($options);
        $this->setRowModelClass('TaskDetail');
        $this->setFetchByClass(false);

        $this->addColumn('id_task_mp', 'ID', 'string');
        $this->addColumn('id_task', 'ID задания', 'string');
        //$this->addColumn('name', 'Название', 'string');
        $this->addColumn('id_pharmacy', 'ID аптеки', 'string');
        $this->addColumn('category', 'Категория', 'string');
        $this->addColumn('pharmacy', 'Название аптеки', 'string');
        $this->addColumn('address', 'Адрес аптеки', 'string');
        $this->addColumn('region', 'Регион', 'string');
        $this->addColumn('city', 'Город', 'string');
        $this->addColumn('area', 'Район', 'string');
        $this->addColumn('fio', 'Мерчендайзер', 'string');
        //$this->addColumn('action_status', 'Идет акция?', 'string');
        //$this->addColumn('comment', 'Комментарий к аптеке', 'string');
        $this->addColumn('detail', 'Детально', 'string', array(), array('class' => 'rowlink-skip'));
        $this->addColumn('dt', 'Дата создания', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('dt_status', 'Дата статуса', 'date', array('format' => DateHelper::HTMLDTS_FORMAT));
        $this->addColumn('status', 'Статус', 'string');

        if ($id_task = $this->context->getRequest()->getParameter('id_task')) {
            $id_task = (int) $id_task;
            $filters = $this->getFilters();
            $filters['id_task'] = $id_task;
            $this->setFilters($filters);

            //$this->setDBContextParameter('id_task', $id_task);
        }

        $this->setFilterForm(new TaskDetailFilterForm($this->context));
        $this->setBatchActions(new TaskDetailBatchActions($this->context));
    }

    protected  function setFilterForm($filterForm) {
        $this->filterForm = $filterForm;
        $this->filterForm->setAttribute('method', 'post');
        $this->filterForm->setAttribute('action', $this->controller->makeUrl().'/filter/');
        $this->filterForm->setAttribute('reset', $this->controller->makeUrl().'/filter/reset/');
        $this->filterForm->setAttribute('export', $this->controller->makeUrl().'/export/xls');
        $filters = $this->getFilters();

        if ($id_task = $this->context->getRequest()->getParameter('id_task')) {
            $filters['id_task'] = (int) $id_task;
            $this->filterForm->setAttribute('reset', $this->controller->makeUrl().'/filter/reset/?id_task='.$filters['id_task']);
        }

        $this->filterForm->bind($filters);
    }

    /** применение фильтров */
    public function applyFilters($values) {
        $model = $this->rowModelClass;
        $filters = $this->getFilters();

        if ($this->filterForm->validate($values)) {
            $filters = $this->filterForm->getValues();
            $this->setFilters($filters);

            if (isset($filters['id_task']))
                $this->controller->redirect($this->controller->makeUrl().'/?id_task='.$filters['id_task']);
            else
                $this->controller->redirect($this->controller->makeUrl());
        }
    }

    /** выполняет различные действия, такие как сортировка/лимиты и проч. */
    public function doAction() {
        // готовимся внимать тому, чего от нас хотят
        $uri = $this->controller->getNextUri();
        $action = explode('/', $uri); $action = isset($action[1]) ? $action[1] : '';
        switch($action) {
            case 'sort':	// смена сортировки
                if (preg_match('/\/sort\/([^\/]*)\/(asc|desc)/imu', $uri, $match)) {
                    if(isset($this->columns[$match[1]]) && !in_array($this->columns[$match[1]]['type'], array('custom'))) {
                        $this->setSortBy($match[1]);
                        $this->setSortOrder($match[2]);
                    }
                }
                break;
            case 'page':	// пейджинг
                if (preg_match('/\/page\/(\d+)/imu', $uri, $match)) {
                    $page = $match[1];
                    if ($page > 0) {
                        $this->setOffset($this->getLimit() * ($page - 1));
                    }
                }
                break;
            case 'limit':	// установка нового ограничения строк на страницу
                if (preg_match('/\/limit\/(\d+)/imu', $uri, $match)) {
                    $limit = $match[1];
                    if ($limit > 0) {
                        $offset = floor($this->getOffset() / $limit) * $limit;
                        $this->setOffset($offset);
                        $this->setLimit($limit);
                    }
                }
                break;
            case 'filter':	// фильтрация данных
                $form_data = $this->context->getRequest()->getParameter('filters');
                if (preg_match('/\/filter\/reset/imu', $uri, $match)) {	// сброс фильтров

                    if ($id_task = $this->context->getRequest()->getParameter('id_task')){
                        $id_task = (int) $id_task;
                        $this->setFilters(array('id_task' => $id_task));
                        $this->controller->redirect($this->controller->makeUrl().'/?id_task='.$id_task);
                    }
                    else {
                        $this->setFilters(array());
                        $this->controller->redirect($this->controller->makeUrl());
                    }
                } elseif ($form_data) {	// установка фильтров
                    $this->applyFilters($form_data);
                } else {	// непонятно чего от нас хотят
                    $this->controller->redirect($this->controller->makeUrl());
                }
                break;
            case 'export':	// экспорт данных
                if (preg_match('/\/export\/(xls|csv)/imu', $uri, $match)) {
                    $this->export = $match[1];
                } else {
                    $this->controller->redirect($this->controller->makeUrl());
                }
                break;
            default:
                $action.= 'Action';
                $classMethods = get_class_methods($this);
                if (in_array($action, $classMethods)) {
                    $this->$action();
                }
        }
    }
}
