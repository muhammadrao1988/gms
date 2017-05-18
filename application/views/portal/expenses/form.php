<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
<style type="text/css">
    .form-group div.acc_idformError, .form-group div.typeformError {
        left: 180px !important;
    }
    .append_row{
        margin-top: 10px;
    }
</style>
<!--main content start-->

<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "expenses"); ?>">Expenses</a></li>
                    <li class="active"><?php echo(!empty($row->id) ? 'Edit' : 'Add'); ?></li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->
        <div class="row page-top-btn">
            <div class="btn-row">
                <div class="btn-group">
                    <button class="btn btn-white" onclick="window.history.back()" type="button"><i
                                class="fa fa-chevron-left"></i> Back
                    </button>
                    <span class="vert-sep"></span>
                    <button class="btn btn-white active" type="button">Expenses</button>
                    <span class="vert-sep"></span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row page-middle-btn">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body panel-breadcrumb-action"> <?php echo show_validation_errors(); ?>
                        <div class="period-text"></div>
                    </div>
                </section>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <section class="panel">
                <header class="panel-heading theme-panel-heading"><strong>Expenses - Form</strong></header>
                <div class="panel-body">
                    <form id="validate" class="form-horizontal theme-form-horizontal" role="form" method="post"
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->id) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="<?= $row->id; ?>"/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Vendor Name *</label>
                                    <div class="col-lg-6">

                                        <input type="text"
                                               class="form-control validate[required] col-md-12"
                                               id="vendor_name" name="vendor_name" placeholder=""
                                               value="<?php echo $row->vendor_name; ?>">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Vendor Contact *</label>
                                    <div class="col-lg-6">

                                        <input type="text"
                                               class="form-control validate[required,custom[integer]] col-md-12"
                                               id="vendor_contact" name="vendor_contact" placeholder=""
                                               value="<?php echo $row->vendor_contact; ?>">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Vendor Address *</label>
                                    <div class="col-lg-6">

                                        <input type="text"
                                               class="form-control validate[required] col-md-12"
                                               id="vendor_address" name="vendor_address" placeholder=""
                                               value="<?php echo $row->vendor_address; ?>">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Status *</label>
                                    <div class="col-lg-6">

                                        <select name="expense_status" id="expense_status" class="select validate[required]">
                                            <option value="">-Select-</option>
                                            <option value="1" <?php echo $row->expense_status==1 ? "selected" : "" ?>>Paid</option>
                                            <option value="2" <?php echo $row->expense_status==2 ? "selected" : "" ?>>Part Paid</option>
                                            <option value="3" <?php echo $row->expense_status==3 ? "selected" : "" ?>>UnPaid</option>

                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Date *</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="fees_month" placeholder="DD/MM/YYYY"
                                               style="padding: 0 10px;"
                                               class="form-control datepicker-format validate[required]"
                                               name="expense_date"
                                               value="<?= (($row->expense_date != '') ? date('d-m-Y', strtotime($row->expense_date)) : ''); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">Expense Entry *</label>
                                    <div class="form-inline col-md-9" id="invoice_entries">
                                        <?php
                                        if($row->id==""){ ?>
                                            <div class="default-row">
                                                <div class="form-group" style="margin: 0;">
                                                    <input type="text"
                                                           class="form-control validate[required] col-md-12"
                                                           id="label" name="label[]" placeholder="Expense Entry Label"
                                                           value="">
                                                </div>
                                                <div class="form-group" style="margin: 0;">
                                                    <input type="text" class="form-control validate[required,custom[integer]] col-md-12"
                                                           id="amount" name="amount[]" placeholder="Expense Amount"
                                                           value="">
                                                </div>

                                            </div>
                                        <?php }else {
                                            $amount_details = json_decode($row->expense_entry);
                                            $j = 0;
                                            foreach ($amount_details[0] as $key => $val) {
                                                ?>
                                                <div class="<?php echo (($j > 0)?'append_row':'default-row'); ?>">
                                                    <div class="form-group" style="margin: 0;">
                                                        <!--<label for="amount" class="col-lg-3 col-sm-3 control-label">Amount</label>-->
                                                        <input type="text"
                                                               class="form-control validate[required] col-md-12"
                                                               id="label" name="label[]" placeholder="Expense Entry Label"
                                                               value="<?php echo  $amount_details[0][$key]; ?>">
                                                    </div>
                                                    <div class="form-group" style="margin: 0;">
                                                        <input type="text" class="form-control validate[required,custom[integer]] col-md-12"
                                                               id="amount" name="amount[]" placeholder="Expense Amount"
                                                               value="<?php echo  $amount_details[1][$key]; ?>">
                                                        </select>
                                                    </div>

                                                    <?php
                                                    if($j > 0){
                                                        ?>
                                                        <div class="form-group" style="margin: 0;" >
                                                            <button type="button" class="btn btn-warning" onclick="$(this).closest('.append_row').remove();">Remove</button>
                                                        </div>
                                                    <?php }
                                                    ?>
                                                </div>
                                                <?php
                                                $j++ ; }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="addmorebtn" class="col-lg-3 col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-lg-6">
                                        <button class="btn btn-green" type="button" id="add-more-btn">Add More Expense Entry</button>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-md-3">&nbsp;</label>
                                    <div class="col-md-8">
                                        <button type="reset" class="btn btn-black " onclick="window.history.back()">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-green "> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <!-- end data table -->

    </section>
</section>
<div class="add_more_entries" style="display: none;">
    <div class="append_row">

        <div class="form-group" style="margin: 0;">
            <input type="text"
                   class="form-control validate[required] col-md-12"
                   id="label" name="label[]" placeholder="Expense Entry Label"
                   value="">
        </div>
        <div class="form-group" style="margin: 0;">
            <input type="text" class="form-control validate[required,custom[integer]] col-md-12"
                   id="amount" name="amount[]" placeholder="Expense Amount"
                   value="">
        </div>
        <div class="form-group" style="margin: 0;" >
            <button type="button" class="btn btn-warning" onclick="$(this).closest('.append_row').remove();">Remove</button>
        </div>
    </div>
</div>
<!--main content end-->
<?php
include dirname(__FILE__) . "/../includes/footer.php";
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#add-more-btn').click(function (e) {
            $('#invoice_entries > div:last').after($('.add_more_entries').html());
            $('#invoice_entries > div.append_row:last').find('.select-more').select2();
        });
        //$('.select-multiple').select2();
    });
</script>
