<?php
include dirname(__FILE__) . "/includes/head.php";
include dirname(__FILE__) . "/includes/header.php";
?>
<body class="login-body" style="background:#f8f8f8; height: 100%;">
<style type="text/css">
    html,body{
        height: 78%;
    }
    .sidebar-toggle-box{
        disply:none;
    }
</style>
<div class="clearfix">&nbsp;</div>


<div class="container" style="margin-top:4%; min-height: 100%;height: auto !important;">
    <div class="notification"><?php echo show_validation_errors_login(); ?></div>

    <?php if(isset($warning_msg)){
        if($warning_msg!=''){ ?>
        <div class="notification"><div class="alert alert-block alert-danger fade in">
            <button class="close close-sm" type="button" data-dismiss="alert">
                <i class="fa fa-times"></i>
            </button>
            <p><?php echo $warning_msg ; ?></p>
            </div>
        </div>
    <?php }
    }?>
    <div class="clearfix">&nbsp;</div>
    <?php
    if ($page == '' && getVar('info')=='' && getVar('success')=='') {
        ?>
        <form class="form-signin" action="<?php echo site_url(ADMIN_DIR . 'login/do_login'); ?>" method="post"
              style="margin: 40px auto;">
            <h2 class="form-signin-heading">User Login</h2>

            <div class="login-wrap">
                <div class="user-login-info">
                    <input type="text" class="loginEmail form-control" placeholder="Your username" name="username" autofocus>
                    <input type="password" class="loginPassword form-control" placeholder="Password" name="password">
                </div>
                <label class="checkbox">
                    <input type="checkbox" id="remember1" class="check styled" checked="checked">
                    Remember me
                   <!-- <span class="pull-right">
                        <a href="<?/*= site_url(ADMIN_DIR . 'login/forget_pass'); */?>"> Forgotten
                            Password?</a> <br/>
                            <a href="<?/*= site_url(ADMIN_DIR . 'login/forget_user'); */?>"> Forgotten Username?</a>
                    </span> -->
                </label>
                <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
            </div>
        </form>
    <?php
    }
    ?>
    <?php
    if ($page == 'forget_pass') {

        ?>

        <form class="form-signin" id="forget_pass" action="<?php echo site_url(ADMIN_DIR . 'login/do_forget_pass'); ?>"
              method="post" style="margin: 40px auto;">
            <h2 class="form-signin-heading">Forgot Password ?</h2>

            <div class="login-wrap">
                <div class="user-login-info">
                    <div class="alert alert-info"><strong>Please enter username, email address and click on robot checkbox.</strong></div>
                    <input type="text" class="form-control user_name validate[required]" placeholder="Enter your username"
                           name="username">
                    <input type="text" class="form-control loginEmail validate[required,custom[email]]"
                           placeholder="Enter your Email" name="email">

                    <div class="row">
                        <?php //echo  $recaptcha; ?>
                        <div class="g-recaptcha" style="padding-left: 15px;" data-sitekey="6LcQbQoTAAAAALQfYGNwaqrNgKFqDQYK4jEbYGVL"></div>
                    </div>
                </div>
                <div class="row">
                    <input type="button" onClick="window.location='<?= site_url(ADMIN_DIR); ?>'"
                           class="btn btn-black pull-left" value="Back" name="Back">
                    <button class="btn btn-green pull-right" type="submit" value="Forgot" name="submit">Forgot</button>
                </div>
            </div>
        </form>
    <?php
    }
    ?>
    <?php
    if ($page == 'forget_user') {

        ?>
        <form class="form-signin" id="recover" action="<?php echo site_url(ADMIN_DIR . 'login/do_forget_user'); ?>" method="post"
              style="margin: 40px auto;">
            <h2 class="form-signin-heading">Forgot Username ?</h2>

            <div class="login-wrap">
                <div class="user-login-info">
                    <div class="alert alert-info"><strong> Please enter email address and and click on robot checkbox..</strong>
                    </div>
                    <input type="text" class="form-control loginEmail validate[required,custom[email]]"
                           placeholder="Enter your Email" name="email">

                    <div class="row">
                        <?php //echo  $recaptcha; ?>
                        <div class="g-recaptcha" style="padding-left: 15px;" data-sitekey="6LcQbQoTAAAAALQfYGNwaqrNgKFqDQYK4jEbYGVL"></div>
                    </div>
                </div>
                <div class="row">
                    <input type="button" onClick="window.location='<?= site_url(ADMIN_DIR); ?>'"
                           class="btn btn-black pull-left" value="Back" name="Back">
                    <button class="btn btn-green pull-right" type="submit" value="Forgot" name="submit">Forgot</button>
                </div>
            </div>
        </form>
    <?php
    }
    ?>

    <!-- first time login steps -->
    <?php
    if ($page == 'step1') {
        ?>
        <section class="panel form-signin" style="max-width: 500px;">
            <header class="panel-heading" style="background:#616365;border-bottom:4px solid #d2d2d2;color:#fff"> First Login Step
                1 of 2
            </header>
            <div class="panel-body">
                <div class="form">
                    <div class="alert alert-info"><strong>To enhance the security on your account please select a security
                            question and enter
                            an answer below.</strong></div>
                    <form class="cmxform form-horizontal " id="answer_form"
                          action="<?php echo site_url(ADMIN_DIR . 'login/do_first_login'); ?>" method="post">
                        <input type="hidden" name="step" id="step" value="step1"/>

                        <div class="form-group ">
                            <label for="firstname" class="control-label col-lg-5">Select Question:</label>

                            <div class="col-lg-6">
                                <span class="styled_select">
                                <select name="select_question" required="" id="select_question" class="validate[required]">
                                    <option value=""> - Select -</option>
                                    <?php echo selectBox("SELECT sec_id,sec_question FROM security_q WHERE sec_active=1"); ?>
                                </select>
                                 </span>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="answer" class="control-label col-lg-5">Answer:</label>

                            <div class="col-lg-6">
                                <input placeholder="Enter your answer..." required type="password" value="" name="answer"
                                       id="answer"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="confirm_answer" class="control-label col-lg-5">Re-enter Answer:</label>

                            <div class="col-lg-6">
                                <input placeholder="Enter your answer again..." required type="password" value=""
                                       id="confirm_answer" name="confirm_answer"
                                       class="form-control validate[required,equals[password]]"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="answer" class="control-label col-lg-5">&nbsp;</label>

                            <div class="col-lg-6">
                                <input type="button" onClick="window.location='<?php echo site_url(ADMIN_DIR); ?>'"
                                       class="btn btn-black" value="Back" name="Back">
                                <input type="submit" class="btn btn-green" value="Next" name="submit">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    <?php
    }
    if ($page == 'step2') {
        ?>
        <section class="panel form-signin" style="max-width: 500px;">
            <header class="panel-heading" style="background:#616365;border-bottom:4px solid #d2d2d2;color:#fff"> First Login Step
                2 of 2
            </header>
            <div class="panel-body">
                <div class="form">
                    <div class="alert alert-info"><strong>Please enter a new password to replace your temporary password.</strong>
                    </div>
                    <p class="color-red"></p>

                    <form class="cmxform form-horizontal " id="validateForm"
                          action="<?php echo site_url(ADMIN_DIR . 'login/do_first_login'); ?>" method="post">
                        <input type="hidden" name="step" id="step" value="step2"/>

                        <div class="form-group ">
                            <label for="answer" class="control-label col-lg-5">Password:</label>

                            <div class="col-lg-6">
                                <input placeholder="Enter your Password..." required type="password" value="" name="password"
                                       id="password"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="answer" class="control-label col-lg-5">Re-enter Password:</label>

                            <div class="col-lg-6">
                                <input placeholder="Enter  your Password again..." required type="password" value=""
                                       name="confirm_password" id="confirm_password"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="answer" class="control-label col-lg-5">Password Strength:</label>

                            <div class="col-lg-6">
                                <div id="passwordDescription">Password not entered</div>
                                <div id="passwordStrength" class="strength0"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="answer" class="control-label col-lg-5">&nbsp;</label>

                            <div class="col-lg-6">
                                <input type="button" onClick="window.location='<?php echo site_url(ADMIN_DIR); ?>'"
                                       class="btn btn-black" value="Back" name="Back">
                                <input type="submit" class="btn btn-green" value="Next" name="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    <?php

    }
    ?>
