<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<!--main content start-->

<section id="main-content" class="inner-main-pages">
  <section class="wrapper"> 
    <!--mini statistics start-->
    <div class="row">
      <div class="col-lg-12"> 
        
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li><a href="<?php echo site_url(ADMIN_DIR."users_admin");?>">Admin</a></li>
          <li><a href="<?php echo site_url(ADMIN_DIR."users_admin");?>">Users</a></li>
          <li class="active">Change Password</li>
        </ul>
        <!--breadcrumbs end --> 
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row-sep"></div>
    <!-- page top buttons -->
    <div class="row page-top-btn">
      <div class="btn-row">
        <div class="btn-group">
          <button class="btn btn-white" onclick="window.history.back()" type="button"> <i class="fa fa-chevron-left"></i> Back</button>
          <span class="vert-sep"></span>
          <button class="btn btn-white" data-action="<?= site_url(ADMIN_DIR."users_admin/form/".$id); ?>"  type="button">Users</button>
          <span class="vert-sep"></span> 
         
           <button class="btn btn-white active"  type="button">Change Password</button>
          <span class="vert-sep"></span> 
           <button class="btn btn-white" data-action="<?= site_url(ADMIN_DIR."users_admin/account_access/".$id); ?>"  type="button">Account Access</button>
          <span class="vert-sep"></span> 
         
          </div>
      </div>
      <div class="clearfix"></div>
    </div>
    <div class="row page-middle-btn">
      <div class="col-sm-12">
        <section class="panel">
          <div class="panel-body panel-breadcrumb-action"> <?php echo show_validation_errors();?>
            <div class="period-text"></div>
          </div>
        </section>
      </div>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <section class="panel">
        <header class="panel-heading theme-panel-heading"> <strong>Users - Change Password</strong> </header>
        <div class="panel-body">
          <form id="validate" class="form-horizontal theme-form-horizontal" action="<?= site_url(ADMIN_DIR . $this->module_name . '/send_new_password'); ?>" method="post" enctype="multipart/form-data">
           <input type="hidden" name="user_id" id="user_id" value="<?= $id; ?>"/>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="inputEmail1" class="col-lg-3 control-label">New Password:</label>
                <div class="col-lg-4">
                  <input type="password" id="password" name="password" value="" class="form-control validate[required,custom[my_password]]">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail1" class="col-lg-3 control-label">Re-Type New Password:</label>
                <div class="col-lg-4">
                  <input type="password" id="retype_new_pwd" name="retype_new_pwd" value="" class="form-control validate[required,equals[password]]">
                </div>
              </div>
              
              
               
              <div class="form-group">
                <label for="inputEmail1" class="col-lg-3 control-label">Don't send email:</label>
                <div class="col-lg-4">
                  <input type="checkbox" id="send_email" name="send_email" value="1">
                </div>
              </div>
             
                <div class="form-group password_st_container" style="float:none; border:0">
                  <label class="control-label col-lg-3">Password Strength</label>
                  <div class="col-lg-4">
                    <div id="passwordDescription">Password not entered</div>
                    <div id="passwordStrength" class="strength0"></div>
                   
                  </div>
                </div>
                <?php
                $login_status = getVal("users","status", " WHERE user_id='".$id."'");
                if($login_status==3){
                ?>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-3 control-label"> Account Status:</label>
                    <div class="col-lg-4">
                   Locked - Invalid Password
                        <p class="help-block">Note: If you change  password the user account will be unlock.</p>
                    </div>
                </div>
                    <input type="hidden">
               <?php }?>
              
              
          
              <div class="form-group">
                <label class="control-label col-md-3">&nbsp;</label>
                <div class="col-md-8">
                  <button type="reset" class="btn btn-black " onclick="window.history.back()"> Cancel</button>
                  <button type="submit" name="submit" class="btn btn-green "> Submit </button>
                </div>
              </div>
            </div>
          </div>
          </form>
        </div>
      </section>
    </div>
    
    <!-- end data table --> 
    
  </section>
</section>
<!--main content end-->
<?php
include dirname(__FILE__) . "/../includes/footer.php";

?>
<link rel="stylesheet" type="text/css" media="screen" href="<?=base_url('assets/css/passwordstrength.css');?>"/>
<script type="text/javascript" src="<?=base_url('assets/js/passwordstrength.js');?>"></script>

<script>
    (function ($) {
        $(document).ready(function () {
            $('#password').on('keyup', function () {
                passwordStrength(this.value);
            });
        });


    })(jQuery)
</script>