<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/print_invoice.css') ; ?>">
<style>
    .total_div{
        background: #1fb5ad !important;
        color: #fff;
    }
    .total_div:hover{
        color: #000;
    }
    @media print {
        body {
            visibility: hidden;

        }

        body * {
            visibility: hidden;

        }

        #print_page {
            visibility: visible;
            background-color: red;
        }

        #print_page * {
            visibility: visible;
            background-color: red;
        }

        /*table tbody tr td{
            border: 1px solid #000000;
        }*/
    }
</style>
<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "invoices"); ?>">Fees Management</a></li>
                    <li><a href="<?php echo site_url(ADMIN_DIR . "invoices/view/" . $row->id); ?>">View Invoice</a></li>
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
                    <div id="print_page">
                        <div class="row invoice-to" style="padding: 15px;">
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
                                    <div class="col-md-8 col-sm-7"><?php echo ((getUri(4)!='')?getUri(4):getVar('id')); ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Member Name :</div>
                                    <div class="col-md-8 col-sm-7"><?= $row2->acc_name; ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Invoice Type :</div>
                                    <div class="col-md-8 col-sm-7"><?php
                                        $invoice_types = '';
                                        foreach ($rows as $row) {
                                            $type[0] = $row->type;
                                            $invoice_types .= invoice_for($type).',';
                                        }
                                        echo trim($invoice_types,',');
                                        ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-5 inv-label">Generated Date :</div>
                                    <div class="col-md-8 col-sm-7"><?= date('d M Y',strtotime($row->fees_datetime)); ?></div>
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
                        <div style="text-align: center; font-size: 20px">
                            <strong>
                                STATUS:
                                <?php
                                if($row->state==1){
                                    echo "PAID";
                                }else if($row->state==2){
                                    echo "PARTIAL PAID";
                                }else if($row->state==3){
                                    echo "CANCELLED";
                                }
                                ?>
                            </strong>
                        </div>
                                    <table class="table table-invoice">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fees Description</th>
                                        <th class="text-center">Amount</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $i = 1;
                                    $amount_details = json_decode($row->amount_details);
                                    $amount_details = object2array($amount_details);
                                    $fee_invoice_array =  array();
                                    $other_invoice =  array();
                                    $total_array =  array();
                                    if(is_array($amount_details['fee_invoice'])){
                                        $fee_invoice_array = $amount_details['fee_invoice'];
                                    }
                                    if(is_array($amount_details['other_invoice'])){
                                        $other_invoice = $amount_details['other_invoice'];
                                    }
                                    if(is_array($amount_details['total'])){
                                        $total_array = $amount_details['total'];
                                    }
                                    $i = 1;
                                    if(count($fee_invoice_array) > 0) {
                                        foreach ($fee_invoice_array as $val) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <h4>Monthly Fee Charges</h4>
                                                    <?php

                                                        $duration = explode("|",$val['duration']);
                                                        $from_date = "From ".date("d MY",strtotime($duration[0]));
                                                        $to_date = " To ".date("d MY",strtotime($duration[1]));
                                                        echo "Duration: ".$from_date.$to_date;
                                                    ?>

                                                </td>
                                                <td class="text-center"><?php echo number_format($val['amount']); ?></td>

                                                <!--<td class="text-center">Rs. 300</td>-->
                                            </tr>
                                            <?php $i++;

                                        }
                                    }
                                if(count($other_invoice) > 0) {
                                    foreach ($other_invoice as $val) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td>
                                                <h4><?php echo invoice_name($val['type']); ?></h4>


                                            </td>
                                            <td class="text-center"><?php echo number_format($val['amount']); ?></td>

                                            <!--<td class="text-center">Rs. 300</td>-->
                                        </tr>
                                        <?php $i++;

                                    }
                                }





                                    if(count($total_array) > 0) {

                               ?>

                                    <tr class="grand-total">

                                        <td colspan="2" class="">
                                            <h4 style="text-align: right">Subtotal: </h4>

                                        </td>
                                        <td class="text-center total_div"><?php echo number_format($total_array['subtotal']); ?></td>
                                    </tr>
                                        <tr class="grand-total">

                                            <td colspan="2" class="">
                                                <h4 style="text-align: right">Discount: </h4>

                                            </td>
                                            <td class="text-center total_div"><?php echo number_format($total_array['discount']); ?></td>
                                        </tr>
                                    <?php if($row->state ==2){?>
                                        <tr class="grand-total">

                                            <td colspan="2" class="">
                                                <h4 style="text-align: right">Received Amount: </h4>

                                            </td>
                                            <td class="text-center total_div"><?php echo number_format($total_array['received_amount']); ?></td>
                                        </tr>
                                        <?php }?>
                                    <?php if($row->state > 1){?>
                                        <tr class="grand-total">

                                            <td colspan="2" class="">
                                                <h4 style="text-align: right">Remaining Amount: </h4>

                                            </td>
                                            <td class="text-center total_div"><?php echo number_format($total_array['remaining_amount']); ?></td>
                                        </tr>
                                        <?php }?>
                                        <?php if($row->state==1){?>
                                        <tr class="grand-total">

                                            <td colspan="2" class="">
                                                <h4 style="text-align: right">Total: </h4>

                                            </td>
                                            <td class="text-center total_div"><?php echo number_format($total_array['total']); ?></td>
                                        </tr>
                                            <?php }?>
                                    <?php }?>

                                    </tbody>
                                    </table>



                        <div class="row" style="padding: 15px;">
                            <div class="col-md-8 col-xs-7 payment-method">
                                <!--<h4>Payment Method</h4>
                                <p>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <p>2. Pellentesque tincidunt pulvinar magna quis rhoncus.</p>
                                <p>3. Cras rhoncus risus vitae congue commodo.</p>
                                <br>-->
                                <h3 class="inv-label itatic">Thank you for the payment.</h3>
                            </div>
                            <div class="col-md-2 col-xs-5 invoice-block pull-right">
                                <!--<ul class="unstyled amounts">
                                    <li>Sub - Total amount : $3820</li>
                                    <li>Discount : 10% </li>
                                    <li>TAX (15%) ----- </li>
                                    <li class="grand-total text-center">Total : <?/*= $grand_total; */?></li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                    <div class="text-center invoice-btn">
                        <!--<a class="btn btn-success btn-lg"><i class="fa fa-check"></i> Submit Invoice </a>-->
                        <a href="javascript:void();" id="print_invoice"
                           class="btn btn-primary btn-lg"><i class="fa fa-print"></i> Print </a>
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
$css = file_get_contents(base_url('assets/css/style.css') );
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#print_invoice').click(function () {
            window.print();
            /*var prtContent = document.getElementById("print_page");
            var WinPrint = window.open("", '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            var head = WinPrint.document.head
                , link = WinPrint.document.createElement('link');

            link.type = 'text/css';
            link.rel = 'stylesheet';
            link.href = '';

            head.appendChild(link);

            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();*/
            /*var prtContent = document.getElementById("print_page");
            var mywindow = window.open('', 'Print BodyShape Invoice', 'width=950,height=700');

            $(mywindow.document.head).html( '<title>PressReleases</title><link rel="stylesheet" href="" type="text/css" /><link rel="stylesheet" href="" type="text/css" />');
            $(mywindow.document.body).html( '<body>' + prtContent.innerHTML + '</body>');

            mywindow.document.close();
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
            mywindow.close();

            return true;*/
        });
    });

</script>