</div>
<footer class="footer-section" style="bottom:0; position: absolute;">
    <div class="text-left" style="float:left"> &nbsp;&nbsp; &copy; <?php echo date('Y').' '.PROJECT_TITLE; ?>.  All rights
        reserved.
    </div>
   <!-- <div class="text-right"> Powered by <a href="http://www.topgearmedia.co.uk" target="_blank" style="color:#fff;">Top Gear
            Media</a>&nbsp;&nbsp; </div>-->
</footer>
<div class="loader-dashboard" style="display: none;"></div>

<!--Core js-->
<!--<script src="<?php /*echo base_url('assets/js/lib/jquery.js'); */?>"></script>
<script src="<?php /*echo base_url('assets/assets/jquery-ui/jquery-ui-1.10.1.custom.min.js'); */?>"></script>-->
<!--Core js-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/accordion-menu/jquery.dcjqaccordion.2.7.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/scrollTo/jquery.scrollTo.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/nicescroll/jquery.nicescroll.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/assets/jQuery-slimScroll-1.3.0/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/assets/skycons/skycons.js'); ?>"></script>
<script src="<?php echo base_url('assets/assets/jquery.scrollTo/jquery.scrollTo.js'); ?>"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="<?php echo base_url('assets/assets/calendar/clndr.js'); ?>"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<script src="<?php echo base_url('assets/assets/calendar/moment-2.2.1.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/calendar/evnt.calendar.init.js'); ?>"></script>
<script src="<?php echo base_url('assets/assets/jvector-map/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/assets/jvector-map/jquery-jvectormap-us-lcc-en.js'); ?>"></script>
<!--<script src="<?/*=base_url('assets/assets/gauge/gauge.js') ; */?>"></script>-->
<!--form validation and confirm box -->

