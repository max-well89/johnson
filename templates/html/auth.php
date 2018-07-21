<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->context->getConfigVal('title'); ?></title>

    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/css/stat.css">

</head>
<body class="auth jumbotron">
<div class="col-sm-4 col-sm-offset-4 container">
    <div class="<?php echo $panelClass ?> panel panel-danger">
        <div class="panel-heading" style="text-align: center;">
            <img style="max-width: 150px;" src="/img/logo.png"/>
            <form>
                <a href="/admin/set_locale?lang=ru" >RU</a>
                |
                <a href="/admin/set_locale?lang=en" >EN</a>
            </form>
        </div>
        <div class="panel-body"><?php echo $content; ?></div>
    </div>
</div>
<script src="/js/jquery-1.11.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>
