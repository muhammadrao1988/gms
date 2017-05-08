<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<!--main content start-->

<section id="main-content" class="inner-main-pages">
  <section class="wrapper"> 
    <!--mini statistics start-->
    <div class="row">
      <div class="col-lg-12">
        
        <!--breadcrumbs start -->
        <ul class="breadcrumb">
          <li><a href="<?php echo site_url(ADMIN_DIR."modules");?>">Modules</a></li>
          <li class="active">Edit Module</li>
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
        <button class="btn btn-white" onclick="window.history.back()" type="button"> <i class="fa fa-chevron-left"></i> Back</button>
          <span class="vert-sep"></span> 
          <button class="btn btn-white active"  type="button">Modules</button>
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
        <header class="panel-heading theme-panel-heading"> <strong>Modules - Form</strong> </header>
        <div class="panel-body">
        
         
       <form id="validate" class="form-horizontal theme-form-horizontal validate" action="<?php echo  site_url(ADMIN_DIR . $this->module_name . (!empty($row->id) ? '/update' : '/add')) ; ?>" method="post" enctype="multipart/form-data">
       <input type="hidden" name="id" id="id" value="<?php echo  $row->id; ?>"/>
          
            <div class="row">
              <div class="col-md-12">
                
                
                <div class="form-group">
                  <label class="control-label col-md-3">Parent Module:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    
                     <select name="parent_id" id="parent_id" class="select">
                                <option value="0" <?php echo ($row->parent_id == '') ? 'selected' : '';?>> / </option>
                                <?php
                                            $this->multilevels->type = 'select';
                                            $this->multilevels->id_Column = 'id';
                                            $this->multilevels->title_Column = 'module_title';
                                            $this->multilevels->link_Column = 'module';
                                            $this->multilevels->level_spacing = 5;
                                            $this->multilevels->selected = $row->parent_id;
                                            $this->multilevels->query = "SELECT * FROM `modules` WHERE `status`='active' ORDER BY ordering ASC";
                                            echo $multiLevelComponents = $this->multilevels->build();
                                            ?>
                              </select>
              
               
                    </div>
                  </div>
                </div>
                
                
                
                
                <div class="form-group">
                  <label class="control-label col-md-3">Module:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <input type="text" name="module" id="module" value="<?php echo  $row->module; ?>"  style="width:60%;" class="form-control validate[required]">
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label class="control-label col-md-3">Module Title:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <input type="text" name="module_title" id="module_title" value="<?php echo  $row->module_title; ?>"  style="width:60%;" class="form-control validate[required]">
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label class="control-label col-md-3">Ordering:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <input type="text" name="ordering" id="ordering" value="<?php echo  $row->ordering; ?>"  style="width:60%;" class="form-control validate[required]">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Show On Menu:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                  <input type="checkbox" class="styled" name="show_on_menu" id="show_on_menu" value="1" <?php echo ($row->show_on_menu == 1) ? 'checked' : '';?>/>
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label class="control-label col-md-3">Actions:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <input type="text" name="actions" id="actions" value="<?php echo  $row->actions; ?>"  style="width:60%;" class="form-control">
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                  <label class="control-label col-md-3">Status:</label>
                  <div class="col-md-4">
                    <div class="input-large">
                    <label class="styled_select">
                      <select name="status" id="status">
                                            <?php
                                            $status = array(
                                                'active' => 'Active',
                                                'inactive' => 'Inactive'
                                            );
                                            echo selectBox($status, $row->status);
                                            ?>
                                        </select>
                       </label>                 
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-3">&nbsp;</label>
                  <div class="col-md-8">
                       <button type="reset" class="btn btn-black " onclick="window.history.back()"> Cancel</button>
                    <button type="submit" class="btn btn-green "> Submit </button>
               
                  </div>
                </div>
                
                
                
              </div>
              
            </div>
          </form>
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