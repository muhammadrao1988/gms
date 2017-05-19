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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members"); ?>>">Members</a></li>
                    <li class="active">All Members</li>
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
                $grid = new grid();
                $grid->query = $query;
                //$grid->title = $this->module_title .' - List';
                $grid->limit = 25;
                $grid->search_box = false;
                $grid->selectAllCheckbox = false;
                $grid->order_column = 'acc_id';
                $grid->hide_fields = array('machine_serial','status','invoices_id');
                //$grid->custom_func = array('subscription_status'=>'getSubscriptionStatus');
                $grid->custom_func = array('monthly_status'=>'getPaymemntStatus');
                $grid->custom_col_name_fields = array('acc_id' => 'Member ID','acc_name'=>'Name','acc_tel'=>'Mobile','name'=>'Subscription','acc_date'=>'Datetime');
                $grid->search_fields_html = array('user_login_status' => '', 'company' => $s_company, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);
                $grid->form_buttons = array('new', 'delete');
                $grid->url = '?' . $_SERVER['QUERY_STRING'];
                //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
                $grid->grid_buttons = array( 'view_attendance','edit', 'delete');
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
include dirname(__FILE__) . "/../includes/fees_pay_pop.php";
?>
<!-- Content -->
  