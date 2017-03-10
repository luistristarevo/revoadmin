<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <link href="<?php echo asset('../css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/bootstrap-select.css'); ?>" rel="stylesheet">

    <link href="<?php echo asset('../css/new.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/uxclass.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/uxclass.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../css/uxclass.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('../img/favicon.png'); ?>" rel="icon" type="image/png"/>
    <?php echo Rapyd::styles(); ?>
    <link href="<?php echo asset('../css/adminui.css'); ?>" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo asset('../js/jquery-2.1.4.min.js'); ?>"></script>
</head>
<body>

<?php include_once 'admin_vnav.php'; ?>
<?php if(session('success')){?> <div class="container"><div class="alert alert-success"><?php echo session('success'); ?></div></div> <?php } ?>
<?php if(session('error')){?> <div class="container"><div class="alert alert-danger"><?php echo session('error'); ?></div></div> <?php } ?>