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
                                        switch ($row->type){
                                            case 1:
                                                echo "Monthly Fees";
                                            break;
                                            case 2:
                                               echo  'Subscription Fees';
                                                break;
                                            case 3:
                                                echo  'Member Type';
                                                break;
                                            case 4:
                                                echo  'Special Days Fees';
                                                break;
                                            default:
                                                echo  'Other';
                                                break;
                                        }
                                        ?></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Date #</div>
                                    <div class="col-md-8 col-sm-7"><?=date('d M y') ; ?></div>
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
                        <table class="table table-invoice">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fees Description</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Month</th>
                                <th class="text-center">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            ?>
                            <tr>
                                <td>1</td>
                                <td>
                                    <h4>Monthly Fees</h4>
                                    <p>Monthly Charges Paid by member.</p>
                                </td>
                                <td class="text-center">300</td>
                                <td class="text-center">1</td>
                                <td class="text-center">Rs. 300</td>
                            </tr>
                            <?php ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-8 col-xs-7 payment-method">
                                <!--<h4>Payment Method</h4>
                                <p>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p>2. Pellentesque tincidunt pulvinar magna quis rhoncus.</p>
                                <p>3. Cras rhoncus risus vitae congue commodo.</p>
                                <br>-->
                                <h3 class="inv-label itatic">Thank you for the payment.</h3>
                            </div>
                            <div class="col-md-4 col-xs-5 invoice-block pull-right">
                                <ul class="unstyled amounts">
                                    <!--<li>Sub - Total amount : $3820</li>
                                    <li>Discount : 10% </li>
                                    <li>TAX (15%) ----- </li>-->
                                    <li class="grand-total">Total : Rs. 300</li>
                                </ul>
                            </div>
                        </div>

                        <div class="text-center invoice-btn">
                            <a class="btn btn-success btn-lg"><i class="fa fa-check"></i> Submit Invoice </a>
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