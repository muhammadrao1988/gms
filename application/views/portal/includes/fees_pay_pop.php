<div class="modal fade" id="payment_pop_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="true"
     style="display: none;">
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
                        <label for="fees" class="col-lg-3 col-sm-4 control-label">Amount</label>
                        <div class="col-lg-8">
                            <input class="form-control validate[required,custom[integer]]" id="fees" name="fees" placeholder="Amount" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
                        <div class="col-lg-8">
                            <textarea name="description" class="form-control" id="description" cols="30" rows="3"></textarea>
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
        $('.payment_pop').click( function () {
            var invoice_id = $(this).attr('data-invoice');
            $('#invoice_id').val(invoice_id);
            $('#payment_pop_modal').modal('show');
        });
    });
</script>