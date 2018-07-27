<h2>Список команд и их описание</h2>

<table class="table table-condensed">
    <thead>
    <tr>
        <th>Название команды</th>
        <th>Краткое описание</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->getActionsList() as $group => $commands) : ?>
        <tr>
            <th colspan="2"><?php echo $group; ?></th>
        </tr>
        <?php foreach ($commands as $cmd => $title) : ?>
            <tr>
                <th><a href="?p=cmd&cmd=<?php echo $cmd; ?>"><?php echo $cmd; ?></th>
                <td><?php echo $title; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>
