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

            $receipt_date = '<input type="text" class="form-control datepicker-format" name="search[receipt_date]" value="'.$search_ar['receipt_date'].'"/>';

             $grid = new grid();
            $grid->query = $query;
            $grid->limit = 25;
            $grid->search_box = true;
            $grid->selectAllCheckbox = false;
            $grid->hide_fields = array();
            $grid->order_column = 'id';
            $grid->custom_col_name_fields = array('invoice_id'=>'Invoice #','receipt_id'=>'Receipt #','machine_member_id'=>'Member ID');
            $grid->search_fields_html = array('receipt_date'=>$receipt_date);
            $grid->custom_func = array();

            $grid->form_buttons = array('new');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            $grid->grid_buttons = array('view');
            $grid->id_field ='receipt_id';
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
include dirname(__FILE__) . "/../includes/cancel_invoice.php";
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
    })
    $('.cancel_invoice').click(function(){

        var acc_id =$(this).attr("data-id");
        var invoice_id =$(this).attr("data-invoice");

        $('#cancel_invoice_modal #acc_id').attr('value', acc_id);
        $('#cancel_invoice_modal #invoice_id').attr('value', invoice_id);
    });
</script>
<!-- Content -->
  