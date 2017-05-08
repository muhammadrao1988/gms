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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">Admin</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "users_admin"); ?>">Users</a></li>
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
                    <button class="btn btn-white active" type="button">Users</button>
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
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->id) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="<?= $row->id; ?>"/>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Full Name:</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="acc_name" name="acc_name"
                                               value="<?= $row->acc_name; ?>" placeholder="Full Name"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Email: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="email" name="email" value="<?= $row->email; ?>"
                                               placeholder="example@domain.com:"
                                               class="form-control validate[required,custom[email]]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Mobile
                                        Number: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="acc_tel" name="acc_tel"
                                               value="<?= $row->acc_tel; ?>" placeholder="Mobile Number"
                                               class="form-control validate[required,custom[phone]]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Address: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="address" name="address"
                                               value="<?= $row->address; ?>" placeholder="Address"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">City: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="city" name="city"
                                               value="<?= $row->city; ?>" placeholder="City"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Country: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="country" name="country"
                                               value="<?= $row->country; ?>" placeholder="Country"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Member Type: </label>
                                    <div class="col-lg-6">
                                        <label class="styled_select">
                                            <select name="acc_types" id="acc_types" class="styled validate[required]">
                                                <option value=""> - Select -</option>
                                                <?= selectBox("SELECT `acc_type_ID`, `Name` FROM `acc_types`", $row->acc_types); ?>
                                            </select>
                                        </label>
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