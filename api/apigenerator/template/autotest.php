<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Автотест генератора API</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php foreach ($this->getClassesForTest() as $class => $params) : ?>
    <div class="col-lg-12">
        <h4><?php echo $class; ?></h4>
        <div class="conbtent">
            <?php echo $this->runTest($class); ?>
        </div>
    </div>
<?php endforeach; ?>


<?php
/*
    <div class="col-lg-12"><h4>API проекта <b><?php echo $this->context->getProjectName(); ?></b></h4></div>

    <div id="index" class="col-lg-4">
        <h2>Навигация</h2>
        <ul>
            <li><a href="?p=main">1. Общее описание протокола взаимодействия</a></li>
            <li><a href="?p=cmdlist">2. Список команд и их описание</a></li>
            <li>
                <ul>
                    <?php foreach ($this->getActionsList() as $cmd => $title) : ?>
                    <li><a href="?p=cmd&cmd=<?php echo $cmd; ?>"><?php echo "<b>{$cmd}</b> - {$title}"; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
        
        
    </div>
    
    <div id="content" class="col-lg-8">
        <?php echo $this->getContent(); ?>
    </div>
    */ ?>

</body>
</html>
