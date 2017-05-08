<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<section id="main-content" class="inner-main-pages">
        <section class="wrapper">
            <!--mini statistics start-->
            <div class="row">
                <div class="col-lg-12">
                    
                    <ul class="breadcrumb">
                        <li><a href="<?php echo site_url(ADMIN_DIR."modules");?>">Admin</a></li>
                        <li class="active">Modules</li>
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
				
                $grid 						= new grid();
                $grid->query 				= $query;
               // $grid->title 				= $this->module_title .' - List';
                $grid->limit 				= 25;
                $grid->search_box 			= TRUE;
				$grid->url 				 	='?'.$_SERVER['QUERY_STRING'];
                $grid->hide_fields 			= array('id','actions');
                $grid->center_fields 		= array('ordering');
                $grid->form_buttons 		= array('new', 'delete');
                $grid->grid_buttons 		= array('edit', 'delete');
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

?>