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

            $s_company = '<label class="styled_select"><select class="styled" name="search[company]" id="company">
                        <option value=""> - Customer - </option>
                        ' . selectBox("SELECT acc_id, acc_name FROM accounts WHERE acc_types IN('" . COMPANY_IDS . "')", $search_ar['company']) . '
                      </select></label>';
            $s_reseller = '<label class="styled_select"><select class="styled" name="search[reseller]" id="reseller">
                        <option value=""> - Reseller - </option>
                        ' . selectBox("SELECT acc_id, acc_name FROM accounts WHERE acc_types IN('" . RESELLER_IDS . "')", $search_ar['reseller']) . '
                      </select></label>';
            $s_user_id = '<input class="form-control" type="text" name="search[users:user_id]" id="search_user_id" value="' . $search_ar['users:user_id'] . '" placeholder="Acc id">';
            $s_username = '<input class="form-control" type="text" name="search[username]" id="search_username" value="' . $search_ar['username'] . '" placeholder="Username">';
            $s_email = '<input class="form-control" type="text" name="search[email]" id="search_email" value="' . $search_ar['email'] . '" placeholder="email">';

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = TRUE;
            $grid->hide_fields = array('full_name', 'email', 'user_login_status');
            $grid->search_fields_html = array('user_login_status' => '', 'company' => $s_company, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);

            $grid->form_buttons = array('new', 'delete');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
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
  