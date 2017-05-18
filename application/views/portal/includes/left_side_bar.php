<!--sidebar start-->
<?php
$this->load->database('default', TRUE);

$user_info_left = $this->db->query("SELECT t1.first_name, t1.surname, t1.acc_name FROM accounts t1 WHERE t1.acc_id = '".$this->session->userdata['user_id']."'")->result();

?>
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="row user-left-details">
            <div class="col-md-12">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon"><img src="<?=base_url('assets/images/user-logo.png') ; ?>" alt="user icon"/></span>
                    <div class="mini-stat-info">
                        <span><?= $user_info_left[0]->first_name ; ?> <?=$user_info_left[0]->surname ; ?></span>
                        <?=$user_info_left[0]->acc_name ; ?>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu" id="nav-accordion">
           <!-- <li>
            <a class="<?/*=(getUri('2') == 'dashboard')?'active':'' ; */?>" href="<?/*= site_url(ADMIN_DIR . 'dashboard'); */?>">
                    <i class="fa tb-icon tb-dashboard"></i>
                    Dashboard 
                </a>
            </li>-->
            <?php

			/*if($this->session->userdata['u_type']==3){
				 $user_template_table = $this->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['parent_id']."'")->num_rows();	
				 if($user_template_table==0){
				   $table	= 'user_type_module_rel';
				   $user_template_id	= intval(sessionVar('user_template_id'));	
				   
				 }else{
				   $table	= 'user_template_methods';	 
				   $and_condition = ' AND acc_id = "'.$this->session->userdata['parent_id'].'"'; 
				   $user_template_id	= $this->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['parent_id']."'")->row();	
				   $user_template_id	= $user_template_id->user_type_id;
				 }
			}else */
			//if($this->session->userdata['u_type']==4 || $this->session->userdata['u_type']==3){
				 $user_template_table = $this->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['user_info']->acc_id."'")->num_rows();
				 if($user_template_table==0){
				   $table			= 'user_type_module_rel';	 
				   //$user_template_id	= intval(sessionVar('user_template_id'));
                   $user_template_id = $this->db->query("SELECT user_template_id FROM accounts WHERE acc_id = '".$this->session->userdata['user_info']->acc_id."'")->row();
				   $user_template_id = $user_template_id->user_template_id;
				 }else{
				   $table	= 'user_template_methods';

				   $and_condition = ' AND user_template_methods.acc_id = "'.$this->session->userdata['user_info']->acc_id.'" ';
				   $user_template_id	= $this->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['user_info']->acc_id."'")->row();
				   $user_template_id	= $user_template_id->user_type_id;
				 }

			//}else{
				 	//echo $table			= 'user_type_module_rel';
					//$user_template_id	= intval(sessionVar('user_template_id'));
						
			//}
			
		//echo "SELECT * FROM `modules` WHERE `status`='active' AND show_on_menu = 1 AND id IN (SELECT `module_id` FROM `".$table."` WHERE user_type_id='" . intval($user_template_id) . "' ".$and_condition.") ORDER BY ordering ASC";
            $this->multilevels->id_Column = 'id';
            $this->multilevels->title_Column = 'module_title';
            $this->multilevels->link_Column = 'module';
            $this->multilevels->level_spacing = 5;
            $this->multilevels->active_class = 'active';
            $this->multilevels->active_link = getUri(2);
            $this->multilevels->search = array('{icon}');
            $this->multilevels->replace = array('icon');

            $this->multilevels->url = site_url(ADMIN_DIR) ;
            $this->multilevels->query = "SELECT * FROM `modules` WHERE `status`='active' AND show_on_menu = 1 AND id IN (SELECT `module_id` FROM `".$table."` WHERE user_type_id='" . intval($user_template_id) . "' ".$and_condition.") ORDER BY ordering ASC";
            echo $this->multilevels->build();
            ?>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
