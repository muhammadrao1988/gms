<!--sidebar start-->
<?php
$this->load->database('default', TRUE);

$user_info_left = $this->db->query("SELECT t1.first_name, t1.surname FROM users t1 WHERE t1.user_id = '".$this->session->userdata['user_info']->user_id."'")->result();

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

            <?php

            $user_template_id = $this->db->query("SELECT user_template_id FROM users WHERE user_id = '".$this->session->userdata['user_info']->user_id."'")->row();
            $user_template_id = $user_template_id->user_template_id;
            $table			= 'user_type_module_rel';

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
