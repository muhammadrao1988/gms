<div class="modal fade in" id="cancel_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="payment_pop" aria-hidden="false"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal validate ajax_form" role="form" id="payment_form" method="post" action="<?=site_url(ADMIN_DIR.'invoices/cancelPayment/') ; ?>">
                <input type="hidden" id="acc_id" name="acc_id" value="<?php echo $row->acc_id;?>">
                <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo $row->id;?>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cancel Invoice</h4>
                </div>

                    <div class="modal-body">
                        <p>Do you really want to cancel this invoice?</p>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-warning" id="submit_button_delete" type="submit"> Confirm</button>

                    </div>

            </form>
        </div>
    </div>
</div>
<style>
    .margin-bottom-10{
        margin-bottom: -10px;
    }
</style>
