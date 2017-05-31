<div class="modal fade in" id="payment_pop_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="false"
     style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal validate" role="form" id="payment_form" method="post" action="<?=site_url(ADMIN_DIR.'invoices/payPayment/') ; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Payment Form</h4>
                </div>
                <div class="modal-body">
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
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Per Month Fee:</label>
                        <div class="col-lg-8">
                            <?php echo $one_month_fee;?>
                            <input class="form-control validate[required,custom[integer]]" id="per_month_fee" name="per_month_fee"  type="hidden" value="<?php echo $one_month_fee;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Total Amount:</label>
                        <div class="col-lg-8">
                            <?php echo $one_month_fee*$month_due;?>
                            <input class="form-control validate[required,custom[integer]]" id="total_amount" name="total_amount"  type="hidden" value="<?php echo $one_month_fee*$month_due;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Discount:</label>
                        <div class="col-lg-8">

                            <input class="form-control validate[custom[integer]]" id="discount" name="discount"  type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
                        <div class="col-lg-8">
                            <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
                        <div class="col-lg-8">
                       <?php
                       $datetime = new DateTime(date('Y-m-').'01');
                       $datetime2 = new DateTime(date('Y-m-').'01');

                       for ($i=1 ; $i<=$month_due;$i++ ){


                           ?>

                           <?php

                           $from_month = $datetime->modify("-1 month");
                           $from_month = $datetime->format("FY");


                           if($i==1){
                               $to_month = date( "FY");
                           }else{
                               $to_month =$datetime2->modify("-1 month");
                               $to_month =$datetime2->format("FY");
                           }

                           if( strpos($from_month, 'February') !== false && $register_day > 28 ){
                               $register_day_from = 28;
                           }else if($register_day > 30 && (strpos($from_month, 'April') !== false OR
                               strpos($from_month, 'June') !== false OR
                               strpos($from_month, 'September') !== false OR
                               strpos($from_month, 'November') !== false
                                    )){
                               $register_day_from = 30;
                           }else{
                               $register_day_from = $register_day;
                           }


                            if(strpos($to_month, 'February') !== false  && $register_day > 28){
                                $register_day_to = 28;
                            }else if($register_day > 30 && (strpos($to_month, 'April') !== false OR
                                    strpos($to_month, 'June') !== false OR
                                    strpos($to_month, 'September') !== false OR
                                    strpos($to_month, 'November') !== false
                                )){
                                $register_day_to = 30;
                            }else{
                                $register_day_to = $register_day;
                            }


                           echo "From ".$register_day_from." ". $from_month;
                           echo " To ".$register_day_to." ".$to_month;
                           echo "<br>";
                           //$datetime = new DateTime(date('Y-m-d'));

                       }
                        ?>
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
                <div class="modal-footer">
                    <input type="hidden" name="invoice_id" id="invoice_id" value=""/>
                    <input type="hidden" name="redirect_page" value="<?php echo current_url().( $_SERVER['QUERY_STRING']!="" ? '?msg=Invoice added successfully.' . $_SERVER['QUERY_STRING'] : ""); ?>">
                    <button data-dismiss="modal" class="btn btn-black" type="button">Close</button>
                    <button class="btn btn-green" type="submit">Pay Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

    });
</script>