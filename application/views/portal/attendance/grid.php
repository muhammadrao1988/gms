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
                    <li class="active">All Attendance</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->
        <div class="top-form">
            <?php echo $this->session->flashdata('errors'); ?>
            <form class="form-inline" role="form" id="validate" method="post"
                  action="<?= site_url(ADMIN_DIR . $this->module_name . '/take_attendance'); ?>">
                <div class="form-group">
                    <label class="sr-only" for="attendance">Insert Member ID</label>
                    <input class="form-control" value="<?php echo getVar('account_id'); ?>" id="account_id" name="account_id" placeholder="Insert Member ID"
                           type="text">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="attendance">Check Type</label>
                    <label for="style" class="styled_select">
                        <select name="check_type" id="check_type">
                            <option value="I">IN</option>
                            <option value="O">OUT</option>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="attendance">Date</label>
                    <input class="form-control datetime-picker" id="datetime" name="datetime" value="<?php echo $pk_date_time; ?>" type="text">
                </div>
                <button type="submit" class="btn btn-green">Take Attendance</button>
            </form>
        </div>
        <div class="row page-table">

            <div class="clearfix"></div>
            <?php

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = true;
            $grid->order_column = 'id';
            $grid->selectAllCheckbox = false;
            $grid->actionColumn = array('view');
            $grid->custom_func = array('monthly_status'=>'getPaymemntStatus');
            $grid->custom_col_name_fields = array('acc_name'=>'member_name','Name'=>'member_type');
            $grid->hide_fields = array('id','status','acc_id','invoices_id');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            $grid->grid_buttons = array();
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
        //$('.date-picker').data();
        $(".datetime-picker").datetimepicker({format: 'dd-mm-yyyy hh:ii', autoclose: true});
    });
</script>
<!-- Content -->
  