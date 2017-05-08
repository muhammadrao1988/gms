<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none;">
 <form class="ajax_form form-horizontal" id="del-form" role="form" method="post" action="<?php echo site_url(ADMIN_DIR . $this->router->class .'/delete')?>">
 <input type="hidden" name="del-id" value="" id="del-id">
 <input type="hidden" name="del-all" value="" id="del-all">
 <input type="hidden" name="action"  value="" id="action">
 
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Delete</h4>
                                        </div>
                                        <div class="modal-body">
                                       

                                           Are you sure you want to delete?

                                        </div>
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                            <button class="btn btn-warning" id="submit_button_delete" type="submit"> Confirm</button>
                                        </div>
                                    </div>
                                </div>
</form>                                
                            </div>
                            