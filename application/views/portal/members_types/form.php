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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members_types"); ?>">Members</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members_types"); ?>">Members Type</a></li>
                    <li class="active"><?php echo(!empty($row->acc_type_ID) ? 'Edit' : 'Add'); ?></li>
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
                    <button class="btn btn-white active" type="button">Member Type</button>
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
                <header class="panel-heading theme-panel-heading"><strong>Member Type - Form</strong></header>
                <div class="panel-body">
                    <form id="validate" class="form-horizontal theme-form-horizontal" role="form" method="post"
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->acc_type_ID) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="acc_type_ID" id="acc_type_ID" value="<?= $row->acc_type_ID; ?>"/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Type
                                        Name</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="Name" name="Name"
                                               value="<?= $row->Name; ?>" placeholder="Type Name"
                                               class="form-control validate[required]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Description" class="col-lg-3 col-sm-3 control-label">Description</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="Description" name="Description" value="<?= $row->Description; ?>"
                                               placeholder="Description" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service_period" class="col-lg-3 col-sm-3 control-label">Service Period</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="service_period" name="service_period" value="<?= (($row->service_period=='')?'30':$row->service_period); ?>"
                                               placeholder="Enter days number e.g 30 for 30 days "
                                               class="form-control validate[required,custom[integer]]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service_charges" class="col-lg-3 col-sm-3 control-label">Service Charges</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="service_charges" name="service_charges" value="<?= $row->service_charges; ?>"
                                               placeholder="Service Charges"
                                               class="form-control validate[required,custom[integer]]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="service_offered" class="col-lg-3 col-sm-3 control-label">Service Offered</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="service_offered" name="service_offered" value="<?= $row->service_offered; ?>"
                                               placeholder="Service Offered"
                                               class="form-control validate[required]">
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
<!--main content end-->
<?php
include dirname(__FILE__) . "/../includes/footer.php";

?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#reseller').on('change', function () {
                var user_type = $('#user_type').val();
                var reseller = $(this).val();
                if (user_type == "customer") {
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: '<?= site_url(ADMIN_DIR . $this->module_name . '/AJAX/reseller_companies'); ?>',
                        data: 'reseller_id=' + reseller,
                        success: function (JSON) {
                            $('#company').removeAttr('disabled').html(JSON.data);
                            $('#uniform-company').removeClass('disabled');
                        }
                    });
                }
            });
            /*-------------------------------------------------------------------------*/
            $('#user_type').on('change', function () {
                var user_type = $(this).val();

                switch (user_type) {
                    case 'customer':
                        $('#reseller,#company').addClass('validate[required]');
                        $('#reseller,#first_name,#surname,#email,#office_phone,#mob_phone,#fax').removeAttr('disabled');
                        $('#uniform-company').removeClass('disabled');
                        $('#company').removeAttr('disabled');
                        break;
                    case 'reseller':
                        $('#company').attr("disabled", "disabled").removeClass('validate[required]');
                        $('#reseller').removeAttr('disabled');
                        $('#reseller').addClass('validate[required]');
                        $('#first_name,#surname,#email,#office_phone,#mob_phone,#fax').removeAttr('disabled');

                        break;
                    case 'administrator':
                    case 'super admin':
                        $('#reseller, #company').attr("disabled", "disabled").removeClass('validate[required]');
                        $('#first_name,#surname,#email,#office_phone,#mob_phone,#fax').removeAttr('disabled');
                        break;
                    default :
                        $('#reseller,#company,#first_name,#surname,#email,#office_phone,#mob_phone,#fax').attr('disabled', 'disabled');
                        break;
                }
            });

            $('#user_type').trigger('change');
        });
    })(jQuery)
</script>