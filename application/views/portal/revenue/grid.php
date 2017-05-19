<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css'); ?>">
<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "members"); ?>>">Members</a></li>
                    <li class="active"><?php echo $title ?></li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->

        <div class="filter-section" style="padding-top: 10px">
            <div class="row">
                <!--<div class="pull-left" style="margin-left: 15px;">
                    <label for="" style="padding: 5px 0;"> Month: </label>
                </div>-->
                <div class="col-md-9">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <label for="" class="pull-left  " style="padding: 5px 0;"> Date Range:&nbsp; </label>

                                <div class="input-group input-large" data-date="13-07-2013" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control datepicker-format" name="from">
                                    <span class="input-group-addon">To</span>
                                    <input type="text" class="form-control datepicker-format" name="to">
                                </div>

                        </div>
                        <button type="submit" class="btn btn-green">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row page-table">

            <div class="clearfix"></div>
            <?php

            $search_ar = getVar('search');

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = false;
            $grid->hide_fields = array('full_name', 'email', 'user_login_status');
            //$grid->search_fields_html = array('user_login_status' => '', 'company' => $s_company, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);

            $grid->form_buttons = array('new', 'delete');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array('view');
            echo $grid->showGrid();

            ?>
            <!--</div>-->
        </div>
        <!-- end data table -->


    </section>
</section>

<?php
include dirname(__FILE__) . "/../includes/footer.php";
include dirname(__FILE__) . "/../delete.php";
include dirname(__FILE__) . "/../status.php";

?>
<!-- Content -->
  