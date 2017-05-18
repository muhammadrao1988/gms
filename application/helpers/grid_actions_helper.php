<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_grid_actions')) {
    /**
     * @param $rows
     * @param $id_field
     * @param array $buttons = array('view' => array('id'), 'edit' => array('id'), 'status' => array('id'), 'delete' => array('id'))
     * @param string $form_name
     * @return string
     */
    function get_grid_actions($rows, $id_field, $buttons, $module_uri = "", $file_path = array())
    {

        $CI = &get_instance();
        //$user_actions = $CI->session->userdata('actions');
        $controller = $CI->router->fetch_class();
        $controller_method = $CI->router->fetch_method();
        $module  = $controller;

		/*if($CI->session->userdata['u_type']==3){
				 $user_template_table = $CI->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['parent_id']."'")->num_rows();
				 if($user_template_table==0){
				   $table_temp	= 'user_type_module_rel';
				   $user_template_id	= $CI->session->userdata('user_template_id');
				 }else{
				   $table_temp	= 'user_template_methods';
				   $and_condition = ' AND um.acc_id = "'.$CI->session->userdata['parent_id'].'"';
				   $user_template_id	= $CI->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['parent_id']."'")->row();
				   $user_template_id	= $user_template_id->user_type_id;
				 }
			}else */
           // if($CI->session->userdata['u_type']==4 || $CI->session->userdata['u_type']==3){
				 $user_template_table = $CI->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['user_info']->user_id."'")->num_rows();
				 if($user_template_table==0){
				   $table_temp	= 'user_type_module_rel';


                   //$user_template_id	= intval(sessionVar('user_template_id'));
                   $user_template_id = $CI->db->query("SELECT user_template_id FROM users WHERE user_id = '" . $CI->session->userdata['user_info']->user_id . "'")->row();
                   $user_template_id = $user_template_id->user_template_id;
                 }else{
				   $table_temp	= 'user_template_methods';
				   $and_condition = ' AND um.acc_id = "'.$CI->session->userdata['user_info']->user_id.'"';
				   $user_template_id	= $CI->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['user_info']->user_id."'")->row();
				   $user_template_id	= $user_template_id->user_type_id;
				 }
			/*}else{
				 	$table_temp	= 'user_type_module_rel';
					$user_template_id	= intval($CI->session->userdata('user_template_id'));
			}*/

        //todo:: _users
         // $user_actions = $CI->db->query("SELECT um.actions FROM users AS u INNER JOIN user_type_module_rel AS um ON (u.user_type = um.user_type_id) INNER JOIN modules AS m ON (m.id = um.module_id) WHERE um.user_type_id = '" . intval($CI->session->userdata('user_type')) . "' AND m.`module`='" . addslashes($module) . "'")->row()->actions;
		$user_actions = $CI->db->query("SELECT um.actions FROM users AS u INNER JOIN ".$table_temp." AS um ON (u.user_template_id = um.user_type_id) INNER JOIN modules AS m ON (m.id = um.module_id) WHERE um.user_type_id = '" . intval($user_template_id) . "' ".$and_condition." AND m.`module`='" . addslashes($module) . "'")->row()->actions;
       //todo::remove
        $user_actions .='|view_number_manager|edit_numbers|view_attendance';
        $user_actions = array_unique(explode('|', str_replace(array('update'), array('edit'), $user_actions))); //$user_actions[$module]

        $actions = array();
        $qstring = array();
        foreach ($buttons as $key => $button) {

            if (is_array($button)) {
                array_push($actions, $key);
                $i = -1;
                foreach ($button as $field => $fields) {
                    if (is_int($field)) {
                        $qsval = $fields;
                        $fields = $field;
                    } else {
                        $qsval = strtolower($rows[$fields]);
                    }
                    $i++;

                    $qstring[$key] .= (($i == 0) ? "?" : "&") . $fields . "=" . $qsval;
                }

            } else {
                array_push($actions, $button);
            }

        }

        $CI =& get_instance();


        $site_url = $CI->router->class;
        if (getUri(1) == str_replace('/', '', ADMIN_DIR)) {
            $site_url = ADMIN_DIR . $CI->router->class;
        }

        if (in_array('edit', $actions)) {
            $icon = '<i class="fa fa-edit th-edit"></i>';
            $edit = '<a
                        action="edit"
                        href="' . site_url($site_url . '/form/' . $rows[$id_field] . '/') . '"
                        data-toggle="tooltip" class="tooltips" data-original-title="Edit">' . $icon . '
                     </a>';


        }

        if (in_array('delete', $actions)) {
            $icon = '<i class="fa fa-trash-o th-delete"></i>';
            $delete = '<a href="#myModal2" data-toggle="modal" class="tooltips del-data" id="' . $rows[$id_field] . '"  data-original-title="Delete">
						' . $icon . '
                     </a>';

        }

        if (in_array('banned_user', $actions)) {
            $banned_user = '<li><a
                        action="banned_user"
                        href="' . site_url($site_url . '/banned_user/' . $rows[$id_field] . '/' . $qstring['banned_user']) . '"
                        class="tip " title="" data-original-title="Ban user"><i class="icon-remove-sign"></i>
                     </a></li>';
        }
        if (in_array('status', $actions)) {


            $status = '<a href="#myModal3" data-toggle="modal" class="tooltips status-data" id="' . $rows[$id_field] . '"  data-original-title="Inactive">
						<i class="fa fa-dot-circle-o th-dot-circle-o" style="border:0"></i>
                     </a>';


        }


        if (in_array('view', $actions)) {

            $view = '<a
                            action="view"
                            href="' . site_url($site_url . '/view/' . $rows[$id_field] . '/' . $qstring['view']) . '"
                            data-toggle="tooltip" class="tooltips" data-original-title="View"><i class="fa fa-eye th-eye"></i>
                         </a>';

        }

        if (in_array('view_attendance', $actions)) {
            $view_attendance = '<a
                            action="view_attendance"
                            href="' . site_url(ADMIN_DIR . '/attendance?search[account_id]=' . $rows[$id_field] . '' . $qstring['view']) . '"
                            data-toggle="tooltip" class="tooltips" data-original-title="View Attendance"><i class="fa fa-eye"></i>
                         </a>';

        }

        if (in_array('view_numbers', $actions)) {
            $view_numbers = '<li><a
                            action="view_numbers"
                            href="' . site_url($site_url . '/view_numbers/' . $rows[$id_field] . '/' . $qstring['view_numbers']) . '"
                            class="tip show_popup" title="" data-original-title="View Numbers"><i class="icon-dashboard"></i>
                      </a></li>';
        }


        if (in_array('download', $actions)) {

            $download = '<li style=" width: 20px; float:left;">
                    <a title="Download" class="grid_button ui_dialog" action="check_status" href="' . $file_path['download'] . '">
                         <img src="' . base_url() . 'images/pictos/download.png" alt="" width="16" height="16"></p>
                    </a>
                </li>';
        }
        if (in_array('delete_file', $actions)) {

            $delete_file = '<li style=" width: 20px; float:left;">
                    <a title="Delete File" class="grid_button ui_dialog" action="check_status" href="' . $file_path['delete_file'] . '">
                         <img src="' . base_url() . 'images/pictos/delete_file.png" alt="" width="16" height="16"></p>
                    </a>
                </li>';
        }
        if (in_array('view_calls', $actions)) {

            $view_calls = '<li><a
                                action="view_calls"
                                href="' . site_url($site_url . '/view_calls/' . $rows[$id_field] . '/' . $qstring['view']) . '"
                                class="tip show_popup" title="" data-original-title="View Calls"><i class="icon-tasks"></i>
                          </a></li>';
        }

        /*---------------------------------------------------------------------------------------------*/
        //$user_actions = array('new', 'edit', 'delete', 'print');
        $form_btn = '';
        foreach ($buttons as $key => $button) {
            if (is_array($button)) {
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                # @start Modules action Conditions
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                if (!in_array($rows['status'], array('accepted', 'approved')) && $module == 'purchase_orders') {
                    unset($user_actions[array_search('pi_approved', $user_actions)]);
                }
                if ($rows['status'] == 'shipped' && $module == 'sales_orders') {
                    unset($user_actions[array_search('edit', $user_actions)]);
                }
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                # @End Modules action Conditions
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                if (in_array($key, $user_actions)) {
                    $form_btn .= ${$key};
                }
            } else {
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                # @start Modules action Conditions   //continue
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                if ($rows['status'] == 'shipped' && $module == 'packing_list') {
                    unset($user_actions[array_search('edit', $user_actions)]);
                }
                if ($rows['status'] == 'shipped' && $module == 'sales_orders') {
                    unset($user_actions[array_search('edit', $user_actions)]);
                }
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                # @End Modules action Conditions
                # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                //echo '<pre>';print_r($user_actions);echo '</pre>';
                if (in_array($button, $user_actions)) {
                    $form_btn .= ${$button};
                }
            }
        }

        return $action_btn = '<ul class="table-controls">' . $form_btn . '</ul>';
    }
}