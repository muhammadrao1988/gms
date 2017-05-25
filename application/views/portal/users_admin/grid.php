<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css') ; ?>">
<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>>">Admin</a></li>
                    <li class="active">Users</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->


        <div class="row page-table">

            <div class="clearfix"></div>

            <?php

            $search_ar = getVar('search');

            $branch = '<select class="select" name="search[branch_id]" id="branch_id" style="width: 100%">
                        <option value=""> - Select Branch - </option>
                        ' . selectBox("SELECT id, branch_name FROM branches", $search_ar['branch_id']) . '
                      </select>';
            $template = '<select class="select" name="search[user_template_id]" id="user_template_id" style="width: 100%">
                        <option value=""> - Select Role - </option>
                        ' . selectBox("SELECT id, user_type FROM user_types_template", $search_ar['user_template_id']) . '
                      </select>';
            $s_user_id = '<input class="form-control" type="text" name="search[user_id]" id="search_user_id" value="' . $search_ar['user_id'] . '" placeholder="Acc id">';
            $s_username = '<input class="form-control" type="text" name="search[username]" id="search_username" value="' . $search_ar['username'] . '" placeholder="Username">';


            $grid = new grid();
            $grid->query = $query;

            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = TRUE;
            $grid->hide_fields = array('full_name', 'email', 'user_login_status');
            $grid->search_fields_html = array('branch_name' => $branch, 'user_type' => $template, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);

            $grid->form_buttons = array('new', 'delete');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            $grid->custom_col_name_fields = array('user_type'=>'User Role');
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array('edit', 'delete', 'acc_status');
            echo $grid->showGrid();

            ?>
            <!--</div>-->
        </div>
        <!-- end data table -->


    </section>
</section>

<?php
include dirname(__FILE__) . "/../includes/footer.php";
include dirname(__FILE__) . "/../delete.php";
include dirname(__FILE__) . "/../status.php";

?>
<!-- Content -->
  