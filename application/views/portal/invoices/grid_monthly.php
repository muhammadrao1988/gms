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
            $grid->hide_fields = array('acc_id','fees_month','id','status');
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
<div class="modal fade" id="payment_pop_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
        <form class="form-horizontal validate" role="form" id="payment_form" method="post" action="<?=site_url(ADMIN_DIR.'invoices/payPayment/') ; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Payment Form</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-3 col-sm-4 control-label">Payment Type</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 5px;">
                                Monthly Fees
                            </label>
                            <!--<p class="help-block">Example block-level help text here.</p>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Amount</label>
                        <div class="col-lg-8">
                            <input class="form-control validate[required,custom[integer]]" id="fees" name="fees" placeholder="Amount" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
                        <div class="col-lg-8">
                            <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees_month" class="col-lg-3 col-sm-4 control-label">Payment Month</label>
                        <div class="col-lg-8">
                            <input style="padding: 0 10px;" class="form-control validate[required] datepicker-format" id="fees_month" name="fees_month" placeholder="dd-mm-yyyy"
                                value="<?=date('d-m-Y') ; ?>" type="text">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="invoice_id" id="invoice_id" value=""/>
                    <button data-dismiss="modal" class="btn btn-black" type="button">Close</button>
                    <button class="btn btn-green" type="submit">Pay Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . "/../includes/footer.php";
include dirname(__FILE__) . "/../delete.php";
include dirname(__FILE__) . "/../status.php";
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.payment_pop').click( function () {
            var invoice_id = $(this).attr('data-invoice');
            $('#invoice_id').val(invoice_id);
            $('#payment_pop_modal').modal('show');
        });
        $('.sub li.active a:contains("Invoices")').parent('li').removeClass('active');
        $('.sub li a:contains("Monthly Invoice")').parent('li').addClass('active');
    });
</script>
<!-- Content -->
  