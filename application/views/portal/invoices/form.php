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
                    <li><a href="<?php echo site_url(ADMIN_DIR . "invoices"); ?>">Fees Management</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "invoices"); ?>">Invoices</a></li>
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
                    <button class="btn btn-white active" type="button">Invoices</button>
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
                <header class="panel-heading theme-panel-heading"><strong>Invoices - Form</strong></header>
                <div class="panel-body">
                    <form id="validate" class="form-horizontal theme-form-horizontal" role="form" method="post"
                          action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->id) ? '/update' : '/add')); ?>"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="<?= $row->id; ?>"/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-3 col-sm-3 control-label">Member Name</label>
                                    <div class="col-lg-6">
                                        <?php
                                        if(getVar('tempID')!=""){
                                            $row->acc_id = getVar('tempID');
                                        }
                                        ?>


                                        <select name="acc_id" id="acc_id" class="select validate[required]">
                                            <option value="">-Select-</option>
                                            <?php
                                            echo selectBox("select acc_id,CONCAT(machine_member_id,') ',acc_name) AS acc_name from accounts where status = 1 AND machine_member_id!='' order by acc_id desc", $row->acc_id);
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">Invoice Entry Type</label>
                                    <div class="form-inline col-md-9" id="invoice_entries">
                                        <?php
                                        $hideAddmore = 0 ;
                                        if($row->id==""){ ?>
                                            <div class="default-row">
                                                <div class="form-group" style="margin: 0;">
                                                    <select name="type[]" id="type" class="select validate[required]"
                                                            tabindex="-1"
                                                            data-placeholder="Select invoice types">
                                                        <option value="">- select -</option>
                                                        <?php
                                                        echo selectBox("select `id`,`name` from `invoice_types`", $val);
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="margin: 0;">
                                                    <input type="text"
                                                           class="form-control validate[required,custom[integer]] col-md-12"
                                                           id="amount" name="amount[]" placeholder="amount e.g 1500"
                                                           value="<?= $row->amount; ?>">
                                                </div>
                                            </div>
                                        <?php }else {
                                            $type = explode(",",$row->type);
                                            $amount_details = json_decode($row->amount_details);
                                            $j = 0;
                                            if(in_array(1,$type)) {
                                                $hideAddmore = 1;
                                                foreach ($amount_details[0] as $key => $val) { ?>
                                                    <div class="form-group" style="margin: 0;">
                                                        <input type="hidden" name="type[]" value="<?php echo $val; ?>">
                                                        Monthly Fees:

                                                    </div>
                                                    <div class="form-group" style="margin: 0;">
                                                        <!--<label for="amount" class="col-lg-3 col-sm-3 control-label">Amount</label>-->
                                                        <input type="text"
                                                               class="form-control validate[required,custom[integer]] col-md-12"
                                                               id="amount" name="amount[]" placeholder="amount e.g 1500"
                                                               value="<?= $amount_details[1][$key]; ?>">
                                                    </div>

                                                <?php  $j++;
                                                }
                                            }else{


                                                    foreach ($amount_details[0] as $key => $val) {
                                                        ?>
                                                        <div class="<?php echo(($j > 0) ? 'append_row' : 'default-row'); ?>">
                                                            <div class="form-group" style="margin: 0;">
                                                                <select name="type[]" id="type"
                                                                        class="select validate[required]"
                                                                        tabindex="-1"
                                                                        data-placeholder="Select invoice types">
                                                                    <option value="">- select -</option>
                                                                    <?php
                                                                    echo selectBox("select `id`,`name` from `invoice_types` WHERE id!=1", $val);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group" style="margin: 0;">
                                                                <!--<label for="amount" class="col-lg-3 col-sm-3 control-label">Amount</label>-->
                                                                <input type="text"
                                                                       class="form-control validate[required,custom[integer]] col-md-12"
                                                                       id="amount" name="amount[]"
                                                                       placeholder="amount e.g 1500"
                                                                       value="<?= $amount_details[1][$key]; ?>">
                                                            </div>
                                                            <?php
                                                            if ($j > 0) {
                                                                ?>
                                                                <div class="form-group" style="margin: 0;">
                                                                    <button type="button" class="btn btn-warning"
                                                                            onclick="$(this).closest('.append_row').remove();">
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            <?php }
                                                            ?>
                                                        </div>
                                                        <?php
                                                        $j++;
                                                    }
                                                }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if($hideAddmore==0){?>
                                <div class="form-group">
                                    <label for="addmorebtn" class="col-lg-3 col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-lg-6">
                                        <button class="btn btn-green" type="button" id="add-more-btn">Add More Invoice Entry</button>
                                    </div>
                                </div>
                                <?php }?>
                                <div class="form-group">
                                    <label for="description" class="col-lg-3 col-sm-3 control-label">Description</label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" name="description" id="description" cols="30"
                                                  rows="5"><?= $row->description;; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Fees Period</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="fees_month" placeholder="DD/MM/YYYY"
                                               style="padding: 0 10px;"
                                               class="form-control datepicker-format validate[required]"
                                               name="fees_month"
                                               value="<?= (($row->fees_month != '') ? date('d-m-Y', strtotime($row->fees_month)) : ''); ?>">
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label for="service_charges" class="col-lg-3 col-sm-3 control-label">Status</label>
                                    <div class="col-lg-6">
                                        <label class="styled_select">
                                            <select name="status" id="status" class="styled validate[required]">
                                                <option value="1" <? /*= (($row->status == "1") ? "selected" : ""); */ ?>>
                                                    Active
                                                </option>
                                                <option value="2" <? /*= (($row->status == "0") ? "selected" : ""); */ ?>>
                                                    Inactive
                                                </option>
                                            </select>
                                        </label>
                                    </div>
                                </div>-->
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
            <select name="type[]" id="type" class="select-more"
                    tabindex="-1"
                    data-placeholder="Select invoice types">
                <option value="">- select -</option>
                <?php
                if($row->id!="" ) {
                    echo selectBox("select `id`,`name` from `invoice_types` WHERE id!='1'");
                }else{
                    echo selectBox("select `id`,`name` from `invoice_types` ");
                }
                ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0;" >
            <input type="text"
                   class="form-control  col-md-12"
                   id="amount" name="amount[]" placeholder="amount e.g 1500"
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
