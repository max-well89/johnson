<?php foreach ($this->getProjects() as $path) : ?>
    <div class="col-lg-4 col-md-6">
        <?php $info = $this->getProjectInfo($path); ?>
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $info['project_name']; ?></div>
            <div class="panel-body">
                <p>База данных: <?php echo $info['db_dsn'] . ' / ' . $info['db_user']; ?>; Префикс
                    логов: <?php echo $info['log_prefix']; ?></p>
                <p>URLS:
                <ul>
                    <?php foreach ($info['urls'] as $name => $url): ?>
                        <li class="container-fluid">
                            <div class="col-sms-4"><?php echo $name ?></div>
                            <div class="col-sms-8"><a href="<?php echo $url ?>"><?php echo $url ?></a></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
