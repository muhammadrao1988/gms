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
          <li class="active">Master Permissions</li>
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
        <header class="panel-heading theme-panel-heading"> <strong>User Job Roles</strong> </header>
        <div class="panel-body">
        
         
       <form id="validate" class="form-horizontal theme-form-horizontal validate" action="<?= site_url(ADMIN_DIR . $this->module_name . (!empty($row->id) ? '/update' : '/add')); ?>"   method="post">
         <input type="hidden" name="id" id="id" value="<?= $row->id; ?>"/>
          <input type="hidden" name="edited_by" value="<?php echo  $this->session->userdata['user_id'];  ?>" />
           <input type="hidden" name="last_edited" value="<?php echo date('Y-m-d h:i:s');  ?>" />
            <div class="row">
              <div class="col-md-12">
                
      			
                
                <div class="form-group">
                  <label class="control-label col-md-3">Template Name:</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <input type="text" name="user_type" id="user_type" value="<?= $row->user_type; ?>"  style="width:60%;" class="form-control validate[required]">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Status:</label>
                  <div class="col-md-8">
                    <div class="input-large">

                        <select class="select" name="user_status" id="user_status" style="width:35%;">
                         
                          <option value="1" <?php echo ($row->user_status==1 ? 'selected' : '');?>>Active</option>
                          <option value="0" <?php echo ($row->user_status==0 ? 'selected' : '');?>>Inactive</option>
                                            </select>

                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Modules::</label>
                  <div class="col-md-8">
                    <div class="input-large">
                    <div class="ul">
                                            <?php
                                            $CI =& get_instance();
                                            $CI->load->database();
                                            $check_sql = "SELECT id, parent_id, module, module_title, `actions` FROM modules where `status`='active' order by ordering";
                                            $result = $CI->db->query($check_sql);

                                            $menu = array(
                                                'items' => array(),
                                                'parents' => array()
                                            );
                                            foreach ($result->result_array() as $items) {
                                                $menu['items'][$items['id']] = $items;
                                                $menu['parents'][$items['parent_id']][] = $items['id'];
												$menu['ff'] = $items['parent_id'];

                                            }
											echo "<table class='table table-bordered table-checks table-striped'><thead><tr>
											<th class='perm-table'>Area</th>
											<th class='perm-table'>Sub Area</th>
											<th class='perm-table'>Default Permission</th>
											<th class='perm-table'>Actions</th>
											</thead>
											</tr>";
                                            echo buildModuleCheckBox(0, $menu, $modules, $selected_action);
											echo "</table>";
                                            function buildModuleCheckBox($parent, $menu, $modules, $selected_action)
                                            {

                                                $html = "";
                                                if (isset($menu['parents'][$parent])) {
                                                    $html .= "<tr>";

                                                    foreach ($menu['parents'][$parent] as $itemId) {
                                                        if (!isset($menu['parents'][$itemId])) {
                                                            $actions = '';
                                                            $actions_ar = explode('|', str_replace(',', '|', ($menu['items'][$itemId]['actions'])));

                                                            if (count($actions_ar) > 0) {
                                                                foreach ($actions_ar as $act) {
                                                                    if ($act != '') {
                                                                        $actions .= "<input class='' type='checkbox'
                                                                                                " . (in_array($act, $selected_action[$menu['items'][$itemId]['id']]) ? ' checked ' : '') . "
                                                                                                name='actions[" . $menu['items'][$itemId]['id'] . "][]' id='a' value='" . $act . "' title='" . ucwords(str_replace('_', ' ', $act)) . "'> " . ucwords(str_replace('_', ' ', $act)) . " ";
                                                                    }
                                                                }
                                                            }
															if($menu['items'][$itemId]['parent_id']==0){ #for those which has no sub menu
																
																$html .= "<tr><td class='perm-table-row'>
                                                                                            <strong><input
                                                                                            " . ((in_array($menu['items'][$itemId]['id'], $modules)) ? 'checked' : '') . "
                                                                                            type='checkbox' name='modules[]' value='" . $menu['items'][$itemId]['id'] . "' class='multi_checkbox '>
                                                                                          " . $menu['items'][$itemId]['module_title']."</strong>
                                                                                           </td>
																						   <td class='perm-table-row'>&nbsp;</td>
																						   <td class='perm-table-row'>&nbsp;</td>
																						   <td class='perm-table-row'>".$actions."</td>
																						   
                                                                                            </tr>
																							
																							";
															}else{	#sub menu
                                                            $html .= "<tr><td>&nbsp;</td><td>
                                                                                          " . $menu['items'][$itemId]['module_title'] . "
                                                                                           </td>
                                                                                               
																								<td> <input type='checkbox'
                                                                                            " . ((in_array($menu['items'][$itemId]['id'], $modules)) ? 'checked' : '') . "
                                                                                            name='modules[]' value='" . $menu['items'][$itemId]['id'] . "' class='multi_checkbox '>
                                                                                           </td>
																						    <td>
                                                                                                " . $actions . "
                                                                                                </td>
                                                                                            </tr>
																							";
															}
                                                        }
                                                        if (isset($menu['parents'][$itemId])) { #for those which has sub menu

                                                            $html .= "<tr>
																		<td class='perm-table-row'>
                                                                                           <strong> <input
                                                                                            " . ((in_array($menu['items'][$itemId]['id'], $modules)) ? 'checked' : '') . "
                                                                                            type='checkbox' name='modules[]' value='" . $menu['items'][$itemId]['id'] . "' class='multi_checkbox '>
                                                                                          " . $menu['items'][$itemId]['module_title']."</strong>
																						  </td>
																		<td class='perm-table-row'>&nbsp;</td class='perm-table-row'><td class='perm-table-row'>&nbsp;</td><td class='perm-table-row'>&nbsp;</td>
																	</tr>	
																						  
																						  ";
                                                            $html .= buildModuleCheckBox($itemId, $menu, $modules, $selected_action);
                                                            $actions = '';
                                                            $actions_ar = explode('|', str_replace(',', '|', ($menu['items'][$itemId]['actions'])));
                                                            if (count($actions_ar) > 0) {
                                                                foreach ($actions_ar as $act) {
                                                                    if ($act != '') {
                                                                        $actions .= "<input class='' type='checkbox'
                                                                                                " . (in_array($act, $selected_action[$menu['items'][$itemId]['id']]) ? ' checked ' : '') . "
                                                                                                name='actions[" . $menu['items'][$itemId]['id'] . "][]' id='a' value='" . $act . "' title='" . ucwords(str_replace('_', ' ', $act)) . "'>fr " . ucwords(str_replace('_', ' ', $act)) . " ";
                                                                    }
                                                                }
                                                            }
                                                           /* $html .= "	
															
                                                                                    
																					<tr class='module_actions'>
                                                                                        <td colspan='4' class='perm-table-row'>
                                                                                        " . $actions . "
                                                                                        </td>
                                                                                    </tr>
																					
                                                                                    ";*/
                                                        }
                                                    }
                                                    $html .= "";
                                                }
                                                return $html;
                                            }
											 function buildModuleCheckBox2($parent, $menu, $modules, $selected_action)
                                            {

                                                $html = "";
                                                if (isset($menu['parents'][$parent])) {
                                                    $html .= "<ul>\n";

                                                    foreach ($menu['parents'][$parent] as $itemId) {
                                                        if (!isset($menu['parents'][$itemId])) {
                                                            $actions = '';
                                                            $actions_ar = explode('|', str_replace(',', '|', ($menu['items'][$itemId]['actions'])));

                                                            if (count($actions_ar) > 0) {
                                                                foreach ($actions_ar as $act) {
                                                                    if ($act != '') {
                                                                        $actions .= "<input class='' type='checkbox'
                                                                                                " . (in_array($act, $selected_action[$menu['items'][$itemId]['id']]) ? ' checked ' : '') . "
                                                                                                name='actions[" . $menu['items'][$itemId]['id'] . "][]' id='a' value='" . $act . "' title='" . ucwords(str_replace('_', ' ', $act)) . "'> " . ucwords(str_replace('_', ' ', $act)) . " ";
                                                                    }
                                                                }
                                                            }
                                                            $html .= "\n<li>
                                                                                            <input type='checkbox'
                                                                                            " . ((in_array($menu['items'][$itemId]['id'], $modules)) ? 'checked' : '') . "
                                                                                            name='modules[]' value='" . $menu['items'][$itemId]['id'] . "' class='multi_checkbox '>
                                                                                            " . $menu['items'][$itemId]['module_title'] . "
                                                                                            <ul class='module_actions '>
                                                                                                <li>
                                                                                                " . $actions . "
                                                                                                </li>
                                                                                            </ul>
                                                                                            </li>";
                                                        }
                                                        if (isset($menu['parents'][$itemId])) {

                                                            $html .= "\n<li>
                                                                                            <input
                                                                                            " . ((in_array($menu['items'][$itemId]['id'], $modules)) ? 'checked' : '') . "
                                                                                            type='checkbox' name='modules[]' value='" . $menu['items'][$itemId]['id'] . "' class='multi_checkbox '>
                                                                                            " . $menu['items'][$itemId]['module_title'];
                                                            $html .= buildModuleCheckBox($itemId, $menu, $modules, $selected_action);
                                                            $actions = '';
                                                            $actions_ar = explode('|', str_replace(',', '|', ($menu['items'][$itemId]['actions'])));
                                                            if (count($actions_ar) > 0) {
                                                                foreach ($actions_ar as $act) {
                                                                    if ($act != '') {
                                                                        $actions .= "<input class='' type='checkbox'
                                                                                                " . (in_array($act, $selected_action[$menu['items'][$itemId]['id']]) ? ' checked ' : '') . "
                                                                                                name='actions[" . $menu['items'][$itemId]['id'] . "][]' id='a' value='" . $act . "' title='" . ucwords(str_replace('_', ' ', $act)) . "'> " . ucwords(str_replace('_', ' ', $act)) . " ";
                                                                    }
                                                                }
                                                            }
                                                            $html .= "
                                                                                    <ul class='module_actions'>
                                                                                        <li>
                                                                                        " . $actions . "
                                                                                        </li>
                                                                                    </ul>
                                                                                    \n</li>";
                                                        }
                                                    }
                                                    $html .= "\n</ul>";
                                                }
                                                return $html;
                                            }
                                            ?>
                                        </div>
                    </div>
                  </div>
                </div>
                 
                
                <div class="form-group">
                  <label class="control-label col-md-3">&nbsp;</label>
                  <div class="col-md-8">
                       <button type="reset" class="btn btn-black " onclick="window.history.back()"> Cancel</button>
                    <button type="submit" class="btn btn-green "> Save </button>

               
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

<script type="text/javascript">
    (function ($) {

        /*$('.ul ul li:not(li ul li)').css({
            float:'left',
            'margin':'0 0 0 30px'
        })*/
       // var chk;
        /*$('.multi_checkbox').click(function(e) {
			var status = this.checked;
			//alert(this.checked);
			
            $('.multi_checkbox', $(this).parent()).attr('checked', status);
			
            if (status) {
                $(this).next('.module_actions').fadeIn();
				
                $(this).next('.module_actions').find('input').attr('checked',true);
            } else {
                $(this).next('.module_actions').fadeOut();
                $(this).next('.module_actions').find('input').attr('checked',false);
            }

        })
        $('.multi_checkbox').each(function () {
            var status = (($(this).attr('checked')) ? true : false);
            //console.log($(this).next('.module_actions'));
            if (status) {
                $(this).next('.module_actions').fadeIn();
            } else {
                $(this).next('.module_actions').fadeOut();
            }
        })*/
    })(jQuery)
</script>