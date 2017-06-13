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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "invoices"); ?>">Fees Management</a></li>
                    <li class="active"><?php echo $title ?></li>
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
            $machine_member = '<input class="form-control" type="text" name="search[ic:machine_member_id]" id="search_machine_member_ids" value="'.$search_ar['ic:machine_member_id'].'">';
            $fees_datetime = '<input type="text" class="form-control datepicker-sql" name="search[ic:fees_datetime]" value="'.$search_ar['ic:fees_datetime'].'"/>';
            $last_paid = '<input type="text" class="form-control date-picker" name="search[ic:fees_month]" value="'.$search_ar['ic:fees_month'].'"/>';
            $invoice_type = '<select class="select" name="search[ic:type]" id="ic:type" style="width: 100%">
                        <option value=""> - Select Invoice Type - </option>
                        ' . selectBox("SELECT id, name FROM invoice_types", $search_ar['ic:type']) . '
                      </select>';
            $invoice_state = '<select class="select" name="search[ic:state]" id="ic:state" style="width: 100%">
                        <option value="" > - Select State - </option>
                        <option value="1" '.($search_ar['ic:state']==1 ? "selected":"").'> Paid </option>
                        <option value="2" '.($search_ar['ic:state']==2 ? "selected":"").'> Partial Paid </option>
                        <option value="3" '.($search_ar['ic:state']==3 ? "selected":"").'> Cancelled </option>
                      
                      </select>';
            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = true;
            $grid->selectAllCheckbox = false;
            $grid->hide_fields = array('acc_id');
            $grid->order_column = 'id';
            $grid->custom_col_name_fields = array('acc_name'=>'Member Name','fees_datetime'=>'Paid Date','type'=>'Invoice For','fees_month'=>'last_paid_month','machine_member_id'=>'Member Id','id'=>'invoice_No.','acc_id'=>'Account ID');
            $grid->search_fields_html = array('state'=>$invoice_state,'machine_member_id'=>$machine_member,'fees_datetime'=>$fees_datetime,'type'=>$invoice_type,'fees_month'=>$last_paid);
            //$grid->custom_func = array('amount'=>'getTotalfeesAmount');
            $grid->custom_func = array('type'=>'invoice_for','fees_datetime'=>'grid_dateFormat');
            //$grid->search_fields_html = array('user_login_status' => '', 'company' => $s_company, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);

            $grid->form_buttons = array('new');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array('view','edit');
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
<script>
    $(function() {
        $('.date-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
    });
</script>
<!-- Content -->
  