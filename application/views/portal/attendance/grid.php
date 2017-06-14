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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members"); ?>">Members</a></li>
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
                    <!--<input class="form-control" value="<?php /*echo getVar('account_id'); */?>" id="account_id" name="account_id" placeholder="Insert Member ID"
                           type="text">-->
                    <select name="acc_id" id="acc_id" class="select validate[required]">
                        <option value="">-Select-</option>
                        <?php
                        if($this->session->userdata('user_info')->is_machine==1){
                            echo selectBox("select acc_id,CONCAT(machine_member_id,') ',acc_name) AS acc_name from accounts where status = 1 AND machine_member_id!='' AND branch_id='".$this->session->userdata('user_info')->branch_id."' order by acc_id desc", '');
                        }else{
                            echo selectBox("select acc_id,CONCAT(machine_member_id,') ',acc_name) AS acc_name from accounts where status = 1 AND branch_id='".$this->session->userdata('user_info')->branch_id."' order by acc_id desc", '');
                        }
                        ?>
                    </select>
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
            $search = getVar('search');
            $monthly_status = '<span  style="overflow: hidden;background: #616365;"></span>';
            $check_type = '<select name="search[att:check_type]" class="form-control select-default">
                                    <option value="">-select-</option>
                                    <option value="I" '.(($search['att:check_type']=='I')?"selected":"").'>Checked In</option>
                                    <option value="O" '.(($search['att:check_type']=='O')?"selected":"").'>Checked Out</option>
                                    </select>';
            $datetime = '<input type="text" class="form-control datepicker-sql" name="search[att:datetime]" value="'.$search['att:datetime'].'"/>';
            $subsction_status = '<select name="search[subscription_status]" class="form-control select-default">
                                    <option value="">-select-</option>
                                    <option value="continue" '.(($search['subscription_status']=='continue')?"selected":"").'>Continue</option>
                                    <option value="expired" '.(($search['subscription_status']=='expired')?"selected":"").'>Expired</option>
                                    </select>';

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = true;
            $grid->order_column = 'id';
            $grid->selectAllCheckbox = false;
            $grid->actionColumn = array('view');
            $grid->search_fields_html = array('fees_month'=>$monthly_sttaus,'check_type'=>$check_type,'datetime'=>$datetime,'subscription_status'=>$subsction_status);
            $grid->custom_func = array('fees_month'=>'getPaymemntStatus','subscription_status'=>'getSubscriptionStatusResult','datetime'=>'grid_dateTimeFormat');
            $grid->custom_col_name_fields = array('acc_name'=>'member_name','Name'=>'membership_type','machine_member_id'=>'member ID','acc_id'=>'account_id','datetime'=>'Date','fees_month'=>'fees_status');
            $grid->hide_fields = array('id','status','invoices_id','acc_id','day_invoice','partial_paid');
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
include dirname(__FILE__) . "/../includes/invoice_popup_js.php";

?>
<script type="text/javascript">
    $(document).ready(function () {
        //$('.date-picker').data();
        $(".datetime-picker").datetimepicker({format: 'dd-mm-yyyy hh:ii', autoclose: true});
    });
</script>
<!-- Content -->
  