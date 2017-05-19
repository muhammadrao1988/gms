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
                        <li><a href="<?php echo site_url(ADMIN_DIR . "invoices"); ?>">Fees Management</a></li>
                        <li><a href="<?php echo site_url(ADMIN_DIR . "invoices/view/".$row->id); ?>">Invoice</a></li>
                        <li class="active">View</li>
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
                        <button class="btn btn-white active" type="button">View Invoice</button>
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
                    <div class="panel-body invoice">
                        <!--<div class="invoice-header">
                            <div class="invoice-title col-md-3 col-xs-2">
                                <h1>invoice</h1>
                            </div>
                            <div class="invoice-info col-md-9 col-xs-10">

                                <div class="pull-right">
                                    <div class="col-md-6 col-sm-6 pull-left">
                                        <p>121 King Street, Melbourne <br>
                                            Victoria 3000 Australia</p>
                                    </div>
                                    <div class="col-md-6 col-sm-6 pull-right">
                                        <p>Phone: +61 3 8376 6284 <br>
                                            Email : info@envato.com</p>
                                    </div>
                                </div>

                            </div>
                        </div>-->
                        <div class="row invoice-to">
                            <div class="col-md-4 col-sm-4 pull-left">
                                <h4>Invoice To:</h4>
                                <h2>Bodyshape</h2>
                                <p>
                                    karachi,Pakistan<br>
                                    Phone: + 92 3213828191<br>
                                </p>
                            </div>
                            <div class="col-md-5 col-sm-5 pull-right">
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Invoice #</div>
                                    <div class="col-md-8 col-sm-7"><?=$row->id ; ?></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Member Name #</div>
                                    <div class="col-md-8 col-sm-7"><?=$row2->acc_name ; ?></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Invoice Type #</div>
                                    <div class="col-md-8 col-sm-7"><?php
                                        $type[0] = $row->type;
                                        echo $invoice_types =  invoice_for($type);
                                        ?></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Date #</div>
                                    <div class="col-md-8 col-sm-7"><?=date('d M Y') ; ?></div>
                                </div>
                                <br>
                                <!--<div class="row">
                                    <div class="col-md-12 inv-label">
                                        <h3>TOTAL DUE</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <h1 class="amnt-value">$ 3120.00</h1>
                                    </div>
                                </div>-->


                            </div>
                        </div>
                        <?php
                        $now = time();
                        $fees_date = $val[0];
                        $your_date = strtotime(date('Y-m',strtotime($row->fees_month)).'-'.date('d',strtotime($row2->acc_date)));
                        $datediff = $now - $your_date;
                        $month = floor($datediff / (60 * 60 * 24 * 30));
                        if($month =='0' || $row->status == '2') {
                            ?>
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fees Description</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Fees Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                $grand_total = '';
                                $amount_details = json_decode($row->amount_details);
                                foreach ($amount_details[0] as $key=>$val) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <h4><?=invoice_for($type[0] =$val); ?></h4>
                                            <!--<p>Monthly Charges Paid by member.</p>-->
                                        </td>
                                        <td class="text-center"><?=$amount_details[1][$key] ; ?></td>
                                        <td class="text-center"><?=date('F',strtotime($row->fees_month)) ; ?></td>
                                        <!--<td class="text-center">Rs. 300</td>-->
                                    </tr>
                                    <?php $i++;
                                    $grand_total +=$amount_details[1][$key];
                                } ?>
                                </tbody>
                            </table>
                            <?php
                        }else{ ?>
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fees Description</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Month</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <h4>Montly Fees</h4>
                                            <!--<p>Monthly Charges Paid by member.</p>-->
                                        </td>
                                        <td class="text-center"><?php echo MONTHLY_FEES; ?></td>
                                        <td class="text-center"><?php echo $month ; ?></td>
                                        <!--<td class="text-center">Rs. 300</td>-->
                                    </tr>

                                </tbody>
                            </table>
                        <?php
                        $grand_total = $month*MONTHLY_FEES;
                        }
                            ?>
                        <div class="row">
                            <div class="col-md-8 col-xs-7 payment-method">
                                <!--<h4>Payment Method</h4>
                                <p>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p>2. Pellentesque tincidunt pulvinar magna quis rhoncus.</p>
                                <p>3. Cras rhoncus risus vitae congue commodo.</p>
                                <br>-->
                                <h3 class="inv-label itatic">Thank you for the payment.</h3>
                            </div>
                            <div class="col-md-2 col-xs-5 invoice-block pull-right">
                                <ul class="unstyled amounts">
                                    <!--<li>Sub - Total amount : $3820</li>
                                    <li>Discount : 10% </li>
                                    <li>TAX (15%) ----- </li>-->
                                    <li class="grand-total text-center">Total : <?=$grand_total ; ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="text-center invoice-btn">
                            <!--<a class="btn btn-success btn-lg"><i class="fa fa-check"></i> Submit Invoice </a>-->
                            <a href="invoice_print.html" target="_blank" class="btn btn-primary btn-lg"><i class="fa fa-print"></i> Print </a>
                        </div>

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