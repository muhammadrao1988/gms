<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css') ; ?>">
<?php

?>

<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "expenses"); ?>>">Expenses</a></li>
                    <li class="active">Expenses</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->
        <div class="row page-table">
            <div class="clearfix"></div>
                <?php
                $grid = new grid();
                $grid->query = $query;
                //$grid->title = $this->module_title .' - List';
                $grid->limit = 25;
                $grid->search_box = true;
                $grid->selectAllCheckbox = false;
                $grid->order_column = 'id';
                $grid->hide_fields = array('id');
                $grid->custom_col_name_fields = array();
                $grid->search_fields_html = array('status' => $status, );
                $grid->form_buttons = array('new');
                $grid->url = '?' . $_SERVER['QUERY_STRING'];
                //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
                $grid->grid_buttons = array('edit', 'delete', 'view');
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
  