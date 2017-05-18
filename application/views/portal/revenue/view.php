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
            <div class="clearfix">&nbsp;</div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <section class="panel">
                    <div class="panel-body invoice">
                        <div class="invoice-header">
                            <div class="invoice-title col-md-3 col-xs-2">
                                <h1>invoice</h1>
                                <img class="logo-print" src="images/bucket-logo.png" alt="">
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
                        </div>
                        <div class="row invoice-to">
                            <div class="col-md-4 col-sm-4 pull-left">
                                <h4>Invoice To:</h4>
                                <h2>Envato</h2>
                                <p>
                                    121 King Street, Melbourne<br>
                                    Victoria 3000 Australia<br>
                                    Phone: +61 3 8376 6284<br>
                                    Email : info@envato.com
                                </p>
                            </div>
                            <div class="col-md-4 col-sm-5 pull-right">
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Invoice #</div>
                                    <div class="col-md-8 col-sm-7">233426</div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Date #</div>
                                    <div class="col-md-8 col-sm-7">21 December 2013</div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 inv-label">
                                        <h3>TOTAL DUE</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <h1 class="amnt-value">$ 3120.00</h1>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <table class="table table-invoice">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Description</th>
                                <th class="text-center">Unit Cost</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <h4>Service One</h4>
                                    <p>Service Four Description Lorem ipsum dolor sit amet.</p>
                                </td>
                                <td class="text-center">1</td>
                                <td class="text-center">4</td>
                                <td class="text-center">$1300.00</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <h4>Service Two</h4>
                                    <p>Service Four Description Lorem ipsum dolor sit amet.</p>
                                </td>
                                <td class="text-center">2</td>
                                <td class="text-center">5</td>
                                <td class="text-center">$1300.00</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <h4>Service Three</h4>
                                    <p>Service Four Description Lorem ipsum dolor sit amet.</p>
                                </td>
                                <td class="text-center">1</td>
                                <td class="text-center">9</td>
                                <td class="text-center">$1300.00</td>
                            </tr>

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-8 col-xs-7 payment-method">
                                <h4>Payment Method</h4>
                                <p>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p>2. Pellentesque tincidunt pulvinar magna quis rhoncus.</p>
                                <p>3. Cras rhoncus risus vitae congue commodo.</p>
                                <br>
                                <h3 class="inv-label itatic">Thank you for your business</h3>
                            </div>
                            <div class="col-md-4 col-xs-5 invoice-block pull-right">
                                <ul class="unstyled amounts">
                                    <li>Sub - Total amount : $3820</li>
                                    <li>Discount : 10% </li>
                                    <li>TAX (15%) ----- </li>
                                    <li class="grand-total">Grand Total : $7145</li>
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