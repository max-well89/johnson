<h2><b><?php echo $this->action->getAction(); ?></b> - <?php echo $this->action->getTitle(); ?></h2>

<?php if ($description = $this->action->getDescription()) : ?>
    <div><?php echo $description; ?></div>
<?php endif; ?>

<div class="col-lg-12">
    <div class="col-lg-7">
        <h4>Параметры команды</h4>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Параметр</th>
                <th>Формат</th>
                <th>Описание</th>
                <th>Код ошибки</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->action->getParameters() as $parameter => $conf): ?>
                <tr>
                    <th><?php echo $parameter; ?></th>
                    <td><?php echo $conf['validator']; ?></td>
                    <td><?php echo $conf['description']; ?></td>
                    <td><?php echo $conf['errorcode']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-lg-5">
        <h4>Возможные ошибки</h4>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Код ошибки</th>
                <th>Описание</th>
            </tr>
            </thead>
            <tbody>
            <?php //var_dump($this->action->getExceptions());
            foreach ($this->action->getExceptions() as $code => $description): ?>
                <tr>
                    <th><?php echo $code; ?></th>
                    <td><?php echo $description; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="col-lg-12">
    <div class="col-lg-5">
        <h4>Пример запроса:</h4>
        <pre><?php echo $this->getRequestExample(); ?></pre>
    </div>

    <div class="col-lg-7">
        <h4>Пример ответа:</h4>
        <pre><?php echo $this->getResponseExample(); ?></pre>
    </div>
</div>
