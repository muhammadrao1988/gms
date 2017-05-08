<?php
/**
 * Adnan Bashir
 * Email: adnan.bashir@topgearmedia.co.uk
 */
include  dirname(__FILE__) . "/../includes/head.php";
include  dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";

?>
    <!-- Content -->
    <div id="content">
        <div class="wrapper">

            <div class="page-header">
                <h5 class="widget-name"><i class="icon-user"></i><?=$this->module_title;?></h5>
            </div>
            <div class="row-fluid">
                <!-- START -->

                <?php

                echo get_form_actions($buttons);
                $view = new record_view();
                $view->query = $query;
                //$view->grid_buttons = array('edit' => array('module_id'), 'delete');
                echo $view->showView();
                ?>

                <!-- END -->
            </div>
        </div>
    </div>
    <!-- /content -->
<?php
include  dirname(__FILE__) . "/../includes/footer.php";
?>