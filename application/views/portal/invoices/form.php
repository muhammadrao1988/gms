<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
<style type="text/css">
    .form-group div.acc_idformError, .form-group div.typeformError {
        left: 180px !important;
    }

    .append_row {
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

                                <?php
                                if ($firstInvoice == 1 && $tempID > 0) {

                                    $subscription_id = $tempRow->acc_types;
                                    $invoice_generate_date = $tempRow->invoice_generate_date;
                                    $machine_member_id = $tempRow->machine_member_id;
                                    $acc_name = $tempRow->acc_name;

                                    $fees_per_month = getVal("acc_types", "monthly_charges", " WHERE acc_type_ID='" . $subscription_id . "'");

                                    $month_due = floor(dayDifference($invoice_generate_date) / 30);
                                    $register_day = date('d', strtotime($invoice_generate_date));

                                    ?>
                                    <input type='hidden' name='firstInvoice' value='1'>
                                    <input type='hidden' name='tempID' value='<?php echo $tempRow->acc_id; ?>'>
                                    <input type='hidden' name='acc_id' value='<?php echo $tempRow->acc_id ?>'>
                                    <input type='hidden' name='fees_per_month' value='<?php echo $fees_per_month ?>'>
                                    <input type='hidden' name='invoice_generate_date'
                                           value='<?php echo $invoice_generate_date ?>'>
                                    <div class="form-group">
                                        <label for="Name" class="col-lg-3 col-sm-3 control-label">Member Name:</label>
                                        <label class="col-lg-6"
                                               style="margin-top: 7px"><?php echo(($machine_member_id!='')? $machine_member_id . ") ":"" ). $acc_name; ?></label>

                                    </div>
                                    <div class="form-group" style="border-top:3px  dotted;padding-top: 10px">
                                        <label for="Name" class="col-lg-3 col-sm-3 control-label">Per Month Fee:</label>
                                        <div class="col-lg-6" style="margin-top: 7px">
                                            <?php echo $fees_per_month; ?>
                                        </div>
                                    </div>

                                    <?php

                                    if ($month_due > 0) { ?>
                                        <div class="form-group">
                                            <label for="Name" class="col-lg-3 col-sm-3 control-label">Monthly Fee
                                                Invoice</label>
                                            <div class="col-lg-6">
                                                <?php
                                                if ($register_day <= date('d')) {

                                                    $datetime = new DateTime(date('Y-m-') . '01');
                                                    $datetime2 = new DateTime(date('Y-m-') . '01');
                                                } else {

                                                    $currentMonth = date('F');
                                                    $previous_date = date('Y-m', strtotime($currentMonth . " last month")) . "-01";
                                                    $datetime = new DateTime($previous_date);
                                                    $datetime2 = new DateTime($previous_date);
                                                }
                                                for ($i = 1; $i <= $month_due; $i++) {


                                                    ?>

                                                    <?php
                                                    if ($i == 1) {
                                                        $from_month = $datetime;
                                                    } else {
                                                        $from_month = $datetime->modify("-1 month");
                                                    }
                                                    $from_month = $datetime->format("MY");


                                                    if ($i == 1) {
                                                        $to_month = $datetime2->modify("+1 month");
                                                        //$to_month = date( "MY");
                                                    } else {
                                                        $to_month = $datetime2->modify("-1 month");

                                                    }
                                                    $to_month = $datetime2->format("MY");

                                                    if (strpos($from_month, 'Feb') !== false && $register_day > 28) {
                                                        $register_day_from = 28;
                                                    } else if ($register_day > 30 && (strpos($from_month, 'Apr') !== false OR
                                                            strpos($from_month, 'Jun') !== false OR
                                                            strpos($from_month, 'Sep') !== false OR
                                                            strpos($from_month, 'Nov') !== false
                                                        )
                                                    ) {
                                                        $register_day_from = 30;
                                                    } else {
                                                        $register_day_from = $register_day;
                                                    }


                                                    if (strpos($to_month, 'Feb') !== false && $register_day > 28) {
                                                        $register_day_to = 28;
                                                    } else if ($register_day > 30 && (strpos($to_month, 'Apr') !== false OR
                                                            strpos($to_month, 'Jun') !== false OR
                                                            strpos($to_month, 'Sep') !== false OR
                                                            strpos($to_month, 'Nov') !== false
                                                        )
                                                    ) {
                                                        $register_day_to = 30;
                                                    } else {
                                                        $register_day_to = $register_day;
                                                    }

                                                    echo "<input  style='float:left;margin-top:3px;margin-right:5px' type='hidden' name='fees_month[]' class='due_fees' data-count='" . $i . "' id='fees_" . $i . "' value='" . date('Y-m', strtotime($from_month)) . "-" . $register_day_from . "|" . date('Y-m', strtotime($to_month)) . "-" . $register_day_to . "'>";
                                                    echo " From " . $register_day_from . " " . $from_month;
                                                    echo " To " . $register_day_to . " " . $to_month;

                                                    echo "<br>";
                                                    //$datetime = new DateTime(date('Y-m-d'));

                                                }
                                                ?>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name" class="col-lg-3 col-sm-3 control-label">Choose
                                                Option</label>
                                            <div class="col-lg-6">
                                                <label>
                                                    <input name="invoice_option"
                                                           class="invoice_option validate[required]" value="1"
                                                           type="radio"> Continue with above invoices
                                                </label>
                                                <label>
                                                    <input name="invoice_option" value="2"
                                                           class="invoice_option validate[required]" type="radio">
                                                    Cancel all invoices and create invoice from today date
                                                </label>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name" class="col-lg-3 col-sm-3 control-label">Monthly Invoice
                                                Total </label>
                                            <div class="col-lg-6 fee_total_amount" style="margin-top: 8px"> 0
                                            </div>
                                        </div>
                                        <input type='hidden' name='is_register_old' value='1'>
                                    <?php } else {
                                        ?>
                                        <input type='hidden' name='is_register_old' value='0'>
                                        <div class="form-group">
                                            <label for="Name" class="col-lg-3 col-sm-3 control-label">Monthly Fee
                                                Invoice:</label>
                                            <div class="col-lg-6" style="margin-top: 7px">
                                                <?php $datetime2 = new DateTime(date('Y-m', strtotime($invoice_generate_date)) . '-01');
                                                $to_month = $datetime2->modify("+1 month");
                                                $to_month = $datetime2->format("MY");

                                                if (strpos($to_month, 'Feb') !== false && $register_day > 28) {
                                                    $register_day_to = 28;
                                                } else if ($register_day > 30 && (strpos($to_month, 'Apr') !== false OR
                                                        strpos($to_month, 'Jun') !== false OR
                                                        strpos($to_month, 'Sep') !== false OR
                                                        strpos($to_month, 'Nov') !== false
                                                    )
                                                ) {
                                                    $register_day_to = 30;
                                                } else {
                                                    $register_day_to = $register_day;
                                                }
                                                echo "<input  style='float:left;margin-top:3px;margin-right:5px' type='hidden' name='fees_month[]' class='due_fees' data-count='" . $i . "' id='fees_" . $i . "' value='" . date('Y-m-d', strtotime($invoice_generate_date)) . "|" . date('Y-m', strtotime($to_month)) . "-" . $register_day_to . "'>";
                                                echo " From " . date('d MY', strtotime($invoice_generate_date));
                                                echo " To " . $register_day_to . " " . $to_month;
                                                ?>
                                            </div>
                                        </div>


                                    <?php }
                                    ?>
                                    <div class="form-group" style="border-top:3px  dotted;padding-top: 10px">
                                        <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">Other
                                            Invoice
                                            Entry:</label>
                                        <div class="form-inline col-md-9" id="invoice_entries">
                                            <div class="default-row">
                                                <div class="form-group" style="margin: 0;">
                                                    <select name="type[]" id="type" class="select validate[required]"
                                                            tabindex="-1"
                                                            data-placeholder="Select invoice types">
                                                        <option value="">- select -</option>
                                                        <?php
                                                        echo selectBox("select `id`,`name` from `invoice_types` WHERE id!=1", $val);
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

                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="addmorebtn" class="col-lg-3 col-sm-3 control-label">&nbsp;</label>
                                        <div class="col-lg-6">
                                            <button class="btn btn-green" type="button" id="add-more-btn">Add More
                                                Invoice Entry
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-lg-3 col-sm-3 control-label">Invoice
                                            Status:</label>
                                        <div class="col-lg-6">
                                            <select name="state" id="state" class="select validate[required]"
                                                    tabindex="-1"
                                                    data-placeholder="Select invoice types">
                                                <option value="">Select Status:</option>
                                                <option value="1" <?php echo $row - state == 1 ? "selected" : ""; ?>>
                                                    Paid
                                                </option>
                                                <option value="2" <?php echo $row - state == 2 ? "selected" : ""; ?>>
                                                    Partial
                                                    Paid
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group partial_paid_div"
                                         style="<?php echo(($row - state == 1 OR $row - state == "") ? "display: none;" : ""); ?>">
                                        <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Received
                                            Amount:</label>
                                        <div class="col-lg-6">
                                            <input type="text" id="received_amount" placeholder=""
                                                   style="padding: 0 10px;"
                                                   class="form-control  validate[required,custom[integer]]"
                                                   name="received_amount"
                                                   value="<?php echo $row->received_amount; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fees_month"
                                               class="col-lg-3 col-sm-3 control-label">Discount:</label>
                                        <div class="col-lg-6">
                                            <input type="text" id="discount" placeholder=""
                                                   style="padding: 0 10px;"
                                                   class="form-control  validate[custom[integer]]"
                                                   name="discount"
                                                   value="<?php echo $row->discount; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description"
                                               class="col-lg-3 col-sm-3 control-label">Description:</label>
                                        <div class="col-lg-6">
                                        <textarea class="form-control" name="description" id="description" cols="30"
                                                  rows="5"><?= $row->description;; ?></textarea>
                                        </div>
                                    </div>

                                    <?php


                                } else {
                                    ?>


                                    <div class="form-group">
                                        <label for="Name" class="col-lg-3 col-sm-3 control-label">Member Name:</label>

                                        <div class="col-lg-6" <?php echo(($row->id > 0) ? "style='margin-top:7px'" : ""); ?>>
                                            <?php
                                            if ($row->id > 0) {
                                                $machine_id = getVal("accounts", "machine_member_id", " WHERE acc_id='" . $row->acc_id . "'");
                                                $acc_name = getVal("accounts", "acc_name", " WHERE acc_id='" . $row->acc_id . "'");
                                                echo $machine_id . ") " . $acc_name;
                                                echo "<input type='hidden' name='acc_id' value='" . $row->acc_id . "'>";
                                            } else {

                                                ?>


                                                <select name="acc_id" id="acc_id" class="select validate[required]">
                                                    <option value="">-Select-</option>
                                                    <?php
                                                    if($this->session->userdata('user_info')->is_machine==1){
                                                        echo selectBox("select acc_id,CONCAT(machine_member_id,') ',acc_name) AS acc_name from accounts where status = 1 AND machine_member_id!=''  AND branch_id='" . $this->session->userdata('user_info')->branch_id . "' order by acc_id desc", $row->acc_id);
                                                    }else{
                                                        echo selectBox("select acc_id,CONCAT(acc_id,') ',acc_name) AS acc_name from accounts where status = 1  AND branch_id='" . $this->session->userdata('user_info')->branch_id . "' order by acc_id desc", $row->acc_id);
                                                    }

                                                    ?>
                                                </select>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <?php if ($row->id != "") {
                                        $fees_invoice_array = array();
                                        $amount_details = json_decode($row->amount_details);
                                        $fees_invoice_array = $amount_details->fee_invoice;


                                        if (count($fees_invoice_array) > 0) { ?>
                                            <div class="form-group">
                                                <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">
                                                    Monthly Fee Invoice:</label>
                                                <div class="col-lg-6">
                                                    <?php $amount = 0;
                                                    foreach ($fees_invoice_array as $fee) {
                                                        $duration = explode("|", $fee->duration);
                                                        $from_date = "From " . date("d MY", strtotime($duration[0]));
                                                        $to_date = " To " . date("d MY", strtotime($duration[1]));
                                                        echo $from_date . $to_date;
                                                        echo "<br>";
                                                        $amount = $amount + $fee->amount;

                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">
                                                    Total Monthly Fee Amount:</label>
                                                <div class="col-lg-6" style="margin-top: 7px">
                                                    <?php echo number_format($amount); ?>
                                                </div>
                                            </div>

                                        <?php }


                                    } ?>

                                    <div class="form-group">
                                        <label for="invoice" class="col-lg-3 col-md-3 col-sm-3 control-label">
                                            Invoice Entry:</label>
                                        <div class="form-inline col-md-9" id="invoice_entries">
                                            <?php
                                            $hideAddmore = 0;
                                            if ($row->id == "") { ?>
                                                <div class="default-row">
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
                                                        <input type="text"
                                                               class="form-control validate[required,custom[integer]] col-md-12"
                                                               id="amount" name="amount[]" placeholder="amount e.g 1500"
                                                               value="<?= $row->amount; ?>">
                                                    </div>
                                                </div>
                                            <?php } else {
                                                $type = explode(",", $row->type);


                                                $other_invoice_array = $amount_details->other_invoice;


                                                $j = 0;


                                                foreach ($other_invoice_array as $ad) { ?>


                                                    <div class="<?php echo(($j > 0) ? 'append_row' : 'default-row'); ?>">
                                                        <div class="form-group" style="margin: 0;">
                                                            <select name="type[]" id="type"
                                                                    class="select validate[required]"
                                                                    tabindex="-1"
                                                                    data-placeholder="Select invoice types">
                                                                <option value="">- select -</option>
                                                                <?php
                                                                echo selectBox("select `id`,`name` from `invoice_types` WHERE id!=1", $ad->type);
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" style="margin: 0;">
                                                            <!--<label for="amount" class="col-lg-3 col-sm-3 control-label">Amount</label>-->
                                                            <input type="text"
                                                                   class="form-control validate[required,custom[integer]] col-md-12"
                                                                   id="amount" name="amount[]"
                                                                   placeholder="amount e.g 1500"
                                                                   value="<?= $ad->amount; ?>">
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


                                                $j = 0;

                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php if ($hideAddmore == 0) { ?>
                                        <div class="form-group">
                                            <label for="addmorebtn"
                                                   class="col-lg-3 col-sm-3 control-label">&nbsp;</label>
                                            <div class="col-lg-6">
                                                <button class="btn btn-green" type="button" id="add-more-btn">Add More
                                                    Invoice Entry
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label for="description" class="col-lg-3 col-sm-3 control-label">Invoice
                                            Status</label>
                                        <div class="col-lg-6">
                                            <select name="state" id="state" class="select validate[required]"
                                                    tabindex="-1"
                                                    data-placeholder="Select invoice types">
                                                <option value="">Select Status</option>
                                                <option value="1" <?php echo $row->state == 1 ? "selected" : ""; ?>>Paid
                                                </option>
                                                <option value="2" <?php echo $row->state == 2 ? "selected" : ""; ?>>
                                                    Partial
                                                    Paid
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group partial_paid_div"
                                         style="<?php echo(($row - state == 1 OR $row - state == "") ? "display: none;" : ""); ?>">
                                        <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Received
                                            Amount</label>
                                        <div class="col-lg-6">
                                            <input type="text" id="received_amount" placeholder=""
                                                   style="padding: 0 10px;"
                                                   class="form-control  validate[required,custom[integer]]"
                                                   name="received_amount"
                                                   value="<?php echo($row->state == 2 ? $row->received_amount : ""); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Discount</label>
                                        <div class="col-lg-6">
                                            <input type="text" id="discount" placeholder=""
                                                   style="padding: 0 10px;"
                                                   class="form-control  validate[custom[integer]]"
                                                   name="discount"
                                                   value="<?php echo $row->discount; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description"
                                               class="col-lg-3 col-sm-3 control-label">Description</label>
                                        <div class="col-lg-6">
                                        <textarea class="form-control" name="description" id="description" cols="30"
                                                  rows="5"><?= $row->description;; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>

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
                                <?php if ($row->state != "3") { ?>
                                    <button type="submit" class="btn btn-green "> Submit</button>
                                <?php } ?>
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
                if ($row->id != "") {
                    echo selectBox("select `id`,`name` from `invoice_types` WHERE id!='1'");
                } else {
                    echo selectBox("select `id`,`name` from `invoice_types` WHERE id!='1' ");
                }
                ?>
            </select>
        </div>
        <div class="form-group" style="margin: 0;">
            <input type="text"
                   class="form-control  col-md-12"
                   id="amount" name="amount[]" placeholder="amount e.g 1500"
                   value="">
        </div>
        <div class="form-group" style="margin: 0;">
            <button type="button" class="btn btn-warning" onclick="$(this).closest('.append_row').remove();">Remove
            </button>
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
<script type="text/javascript">
    $(document).ready(function () {

        $('input[name="invoice_option"]').click(function () {

            var per_month_fee = '<?php echo $fees_per_month?>';
            var total_month = '<?php echo $month_due;?>';

            if ($('input:radio[name=invoice_option]:checked').val() == "1") {

                var total_amount = parseInt(per_month_fee) * parseInt(total_month);
                $('.fee_total_amount').text(total_amount);
            } else {

                $('.fee_total_amount').text(per_month_fee);
            }
        });


        if ($("#state").val() == 2) {
            $('.partial_paid_div').show();
        }
        $('#state').change(function () {
            if ($(this).val() == 2) {
                $('.partial_paid_div').show();
            } else {
                $('.partial_paid_div').hide();
            }
        })
    });
</script>