<!--clock init-->
<script src="<?php echo base_url('assets/assets/css3clock/js/script.js'); ?>"></script>
<!--Easy Pie Chart-->
<script src="<?php echo base_url('assets/assets/easypiechart/jquery.easypiechart.js'); ?>"></script>
<!--Sparkline Chart-->
<script src="<?php echo base_url('assets/assets/sparkline/jquery.sparkline.js'); ?>"></script>
<!--Morris Chart-->
<script src="<?php echo base_url('assets/assets/morris-chart/morris.js'); ?>"></script>
<script src="<?php echo base_url('assets/assets/morris-chart/raphael-min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-validate/jquery.validate.min.js'); ?>"></script>

<!--common script init for all pages-->
<script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-validate/validation-init.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/jquery.validation.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/jquery.uniform.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/jquery.tagsinput.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/jquery.select2.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/jquery.validationEngine-en.js'); ?>"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?= base_url('assets/css/passwordstrength.css'); ?>"/>
<script type="text/javascript" src="<?= base_url('assets/js/passwordstrength.js'); ?>"></script>
<script src='//www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
    var RecaptchaOptions = {
        theme: 'white'
    };
</script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#password').on('keyup', function () {
                passwordStrength(this.value);
            });
            <?php
            if(getVar('info')!=""){
            echo '$(".form-signin").hide();';
            }

             ?>

            $('form#recover,form#forget_pass').on('submit', function (e) {
                e.preventDefault();
                if ($(this).validationEngine('validate')) {
                    var form = $(this);
                    var action = $(this).attr('action');
                    $('.loader-dashboard').show();
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: action,
                        data: form.serialize(),
                        complete: function (data) {
                            var json = $.parseJSON(data.responseText);
                            $('.loader-dashboard').hide();
                            //console.log(json);
                            $('.notification').html(json.notification);
                           // Recaptcha.reload();

                        }
                    });
                }
            });
        });
    })(jQuery)
</script>
<?php if(DOMAIN_URL != "http://localhost/telebox_new"){?>
<script type="text/javascript">
    var _mfq = _mfq || [];
    (function () {
    var mf = document.createElement("script"); mf.type = "text/javascript"; mf.async = true;
    mf.src = "//cdn.mouseflow.com/projects/f5f469e6-e36d-4431-af92-75d05b5936f3.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script>
    <script type="text/javascript">
      /*  var $zoho= $zoho || {salesiq:{values:{},ready:function(){}}};var d=document;s=d.createElement("script");s.type="text/javascript";s.defer=true;s.src="https://salesiq.zoho.com/voodoosms/float.ls?embedname=voodoosms";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);*/
    </script>
<?php }?>
</body>
</html>