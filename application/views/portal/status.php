<div class="modal fade in" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none;">
    <form class="ajax_form form-horizontal" id="status-form" role="form" method="post" action="<?php echo site_url(ADMIN_DIR . $this->router->class .'/status')?>">
        <input type="hidden" name="status-id" value="" id="status-id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="status_close close">×</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">


                    Are you sure you want to change status?

                </div>
                <div class="modal-footer">
                    <button   class="btn btn-black styled-btn status_close" type="button"><i class="fa fa-times"></i>Close</button>
                    <button class="btn btn-green styled-btn" type="submit"><i class="fa fa-check-square-o"></i> Confirm</button>
                </div>
            </div>
        </div>
    </form>
</div>
