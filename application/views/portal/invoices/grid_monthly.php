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
            $machine_member = '<input class="form-control" type="text" name="search[acc:machine_member_id]" id="search_machine_member_ids" value="'.$search_ar['acc:machine_member_id'].'">';

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = true;
            $grid->selectAllCheckbox = false;
            $grid->hide_fields = array('acc_id','id','day_invoice','invoices_id','last_paid');
            $grid->order_column = 'id';
            $grid->record_not_found = 'No monthly invoice';
            $grid->custom_func = array('fees_month'=>'getPaymemntStatus','amount'=>'getTotalfeesAmount');
            $grid->custom_col_name_fields = array('acc_date'=>'Registration Date','machine_member_id'=>'Member Id','fees_month'=>'Payment Status','acc_name'=>'Member Name');
            $grid->search_fields_html = array('machine_member_id'=>$machine_member,'acc_date'=>'','Amount'=>'','fees_month'=>'','last_paid_month'=>'','payment_status'=>'');
            //$grid->form_buttons = array('new');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array('');
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
include dirname(__FILE__) . "/../includes/invoice_popup_js.php";


?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.sub li.active a:contains("Invoices")').parent('li').removeClass('active');
        $('.sub li a:contains("Monthly Invoice")').parent('li').addClass('active');
    });

</script>
<!-- Content -->
  