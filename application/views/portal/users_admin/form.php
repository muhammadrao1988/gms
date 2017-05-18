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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">Admin</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">Users</a></li>
                    <li class="active"><?php echo(!empty($row->user_id) ? 'Edit' : 'Add'); ?></li>
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
                    <button class="btn btn-white" onclick="window.history.back()" type="button"><i
                                class="fa fa-chevron-left"></i> Back
                    </button>
                    <span class="vert-sep"></span>
                    <button class="btn btn-white active" type="button">Users</button>
                    <span class="vert-sep"></span>
                    <?php if (!empty($row->user_id)) { ?>
                        <button class="btn btn-white"
                                data-action="<?= site_url(ADMIN_DIR . "users_admin/send_new_password/" . $row->user_id); ?>"
                                type="button">Change Password
                        </button>
                        <span class="vert-sep"></span>
                        
                    <?php } ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row page-middle-btn">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body panel-breadcrumb-action">
                        <div class="period-text"></div>
                    </div>
                </section>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo '<pre>';print_r($_REQUEST );echo '</pre>';
            echo show_validation_errors(); ?>
            <section class="panel">
                <header class="panel-heading theme-panel-heading"><strong>Users - Form</strong></header>
                <div class="panel-body">
                    <form id="validate" class="form-horizontal theme-form-horizontal" role="form" method="post"
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->user_id) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" id="user_id" value="<?= $row->user_id; ?>"/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">User
                                        Template</label>
                                    <div class="col-lg-6">

                                            <select name="user_template_id" id="user_template_id"
                                                    class="select validate[required]">
                                                <option value=""> - Select -</option>
                                                <?= selectBox("SELECT id, `user_type` FROM `user_types_template`", $row->user_template_id); ?>
                                            </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">User Type</label>
                                    <div class="col-lg-6">

                                            <select name="user_type" id="user_type" class="select validate[required]">
                                                <option value=""> - Select -</option>
                                                <?= selectBox("SELECT id , `user_type` as show_u_type FROM `user_types`", $row->user_type); ?>
                                            </select>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">First
                                        Name</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="first_name" name="first_name"
                                               value="<?= $row->first_name; ?>" placeholder="First Name"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Surname</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="surname" name="surname" value="<?= $row->surname; ?>"
                                               placeholder="Surname" class="form-control validate[required]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Email</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="email" name="email" value="<?= $row->email; ?>"
                                               placeholder="example@domain.com:"
                                               class="form-control validate[required,custom[email]]">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Mobile
                                        Number:</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="mob_phone" name="mob_phone"
                                               value="<?= $row->mob_phone; ?>" placeholder="Mobile Number"
                                               class="form-control validate[required,custom[phone]]">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="machine_serial" class="col-lg-3 col-sm-3 control-label">Machine Serial Number:</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="machine_serial" name="machine_serial" value="<?= $row->machine_serial; ?>"
                                               placeholder="Machine Serial Number" class="form-control validate[required,custom[integer]]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="branch_id" class="col-lg-3 col-sm-3 control-label">Branch:</label>
                                    <div class="col-lg-6">

                                            <select name="branch_id" id="branch_id" class="select validate[required]">
                                                <option value=""> - Select -</option>
                                                <?= selectBox("SELECT id,branch_name FROM `branches`", $row->branch_id); ?>
                                            </select>

                                    </div>
                                </div>
                                <?php if($row->user_id==""){?>
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
                                <?php }?>
                                <div class="form-group">
                                    <label class="control-label col-md-3">&nbsp;</label>
                                    <div class="col-md-8">
                                        <button type="reset" class="btn btn-black " onclick="window.history.back()">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-green "> Submit</button>
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
