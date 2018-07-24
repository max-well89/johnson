<?php

class TaskDetailBatchActions
{
    protected $context = null;
    protected $buttons = array();

    /**
     * Конструктор
     * $context                Контекст выполнения
     */
    public function __construct($context)
    {
        $this->context = $context;
        $this->init();
    }

    public function init()
    {
        $this->addButton(
            new nomvcButtonWidget(
                Context::getInstance()->translate('finished_task'),
                'finished',
                array(
                    'type' => 'button',
                    'icon' => 'glyphicon glyphicon-ok'
                ),
                array('class' => 'btn btn-success')
            )
        );

        $this->addButton(
            new nomvcButtonWidget(
                Context::getInstance()->translate('continue_task'),
                'continue',
                array(
                    'type' => 'button',
                    'icon' => 'glyphicon glyphicon-chevron-right'
                ),
                array('class' => 'btn btn-warning')
            )
        );
    }

    public function addButton($button)
    {
        $this->buttons[$button->getName()] = $button;
    }

    public function render()
    {
        $html = "";
        $form = new TaskDetailForm($this->context, array("id" => "nomenclature"));
        $html = '<div class="form-inline">';
        foreach ($this->buttons as $id => $button) {
            $html .= '<div class="form-group">' . $button->renderControl(null, array('id' => 'batch_action_' . $id)) . '</div>';
        }
        $html .= '</div>' . $this->renderJs();

        return $html;
    }

    public function renderJs()
    {
        return <<<EOF
<script>
    $('.batch_select_all').change(BatchActions.clickSelectAll);
    $('.batch_select_row').change(BatchActions.checkStatus);

    $('#batch_action_finished').click(function () {
        if (confirm('Вы действительно хотите завершить выбранные задачи?')) {
            $.ajax({
                url: '/admin/backend/task-detail-form/finished/',
                type: 'POST',
                data: { idss: BatchActions.getIds() },
                success: function(answer) {
                    TableFormActions.reloadTable('task-detail');
                },
                dataType: 'json'
            });
        }
        return false;
    });
    
    $('#batch_action_continue').click(function () {
        if (confirm('Вы действительно хотите продолжить выполнение, выбранных задач?')) {
            $.ajax({
                url: '/admin/backend/task-detail-form/continue/',
                type: 'POST',
                data: { idss: BatchActions.getIds() },
                success: function(answer) {
                    TableFormActions.reloadTable('task-detail');
                },
                dataType: 'json'
            });
        }
        return false;
    });
</script>
EOF;
    }
}