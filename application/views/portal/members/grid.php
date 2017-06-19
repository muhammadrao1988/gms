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

                $search = getVar('search');
                $subsction_status = '<select name="search[subscription_status]" class="form-control select-default">
                                    <option value="">-select-</option>
                                    <option value="continue" '.(($search['subscription_status']=='continue')?"selected":"").'>Continue</option>
                                    <option value="expired" '.(($search['subscription_status']=='expired')?"selected":"").'>Expired</option>
                                  
                                    </select>';
                $datetime = '<input type="text" class="form-control datepicker-format" name="search[acc:acc_date]" value="'.(($search['acc:acc_date']!="")?date('d-M-Y',strtotime($search['acc:acc_date'])):"").'"/>';
                $membership_type = '<input type="text" class="form-control" name="search[act:Name]" value="'.$search['act:Name'].'"/>';
                $subscription_name = '<input type="text" class="form-control" name="search[sub:name]" value="'.$search['sub:name'].'"/>';
                $grid = new grid();
                $grid->query = $query;
                //$grid->title = $this->module_title .' - List';
                $grid->limit = 25;
                $grid->search_box = true;
                $grid->selectAllCheckbox = false;
                $grid->order_column = 'acc_id';
                $grid->hide_fields = array('machine_serial','status','invoices_id','acc_id','day_invoice','branch_name','partial_paid','subscription_status');
                //$grid->custom_func = array('subscription_status'=>'getSubscriptionStatus');
                $grid->custom_func = array('unpaid_invoice'=>'getPaymemntStatus','subscription_status'=>'getSubscriptionStatusResult','acc_date'=>'grid_dateFormat');
                $grid->custom_col_name_fields = array('machine_member_id'=>'Member ID','Name'=>'membership_type','monthly_status'=>'Membership Status','acc_name'=>'Name','acc_tel'=>'Mobile','name'=>'Subscription','acc_date'=>'Registration Date','acc_id'=>'account_id','unpaid_invoice'=>'fees_status');
                $grid->search_fields_html = array('partial_paid'=>'','unpaid_invoice' => '','Name'=>$membership_type, 'name' => $subscription_name,'acc_date'=>$datetime);
                $grid->form_buttons = array('new');
                $grid->url = '?' . $_SERVER['QUERY_STRING'];
                //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
                $grid->grid_buttons = array('view_invoice','view_attendance','edit', 'delete');
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
<!-- Content -->
  