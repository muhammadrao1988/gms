<div class="modal fade in" id="payment_pop_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="false"
     style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal validate ajax_form" role="form" id="payment_form" method="post" action="<?=site_url(ADMIN_DIR.'invoices/payPayment/') ; ?>">
                <input type="hidden" name="acc_id" value="<?php echo $acc_id;?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Payment Form</h4>
                </div>
                <div class="modal-body">
                    <div id="notification" class="notification"></div>
                    <?php echo show_validation_errors();?>
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-3 col-sm-4 control-label">Payment Type:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                                Monthly Fees
                            </label>
                            <!--<p class="help-block">Example block-level help text here.</p>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Per Month Fee:</label>
                        <div class="col-lg-8" >
                            <label for="" class="styled_select" style="padding-top: 7px;">
                            <?php echo $one_month_fee;?>
                            </label>
                            <input class="form-control validate[required,custom[integer]]" id="per_month_fee" name="per_month_fee"  type="hidden" value="<?php echo $one_month_fee;?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Total Amount:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select total_amount_text" style="padding-top: 7px;">0</label>
                            <input class="form-control validate[required,custom[integer]]" id="total_amount" name="total_amount"  type="hidden" value="<?php echo $one_month_fee*$month_due;?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Fee Period</label>
                        <div class="col-lg-8" id="fees_checkboxes">
                       <?php
                       if($register_day <= date('d')) {

                           $datetime = new DateTime(date('Y-m-') . '01');
                           $datetime2 = new DateTime(date('Y-m-') . '01');
                       }else{

                           $currentMonth = date('F');
                            $previous_date = date('Y-m',strtotime($currentMonth." last month"))."-01";
                            $datetime = new DateTime($previous_date);
                           $datetime2 = new DateTime($previous_date);
                       }

                       for ($i=1 ; $i<=$month_due;$i++ ){


                           ?>

                           <?php
                           if($i==1) {
                               $from_month = $datetime;
                           }else{
                               $from_month = $datetime->modify("-1 month");
                           }
                           $from_month = $datetime->format("MY");


                           if($i==1){
                               $to_month = $datetime2->modify("+1 month");
                               //$to_month = date( "MY");
                           }else{
                               $to_month =$datetime2->modify("-1 month");

                           }
                           $to_month =$datetime2->format("MY");

                           if( strpos($from_month, 'Feb') !== false && $register_day > 28 ){
                               $register_day_from = 28;
                           }else if($register_day > 30 && (strpos($from_month, 'Apr') !== false OR
                                   strpos($from_month, 'Jun') !== false OR
                                   strpos($from_month, 'Sep') !== false OR
                                   strpos($from_month, 'Nov') !== false
                               )){
                               $register_day_from = 30;
                           }else{
                               $register_day_from = $register_day;
                           }


                           if(strpos($to_month, 'Feb') !== false  && $register_day > 28){
                               $register_day_to = 28;
                           }else if($register_day > 30 && (strpos($to_month, 'Apr') !== false OR
                                   strpos($to_month, 'Jun') !== false OR
                                   strpos($to_month, 'Sep') !== false OR
                                   strpos($to_month, 'Nov') !== false
                               )){
                               $register_day_to = 30;
                           }else{
                               $register_day_to = $register_day;
                           }

                           echo "<input style='float:left;margin-top:3px;margin-right:5px' type='checkbox' name='fees_month[]' class='due_fees' data-count='".$i."' id='fees_".$i."' value='".date('Y-m',strtotime($from_month))."-".$register_day_from."|".date('Y-m',strtotime($to_month))."-".$register_day_to."'>";
                           echo "<input  type='hidden' name='fees_month_hidden[]'  value='".date('Y-m',strtotime($from_month))."-".$register_day_from."|".date('Y-m',strtotime($to_month))."-".$register_day_to."'>";
                           echo " From ".$register_day_from." ". $from_month;
                           echo " To ".$register_day_to." ".$to_month;
                           echo "<br>";
                           //$datetime = new DateTime(date('Y-m-d'));

                       }
                        ?>
                        </div>
                    </div>
                   <div class="form-group" >
                        <label for="Name" class="col-lg-3 col-sm-3 control-label">Choose Option</label>
                        <div class="col-lg-6" >
                            <label>
                                <input name="invoice_option" class="invoice_option validate[required]" value="1" type="radio" checked  > Continue with above invoices
                            </label>
                            <label>
                                <input name="invoice_option" value="2" class="invoice_option validate[required]" type="radio"> Cancel all invoices and create new invoice from today date
                            </label>

                            </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-3 control-label">Invoice Status</label>
                        <div class="col-lg-6">
                            <select name="state" id="state" class="select validate[required]"
                                    tabindex="-1"
                                    data-placeholder="Select invoice types">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo $row-state==1 ? "selected" : "";?>>Paid</option>
                                <option value="2" <?php echo $row-state==2 ? "selected" : "";?>>Partial Paid</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group partial_paid_div" style="display: none;">
                        <label for="fees_month" class="col-lg-3 col-sm-3 control-label">Received Amount</label>
                        <div class="col-lg-6">
                            <input type="text" id="received_amount" placeholder=""
                                   style="padding: 0 10px;"
                                   class="form-control  validate[required,custom[integer]]"
                                   name="received_amount"
                                   value="<?php echo $row->received_amount;?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Discount:</label>
                        <div class="col-lg-8">

                            <input  class="form-control validate[custom[integer]]" id="discount" name="discount"  type="text" value="0">
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
        $('#state').change(function () {
            if($(this).val()==2){
                $('.partial_paid_div').show();
            }else{
                $('.partial_paid_div').hide();
            }
        })
        $('.due_fees').click(function (e) {
            var check_count  = $('.due_fees').length;
            var current_box = $(this).attr('data-count');
            var per_month_fee = '<?php echo $one_month_fee ?>';
            var i = 1;
            var j = 0;
           if($(this).prop('checked')==true){
               for(i = current_box;i<=check_count;i++){
                   $('#fees_'+i).prop('checked',true);
                   j++;
               }
           }else{

               $('.due_fees').prop('checked',false);
               for(i = parseInt(current_box)+1;i<=check_count;i++){
                   $('#fees_'+i).prop('checked',true);
                   j++;
               }
           }
           var total_amount = parseInt(per_month_fee)*parseInt(j);
           var discount = $("#discount").val();
           total_amount = total_amount - parseInt(discount);
           $('.total_amount_text').text(total_amount);
        });
    });
</script>