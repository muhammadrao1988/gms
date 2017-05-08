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
          <li class="active">Account Access</li>
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
         
           <button class="btn btn-white" data-action="<?= site_url(ADMIN_DIR."users_admin/send_new_password/".$id); ?>"  type="button">Change Password</button>
          <span class="vert-sep"></span> 
          <button class="btn btn-white active"  type="button">Account Access</button>
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
        <header class="panel-heading theme-panel-heading"> <strong>Users -  Account Access</strong> </header>
        <div class="panel-body">
          <form id="validate" class="form-horizontal theme-form-horizontal" action="<?= site_url(ADMIN_DIR . $this->module_name . '/account_access'); ?>" method="post" enctype="multipart/form-data">
           <input type="hidden" name="user_id" id="user_id" value="<?= $id; ?>"/>
          <div class="row">
            <div class="col-md-12">
              
              <div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Account State:</label>
                        <div class="col-lg-6">
                            
                            <div class="radio">
                                <label>
                                    <input type="radio" name="access_account" id="access_account" value="1" checked="checked">
                                   Lock Account
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="access_account" id="access_account" value="2">
                                   Unlock Account
                                </label>
                            </div>

                        </div>
                    </div>
         
              
          
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
