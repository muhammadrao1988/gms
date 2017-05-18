<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_cpanel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function checkLogin()
    {

        if ($this->session->userdata('tgm_user_id') == '') {
            redirect(ADMIN_DIR . 'login');
        }
        if(getUri(1) == substr(ADMIN_DIR,0,-1)){
            $this->checkModule();
        }
    }

    function checkModulePermission($module){




        //if($this->session->userdata['u_type']==4 || $this->session->userdata['u_type']==3){
        $user_template_table = $this->db->query("SELECT module_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['user_info']->user_id."'")->num_rows();
        if($user_template_table==0){
            $table			= 'user_type_module_rel';

            //$user_template_id	= intval(sessionVar('user_template_id'));
            $user_template_id = $this->db->query("SELECT user_template_id FROM users WHERE user_id = '".$this->session->userdata['user_info']->user_id."'")->row();
            $user_template_id = $user_template_id->user_template_id;
        }else{

            $table	= 'user_template_methods';
            $and_condition = ' AND um.acc_id = "'.$this->session->userdata['user_info']->user_id.'" ';
            $user_template_id	= $this->db->query("SELECT user_type_id FROM user_template_methods WHERE acc_id = '".$this->session->userdata['user_info']->user_id."'")->row();
            $user_template_id	= $user_template_id->user_type_id;
        }


        $mod_sql = "SELECT
                       um.module_id AS module_id,
                       m.module AS module,
                       um.actions AS actions
                       
                   FROM
                       users AS u
                       INNER JOIN ".$table." AS um
                           ON (u.u_type = um.user_type_id)
                       INNER JOIN modules AS m
                           ON (m.id = um.module_id)
                      WHERE um.user_type_id='" . intval($user_template_id) . "' ".$and_condition."
                      AND m.module='" . addslashes($module) . "'
                      GROUP BY m.id";


        $mod_rs = $this->db->query($mod_sql);

        return $mod_rs;

    }

    function checkModule()
    {


        $module = $this->router->fetch_class();
        $action = $this->router->fetch_method();
        $user_type = intval($this->session->userdata['u_type']);


        $mod_rs = $this->checkModulePermission($module);


        if ($mod_rs->num_rows() > 0) {
            $U_modules = array();
            $actions = array();
            foreach ($mod_rs->result() as $u_module) {
                array_push($U_modules, $u_module->module_id);
                if (!($u_module->module == '#' || $u_module->module == ''))
                    $my_modules[$u_module->module_id] = $u_module->module;
                if ($u_module->actions != '') {
                    $actions[$u_module->module] = $u_module->actions;
                }
            }
        }



        $user_modules = $my_modules;

        $sql = "SELECT id, module,actions FROM modules WHERE module='" . addslashes($module) . "'";
        $mod_rs = $this->db->query($sql)->row();
        $module_actions = array_unique(explode('|', $mod_rs->actions));


        $user_actions = $actions;

        $search = array('edit', 'update', 'export_csv');
        $replace = array('update|form','update|form','export');


        $account_id 	= $this->session->userdata['user_id'];
        $account_name 	= getVal('users', 'first_name', 'WHERE `user_id`="'.$account_id.'"');


        if(count($actions) > 0) {
            $user_actions = array_unique(explode('|', str_replace($search, $replace, $user_actions[$module])));
            if (in_array('new', $user_actions)) {
                array_push($user_actions, 'add');
            }
        }
        if (in_array($module, $user_modules)) {

            array_push($user_actions, 'index');


            if (!in_array($action, $user_actions) && in_array($action, $module_actions)) {

                $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
                /*** return the full address ***/
                $protocol 		=	$protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

                $this->email->subject("Unauthorised Access Detected");
                $html_unauth = '<html">
							<head>
							<title> Unauthorised Access Detected</title>
							</head>
							
							<body>
							<p>The following account attempted to access an area of Telebox which they are not permitted.</p>
							<table border="0" cellpadding="3" cellspacing="3">
							<tr>
							<td>Account ID:</td>
							<td>'.$account_id.'</td>
							</tr>
							<tr>
							<td>Account Name:</td>
							<td>'.$account_name.'</td>
							</tr>
							<tr>
							<td>User ID:</td>
							<td>'.$this->session->userdata['user_id'].'</td>
							</tr>
							<tr>
							<td>Username:</td>
							<td>'.$this->session->userdata['username'].'</td>
							</tr>
							<tr>
							<td>Time:</td>
							<td>'.date('Y-m-d H:i:s').'</td>
							</tr>
							<tr>
							<td>Link Attempted To Access:</td>
							<td>'.$protocol.'</td>
							</tr>

							</table>
							</body>
							</html>';
                $this->email->message($html_unauth);

                $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
                $this->email->to('muhammadrao1988@gmail.com');

                $this->email->set_mailtype('html');
                $this->email->send();


                redirect(ADMIN_DIR .'dashboard?error=Sorry, you do not have access to this feature.');


                exit;
                //die('This module action has no access allowed');
            }



            return TRUE;


        } elseif (count($mod_rs) == 0) {
            return TRUE;


        } else {
            $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
            /*** return the full address ***/
            $protocol 		=	$protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $this->email->subject("Unauthorised Access Detected");
            $html_unauth = '<html">
							<head>
							<title>Unauthorised Access Detected</title>
							</head>
							
							<body>
							<p>The following account attempted to access an area of Telebox which they are not permitted.</p>
							<table border="0" cellpadding="3" cellspacing="3">
							<tr>
							<td>Account ID:</td>
							<td>'.$account_id.'</td>
							</tr>
							<tr>
							<td>Account Name:</td>
							<td>'.$account_name.'</td>
							</tr>
							<tr>
							<td>User ID:</td>
							<td>'.$this->session->userdata['user_id'].'</td>
							</tr>
							<tr>
							<td>Username:</td>
							<td>'.$this->session->userdata['username'].'</td>
							</tr>
							<tr>
							<td>Time:</td>
							<td>'.date('Y-m-d H:i:s').'</td>
							</tr>
							<tr>
							<td>Link Attempted To Access:</td>
							<td>'.$protocol.'</td>
							</tr>

							</table>
							</body>
							</html>';
            $this->email->message($html_unauth);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->to('muhammadrao1988@gmail.com');

            $this->email->set_mailtype('html');
            $this->email->send();

            redirect(ADMIN_DIR .'dashboard?error=Sorry, you do not have access to this feature.');



            exit;
            //die('This module has no access allowed');
        }

    }

    function parent_submenu($parent_id){



        $sql = "SELECT modules.* from modules
                    LEFT JOIN user_template_methods ON(modules.id= user_template_methods.module_id)
                    WHERE modules.parent_id='".$parent_id."' AND user_template_methods.acc_id='".$this->session->userdata['user_info']->user_id."' AND modules.status='active' GROUP BY modules.id";
        return $this->db->query($sql)->result();

    }

    function moduleDetail($module = ''){
        if(empty($module)){
            $module = getUri(2);
        }
        return getValues('modules', '*', "WHERE module='" . dbEscape($module) . "'");
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */