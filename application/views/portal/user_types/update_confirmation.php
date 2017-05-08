<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<style>
    .perm-table{
        background:#616365;
        color:#fff;
    }
    .perm-table-row{
        background:#f2f2f2 !important;
    }
</style>
<!--main content start-->

<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <!--breadcrumbs start -->
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR."user_types");?>">Admin</a></li>
                    <li class="active">User Job Roles Form</li>
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

                    <button class="btn btn-white active"  type="button">Master Permissions</button>
                    <span class="vert-sep"></span>

                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row page-middle-btn">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body panel-breadcrumb-action">


                        <?php echo show_validation_errors();?>
                        <div class="period-text"></div>

                    </div>
                </section>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <section class="panel">
                <header class="panel-heading theme-panel-heading"> <strong>Updated Account</strong> </header>
                <div class="panel-body">
                    <div class="alert alert-success alert-block fade in controls" id="text-to-copy-text" style="display:none;">
                        <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="fa fa-times"></i>
                        </button>
                        <p>Text Copied..</p>
                    </div>
                <?php
                if(count($update_account_ids) > 0){?>
                    <div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>The following accounts have been updated</div>
                    <br>
                    <table class="table table-bordered table-checks table-striped" style="width: 50%">
                        <thead>
                        <tr>
                            <th class="perm-table">Acc ID</th>
                            <th class="perm-table">Account Name</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $copy_clipboard_value="";
                        foreach($update_account_ids as $account){
                         $acc_name=   getVal("accounts","acc_name"," WHERE acc_id='".$account."'")
                            ?>
                        <tr>
                            <td><?php echo $account;?></td>
                            <td><?php echo $acc_name  ?>
                            </td>
                        </tr>
                        <?php
                            $copy_clipboard_value .= $account." | ".$acc_name."||";
                        } ?>


                        </tbody>
                    </table>
                    <button id="text-to-copy" type="button" data-clipboard-text="<?php echo trim($copy_clipboard_value,"||");?>" class="btn btn-green clipboard-number">Copy Records to Clipboard</button>
                    &nbsp;
                    <a href="<?php echo site_url(ADMIN_DIR."user_types");?>" class="btn btn-black">Finish</a>
                <?php }else{
                    echo '<div class="alert alert-info "><button type="button" class="close" data-dismiss="alert">×</button>All accounts are already updated..</div>';
                }
                ?>


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
<script src="<?=base_url('assets/js/ZeroClipboard.js');?>"></script>
<script src="<?=base_url('assets/js/main_clipboard.js');?>"></script>

