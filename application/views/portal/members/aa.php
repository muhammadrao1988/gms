<?php

include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
<!--main content start-->

<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">
                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">Members</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">All <?php echo $title ; ?></a></li>
                    <li class="active"><?php echo(!empty($row->acc_id) ? 'Edit' : 'Add'); ?></li>
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
                    <button class="btn btn-white active" type="button">All <?php echo $title ; ?></button>
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
                <header class="panel-heading theme-panel-heading"><strong><?php echo $title ; ?> - Form</strong></header>
                <div class="panel-body">
                    <form id="validate" class="form-horizontal theme-form-horizontal" role="form" method="post"
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->acc_id) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="acc_id" id="acc_id" value="<?= $row->acc_id; ?>"/>
                        <div class="row">

                            <div class="col-md-12">
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
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!-- end data table -->
    </section>
</section>
<!--main content end-->
<?php
include dirname(__FILE__) . "/../includes/footer.php";

?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {

            $('#subscriptin_id').on('change', function () {
                var id = $(this).val();
                if(id !='') {
                    $.ajax({
                        type: "POST",
                        url: '<?= site_url(ADMIN_DIR . $this->module_name . '/ajax/getSubscriptionCharges'); ?>',
                        data: "&id=" + id,
                        complete: function (data) {
                            //alert(data.responseText);
                            $('#subscriptin_id').closest('.styled_select').find('.help-block').html(data.responseText+' rupess charges for this subscription.');
                        }
                    });
                }else{
                    $('#subscriptin_id').closest('.styled_select').find('.help-block').html('');
                }
            });
            $('#subscriptin_id').trigger('change');
        });
    })(jQuery)
</script>