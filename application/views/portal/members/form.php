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
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Date of Birth: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="date_of_birth" name="date_of_birth" value="<?=(($row->date_of_birth!='' and $row->date_of_birth!='0000-00-00')?date('d/m/Y',strtotime($row->date_of_birth)):'') ; ?>"
                                               placeholder="DD-MM-YYYY"
                                               class="form-control validate[required] datepicker-format">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Gender: </label>
                                    <div class="col-lg-6">
                                        <label>
                                            <input type="radio" name="gender" value="male" <?php echo (($row->gender == 'male')?'checked':'') ; ?>> Male
                                        </label>
                                        <label>
                                            <input type="radio" name="gender" value="female" <?php echo (($row->gender == 'female')?'checked':'') ; ?>> Female
                                        </label>
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
                                    <label for="inputPassword1" class="col-lg-3 col-sm-3 control-label">Machine Member ID: </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="machine_member_id" name="machine_member_id"
                                               value="<?= $row->machine_member_id; ?>" placeholder="Machine Member ID"
                                               class="form-control validate[required,custom[integer]]">
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
                                    <label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Subscription: </label>
                                    <div class="col-lg-6">
                                        <label class="styled_select">
                                            <select name="subscriptin_id" id="subscriptin_id" class="styled validate[required]">
                                                <option value=""> - Select -</option>
                                                <?= selectBox("SELECT id,CONCAT(`name`,' - ',period_duration) AS subsribe_name FROM `subscriptions` WHERE `status` = 1", $row->subscriptin_id); ?>
                                            </select>
                                            <span class="help-block"></span>
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