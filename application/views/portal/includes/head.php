<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
        var site_url = '<?php echo site_url();?>/';
        var base_url = '<?php echo base_url();?>/';
        var assets_url = '<?php echo base_url('assets');?>/';
    </script>
    <script>
	var RecaptchaOptions = {
     theme : 'white',
 
 	};
	</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Telebox">

    <title> <?php
        echo ADMIN_TITLE;
        ?> </title>
    <!--Core CSS -->
    <link href="<?php echo base_url('assets/bs3/css/bootstrap.min.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/assets/jquery-ui/jquery-ui-1.10.1.custom.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-reset.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/assets/font-awesome/css/font-awesome.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/assets/jvector-map/jquery-jvectormap-1.2.2.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/assets/bootstrap-timepicker/compiled/timepicker.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/assets/bootstrap-datetimepicker/css/datetimepicker.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/clndr.css') ; ?>" rel="stylesheet">
    <!--Switch button CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/assets/bootstrap-switch-master/build/css/bootstrap3/bootstrap-switch.css') ; ?>">
    <!--clock css-->
    <link href="<?php echo base_url('assets/assets/css3clock/css/style.css') ; ?>" rel="stylesheet">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/assets/morris-chart/morris.css') ; ?>">
    <!--dynamic table-->
    <link rel="stylesheet" href="<?php echo base_url('assets/assets/data-tables/DT_bootstrap.css') ; ?>" />
    <!-- Date picker css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/js/datapicker/base.css') ; ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/js/datapicker/clean.css') ; ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/js/gritter/css/gritter.css') ; ?>" />
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.ico') ; ?>"/>
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/style.css') ; ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style-responsive.css') ; ?>" rel="stylesheet"/>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('assets/js/ie8/ie8-responsive-file-warning.js');?>"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<!-- main container start -->
<section id="container">