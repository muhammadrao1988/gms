<div class="modal fade in" id="payment_pop_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="false"
     style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal validate ajax_form" role="form" id="payment_form" method="post" action="<?=site_url(ADMIN_DIR.'invoices/payPayment/') ; ?>">
                <input type="hidden" name="acc_id" value="<?php echo $row->acc_id;?>">
                <input type="hidden" name="invoice_id" value="<?php echo $row->id;?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Receipt Form</h4>
                </div>
                <?php if($result==200){

                    $invoice_state_array = array(1=>'PAID',2=>'PARTIALLY PAID',3=>'CANCELLED',4=>'UNPAID')
                    ?>
                <div class="modal-body">
                    <div id="notification" class="notification"></div>
                    <?php echo show_validation_errors();?>
                    <div class="form-group margin-bottom-10">
                        <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Invoice Number:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                               <?php echo $row->id; ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group margin-bottom-10">
                        <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Invoice For:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                               <?php $explode_type = explode(",",$row->type);
                               foreach ($explode_type as $tp) {
                                   $type .=  invoice_for($tp).",";
                               }
                               echo trim($type,",");
                               ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group margin-bottom-10">
                        <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Invoice Status:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                                <?php echo $invoice_state_array[$row->state];?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group margin-bottom-10">
                        <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Member Name:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                                <?php echo $account_row->acc_name;?>
                            </label>
                        </div>
                    </div>
                    <?php if($row->state==4){?>
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Amount:</label>
                        <div class="col-lg-8">
                            <label for="" class="styled_select" style="padding-top: 7px;">
                                <?php echo $row->amount;?>
                            </label>
                        </div>
                    </div>
                    <?php }else{?>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-4 col-sm-4 control-label">Remaining Amount:</label>
                            <div class="col-lg-8">
                                <label for="" class="styled_select" style="padding-top: 7px;">
                                    <?php echo $row->remaining_amount;?>
                                </label>
                            </div>
                        </div>
                    <?php }?>
                    <div class="form-group" >
                        <label for="fees_month" class="col-lg-4 col-sm-4 control-label">Date of Receipt:</label>
                        <div class="col-lg-8">
                            <input type="text" id="receipt_date" name="receipt_date"
                                   value=""
                                   placeholder="DD-MM-YYYY"
                                   class="form-control validate[required] datepicker-default-ajax">
                        </div>
                    </div>


                    <!--<div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Fee Period</label>
                        <div class="col-lg-8" id="fees_checkboxes">
                       <?php
/*                       if($register_day <= date('d')) {

                           $datetime = new DateTime(date('Y-m-') . '01');
                           $datetime2 = new DateTime(date('Y-m-') . '01');
                       }else{

                           $currentMonth = date('F');
                            $previous_date = date('Y-m',strtotime($currentMonth." last month"))."-01";
                            $datetime = new DateTime($previous_date);
                           $datetime2 = new DateTime($previous_date);
                       }

                       for ($i=1 ; $i<=$month_due;$i++ ){


                           */?>

                           <?php
/*                           if($i==1) {
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
                        */?>
                        </div>
                    </div>-->


                    <div class="form-group partial_paid_div" >
                        <label for="fees_month" class="col-lg-4 col-sm-4 control-label">Received Amount:</label>
                        <div class="col-lg-8">
                            <input type="text" id="received_amount" placeholder=""
                                   style="padding: 0 10px;"
                                   class="form-control  validate[required,custom[integer]]"
                                   name="received_amount"
                                   value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fees" class="col-lg-4 col-sm-4 control-label">Discount:</label>
                        <div class="col-lg-8">

                            <input  class="form-control validate[custom[integer]]" id="discount" name="discount"  type="text" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fees" class="col-lg-4 col-sm-4 control-label">Description:</label>
                        <div class="col-lg-8">

                            <textarea class="form-control" name="description" id="description" cols="30"
                                      rows="5"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <input type="hidden" name="redirect_page" value="<?php echo current_url().( $_SERVER['QUERY_STRING']!="" ? '?msg=Invoice added successfully.' . $_SERVER['QUERY_STRING'] : ""); ?>">
                    <button data-dismiss="modal" class="btn btn-black" type="button">Close</button>
                    <button class="btn btn-green" type="submit">Pay Payment</button>
                </div>
                <?php }else{?>
                    <div class="modal-body">
                        <p>Invalid invoice. Please try again.</p>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-black" type="button">Close</button>

                    </div>
                <?php }?>
            </form>
        </div>
    </div>
</div>
<style>
    .margin-bottom-10{
        margin-bottom: -10px;
    }
</style>
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