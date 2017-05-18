<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_form_actions')) {

    function get_form_actions($buttons = array('new', 'save', 'edit', 'update'), $module_uri = 2)
    {

        $CI = &get_instance();
        $controller = $CI->router->fetch_class();
        $controller_method = $CI->router->fetch_method();
        $module = $controller;

		/*if($CI->session->userdata['u_type']==3){
				 $user_template_table = $CI->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['parent_id']."'")->num_rows();
				 if($user_template_table==0){
				  $table_temp	= 'user_type_module_rel';
				  $user_type_id	= intval($CI->session->userdata('u_type'));
				 }else{
				   $table_temp	= 'user_template_methods';
				   $and_condition = ' AND acc_id = "'.$CI->session->userdata['parent_id'].'"';
				   $user_type_id	= $CI->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['parent_id']."'")->row();
				   $user_type_id	= $user_type_id->user_type_id;
				 }
			}else */
			//if($CI->session->userdata['u_type']==4 || $CI->session->userdata['u_type']==3){
				 $user_template_table = $CI->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$CI->session->userdata['user_info']->acc_id."'")->num_rows();
				 if($user_template_table==0){
				   $table_temp	= 'user_type_module_rel';
				   //$user_type_id	= intval($CI->session->userdata('u_type'));
                   $user_template_id = $CI->db->query("SELECT user_template_id FROM accounts WHERE acc_id = '" . $CI->session->userdata['user_info']->acc_id . "'")->row();
                   $user_template_id = $user_template_id->user_template_id;
				 }else{

                     $table_temp = 'user_template_methods';

                     $and_condition = ' AND um.acc_id = "' . $CI->session->userdata['user_info']->acc_id . '"';
                     $user_template_id = $CI->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '" . $CI->session->userdata['user_info']->acc_id  . "'")->row();
                     $user_template_id = $user_template_id->user_type_id;
                 }
			/*}else{
				 	$table_temp	= 'user_type_module_rel';
					$user_type_id	= intval($CI->session->userdata('u_type'));
			}*/

        //$user_actions = $CI->session->userdata('actions');
        $user_actions = $CI->db->query("SELECT um.actions FROM accounts AS u INNER JOIN ".$table_temp." AS um ON (u.user_template_id = um.user_type_id) INNER JOIN modules AS m ON (m.id = um.module_id) WHERE um.user_type_id = '".intval($user_template_id)."' ".$and_condition." AND m.`module`='".addslashes($module)."'")->row()->actions;

        $search = array('update');
        $replace = array('save');
        $user_actions = explode('|', str_replace($search, $replace, $user_actions));
        if (in_array('add', $user_actions)) {
            array_push($user_actions, 'new');
        }
        if (in_array('new', $user_actions)) {
            array_push($user_actions, 'save');
        }
        if (in_array('import', $user_actions)) {
            array_push($user_actions, 'import_db');
        }

        if (in_array('new', $buttons)) {
            $new = '<button class="btn btn-white" data-action="' . site_url(ADMIN_DIR . $module . '/form') . '" type="button"><i class="fa fa-file-o"></i> New </button>
                        <span class="vert-sep"></span>';
        }
        if (in_array('delete', $buttons)) {
            $delete = '<a href="#myModal2" data-toggle="modal" class="del-data delete-all" id="22"  data-original-title="Delete"><button class="btn btn-white" type="button" id="delete-all">
			
			<i class="fa fa-trash-o"></i> Delete 
			
			</button></a>
                        <span class="vert-sep"></span>';
        }
        if (in_array('edit', $buttons)) {
            $edit = '<li><a action="edit" href="' . site_url(ADMIN_DIR . $module . '/form/') . '" title=""><i class="icon-edit"></i><span>Edit</span></a></li>';
        }
        if (in_array('update', $buttons)) {
            $update = '<li><a action="update" href="' . site_url(ADMIN_DIR . $module . '/update') . '" title=""><i class="icon-edit"></i><span>Update</span></a></li>';
        }
        if (in_array('print', $buttons)) {
            $print = '<li><a action="print" href="javascript: void(0);" title=""><i class="icon-print"></i><span>Print</span></a></li>';
        }
        if (in_array('save', $buttons)) {
            $save = '<li><a action="save" href="javascript: void(0);" title=""><i class="icon-save"></i><span>Save</span></a></li>';
        }
        if (in_array('reset', $buttons)) {
            $reset = '<li><a action="reset" href="javascript: void(0);" title=""><i class="icon-refresh"></i><span>Reset</span></a></li>';
        }
        if (in_array('back', $buttons)) {
            $back = '<li><a action="back" href="javascript: window.back();" title=""><i class="icon-backward"></i><span>Back</span></a></li>';
        }
        if (in_array('export_csv', $buttons)) {
            $param = $_SERVER['QUERY_STRING'];
            if ($param == "") {
                $param = '?download_csv=1';
            } else {
                $param = '/?' . $_SERVER['QUERY_STRING'] . '&download_csv=1';
            }
            $export_csv = '<li><a action="export_csv"href="' . site_url(ADMIN_DIR . $module . $param) . '" title=""><i class="icon-download"></i><span>Export CSV</span></a></li>';
        }
        if (in_array('refresh', $buttons)) {
            $refresh = '<li><a action="refresh" href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" title=""><i class="icon-refresh"></i><span>Refresh</span></a></li>';
        }
        if (in_array('view', $buttons)) {
            $view = '<li><a action="view" href="' . site_url(ADMIN_DIR . $module . '/view') . '" title=""><i class="icon-eye-open"></i><span>View</span></a></li>';
        }


        if (in_array('calendar', $buttons)) {
            $calendar = '<li>
                    <a class="form_button" action="calendar" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/calendar/">
                        <div class="button_icon">
                            <p align="center">
                                <img src="' . ADMIN_VIEWS_URL . 'images/pictos/calendar.png" alt="" width="40" height="40"></p>
                        </div>
                        <div><p align="center">Calendar</p></div>
                    </a>
                </li>';
        }


        if (in_array('export_xml', $buttons)) {
            $export_xml = '<li>
                            <a class="form_button" action="export_xml" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/export/xml">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/settings11.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">Export XML</p></div>
                            </a>
                        </li>';
        }
        if (in_array('export_xls', $buttons)) {
            $export_xls = '<li>
                            <a class="form_button" action="export_xls" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/export_xls/' . $CI->uri->segment(3) . '/">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/ms-excel.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">Export XSLs</p></div>
                            </a>
                        </li>';
        }
        if (in_array('import', $buttons)) {
            $import = '<li>
                            <a class="form_button" action="import_csv" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/import/">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/inbox2.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">Import</p></div>
                            </a>
                        </li>';
        }

        if (in_array('import_db', $buttons)) {
            $import_db = '<li>
                            <a class="form_button" action="import_db" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/import/">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/inbox2.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">Import</p></div>
                            </a>
                        </li>';
        }


        if (in_array('db_backup', $buttons)) {
            $db_backup = '<li>
                            <a class="form_button" action="db_backup" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/db_backup">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/data.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">DB Backup</p></div>
                            </a>
                        </li>';
        }

        if (in_array('download', $buttons)) {
            $download = '<li>
                            <a class="form_button" action="download" href="' . DOMAIN_ACTION_URL . $CI->uri->segment(1) . '/download">
                                <div class="button_icon">
                                    <p align="center">
                                        <img src="' . ADMIN_VIEWS_URL . 'images/pictos/download.png" alt="" width="40" height="40">
                                    </p>
                                </div>
                                <div><p align="center">Download</p></div>
                            </a>
                        </li>';
        }


        /*---------------------------------------------------------------------------------------------*/
        $user_actions[] = 'back';

        $form_btn = '';
        foreach ($buttons as $button) {
            if (in_array($button, $user_actions)) {
                $form_btn .= ${$button};
            }
        }


        return $action_btn = (!empty($form_btn) ? '<div class="row page-top-btn" style="margin:0"><div class="btn-row"><div class="btn-group">' . $form_btn . '</div></div></div>' : '');

    }


}