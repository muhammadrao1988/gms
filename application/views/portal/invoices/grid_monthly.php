<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css'); ?>">
<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members"); ?>>">Fees Management</a></li>
                    <li class="active">Monthly <?php echo $title ?></li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->


        <div class="row page-table">
            <?php
            if(getVar('error')!=''){
                ?>
            <div class="row page-middle-btn">
                <div class="col-sm-12">
                    <section class="panel">
                        <div class="panel-body panel-breadcrumb-action"> <?php echo show_validation_errors(); ?>
                            <div class="period-text"></div>
                        </div>
                    </section>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="clearfix"></div>
            <?php

            $search_ar = getVar('search');

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = false;
            $grid->selectAllCheckbox = false;
            $grid->hide_fields = array('acc_id','fees_month','id','status','invoices_id');
            $grid->order_column = 'id';
            $grid->custom_func = array('payment_status'=>'getPaymemntStatus','amount'=>'getTotalfeesAmount');
            //$grid->form_buttons = array('new');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array('view');
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.sub li.active a:contains("Invoices")').parent('li').removeClass('active');
        $('.sub li a:contains("Monthly Invoice")').parent('li').addClass('active');
    });
</script>
<!-- Content -->
  