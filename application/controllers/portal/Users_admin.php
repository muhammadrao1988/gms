<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_users_admin $module
 * @property M_cpanel $m_cpanel
 */
class Users_admin extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;

    function __construct()
    {
       /* parent::__construct();
        $this->m_cpanel->checkLogin();


        $this->module_name = getUri(2);
        $this->module = 'm_' . $this->module_name;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;

        $this->module_title = $this->m_cpanel->moduleDetail()->module_title;
        $this->iic_user_type = intval(get_option('iic_user_type'));  */


        parent::__construct();
        $this->m_cpanel->checkLogin();

        //TODO:: Module Name
        $this->module_name = getUri(2);
        $this->module = 'm_' . $this->module_name;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;

        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->iic_user_type = intval(get_option('iic_user_type'));

    }


    public function index()
    {

        $where = '';

        $data['title'] = $this->module_title;
        /*$data['query'] = "SELECT
          users.user_id,
          users.username,
          users.email,
          CONCAT(users.first_name,' ', users.surname) as full_name,
          ac_r.acc_name AS reseller,
          ac_c.acc_name AS company,
          ac_s.short_desc AS user_login_status
        FROM
          users
          LEFT JOIN accounts ac_c
            ON (users.parent = ac_c.acc_id AND ac_c.acc_type IN (" . COMPANY_IDS . ") )
          LEFT JOIN accounts ac_r
            ON ( users.parent = ac_c.acc_id AND ac_r.acc_type IN (" . RESELLER_IDS . ") AND ac_c.parent = ac_r.acc_id )
          LEFT JOIN account_status ac_s
            ON (users.login_status = ac_s.id)
        WHERE 1 ". $where;*/
		 $data['query'] = "SELECT
          users.user_id,
          users.username,
          users.email,
          CONCAT(users.first_name,' ', users.surname) as full_name,
          ac_c.acc_name AS company,
		  ac_r.acc_name AS reseller,
          
          ac_s.short_desc AS user_login_status
        FROM
          users
          LEFT JOIN accounts ac_c
            ON (users.parent_child = ac_c.acc_id AND ac_c.acc_types IN (" . COMPANY_IDS . ") )
          LEFT JOIN accounts ac_r
            ON ( users.parent = ac_r.acc_id AND ac_r.acc_types IN (" . RESELLER_IDS . ")  )
          LEFT JOIN account_status ac_s
            ON (users.status = ac_s.id)
        WHERE 1 ". str_replace("reseller =","ac_r.`acc_id` =",str_replace("company =","ac_c.`acc_id` =",$where));
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }


    public function form()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
            $data['row']->user_type = strtolower(getVal('user_types', 'user_type', "WHERE id='" . $data['row']->u_type . "'"));
            if ($data['row']->u_type == RESELLER_TYPE_ID) {
                $data['row']->reseller_id = $data['row']->parent;
            }

        } else if (getVar('reseller_id') > 0) {
            $data['row'] = new stdClass();
            $data['row']->reseller_id = getVar('reseller_id');
            $data['row']->user_type = 'reseller';
        }

        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }

    public function view()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }
        $data['buttons'] = array();
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . '/includes/record_view', $data);
    }


    public function add()
    {
        $email_exists = getVal('users', 'email', 'WHERE `email`="' . getVar('email') . '"');
        if (!empty($email_exists)) {
            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            $username = $this->module->check_username(strtolower(str_replace(' ', '', getVar('first_name') . getVar('surname'))));
            $password = random_string('alnum', 8);

            $_POST['username'] = $username;
            $_POST['password'] = encryptPassword($password);


            $_POST['u_type'] = getVal('user_types', 'id', "WHERE LOWER(user_type)='" . getVar('user_type') . "'");
            if (getVar('user_type') == 'customer') {
                $_POST['user_type'] 		= '7';
                $_POST['parent'] 			= getVar('reseller', TRUE, FALSE);
				$_POST['parent_child'] 		= getVar('company', TRUE, FALSE);
				$owner 				   		= getVar('company', TRUE, FALSE);	
                $email_name 				= 'New User Created';
				
            } else if (getVar('user_type') == 'reseller') {
                $_POST['user_type'] 		= '6';
                $_POST['parent_child'] 		= getVar('reseller', TRUE, FALSE);
				$reseller					= getVar('reseller', TRUE, FALSE);	
				$_POST['parent'] 			= getVal('accounts', 'parent', "WHERE acc_id='" . $reseller . "'");
				$owner 						= getVar('reseller', TRUE, FALSE);	
                $email_name 				= 'New Reseller User Created';
				
            } else if (getVar('user_type') == 'administrator') {
                $_POST['user_type'] 		= '5';
                $_POST['parent'] 			= '50000';
				$_POST['parent_child'] 		= '50000';
				$owner 						= 50000;
                $email_name 				= 'New Admin User Created';
				
            }else if(getVar('user_type') == 'super admin') {
				$_POST['user_type'] 		= '1';
				$_POST['parent'] 			= '50000';
				$_POST['parent_child'] 		= '50000';
				$owner 						= 50000;
			}
			#for log

			$user_log_array = array(
				'date'			=> date('Y-m-d H:i:s'),
				'user_id'		=> sessionVar('user_id'),
				'owner'			=> $owner,
				'type_id'		=> 15,
				'display_state'	=> 2,
				'ip_addr'		=> user_ip()
			);
			save('audit_log', $user_log_array, $where = '');
			#end

            $DbArray = getDbArray($this->table);

            $DBdata = $DbArray['dbdata'];
		
            $id = save($this->table, $DBdata);

            /*--------------------------------------EmailTemplate--------------------------------------*/
            $_POST['password'] = $password;
             if(getVar('user_type')!="1")
             {
                    $email_data = getEmailTemplate($_POST, $email_name);
                    $this->email->subject($email_data->subject);
                    $this->email->message($email_data->email_content);

                    $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
                    $this->email->to(getVar('email'));

                    $this->email->set_mailtype('html');
                    if ($this->email->send()) {
                        $emailMsg = '&info=A new Telebox user has been added and an email sent';
                    } else {
                        $emailMsg = '&error=Email sending failed...';
                    }
             }
            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..' . $emailMsg);
        }
    }


    public function update()
    {
        $email_exists = getVal('users', 'email', 'WHERE `email`="' . getVar('email') . '" AND user_id !="' . getVar('user_id') . '"');
        if ($email_exists != '') {
			
            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            $_POST['u_type'] = getVal('user_types', 'id', "WHERE LOWER(user_type)='" . getVar('user_type') . "'");
            if (getVar('user_type') == 'customer') {
                $_POST['user_type'] 		= '7';
                $_POST['parent'] 			= getVar('reseller', TRUE, FALSE);
				$_POST['parent_child'] 		= getVar('company', TRUE, FALSE);
            
			} elseif (getVar('user_type') == 'reseller') {
                $_POST['user_type'] 		= '6';
                $_POST['parent_child'] 		= getVar('reseller', TRUE, FALSE);
				$reseller					= getVar('reseller', TRUE, FALSE);	
				$_POST['parent'] 			= getVal('accounts', 'parent', "WHERE acc_id='" . $reseller . "'");
            } elseif (getVar('user_type') == 'administrator') {
                $_POST['user_type'] = '5';
                //$_POST['parent'] = '0';
            } elseif (getVar('user_type') == 'super admin') {
                $_POST['user_type'] = '1';
                //$_POST['parent'] = '0';
            }
            /*------------------------------------------------------------------------------------------*/

            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
			
			

            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);

            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');

        }
    }

    function status()
    {
		$JSON = array();
        $id = getVar('status-id');
        $login_status_val = getVal('users', 'status', 'WHERE user_id ="' . $id . '"');
        if($login_status_val==0 ||  $login_status_val==2 || $login_status_val==3 ){
            $status=1;
        }else if($login_status_val==1){
            $status=0;
        }else{
            $status=3;
        }

        $where = $this->id_field . "='" . $id . "' ";
        save($this->table, array('status' => $status), $where);
		$JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Status has been changed...</div>';
		$redirct_url 		  =  '?msg=Status has been changed..' ;
		$JSON['redirect_url'] =  $redirct_url;
		echo json_encode($JSON);
		
       
    }


    public function delete()
    {
		$JSON = array();
		if(getVar('action')==""){
		$id = getVar('del-id');
		}else{
		$id = getVar('del-all');	
		}
		
      
        $SQL = "DELETE FROM " . $this->table . " WHERE `" . $this->id_field . "` IN(" . $id . ")";
        $this->db->query($SQL);
		$JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Record has been deleted..</div>';
		$redirct_url 		  =  '?msg=Record has been deleted..' ;
		$JSON['redirect_url'] =  $redirct_url;
		echo json_encode($JSON);
		
       
    }


     function send_new_password()
     {

        $id = intval(getUri(4));
        $data["id"]=$id;


        if (($this->input->post('submit') !== false)&&($this->input->post('password')==$this->input->post('retype_new_pwd')))
        {
            $password = $this->input->post('password');
            $user_id = $this->input->post('user_id');

            $sql_user_status = "SELECT users.`status`
                         FROM users
                         WHERE users.user_id='".$user_id."'";
            $user_status = $this->db->query($sql_user_status)->row();
            /*updating new password*/
            $where = "user_id='" . $user_id . "' ";
            if($user_status->status==3) {
                save('users', array('password' => encryptPassword($password), 'login_status' => 2, 'status' => 1, 'reset_code' => ''), $where);
            }else{
                save('users', array('password' => encryptPassword($password)), $where);
            }

			
			#log generate
			$owner =$this->session->userdata['user_info']->parent_child;	
			
			$user_log_array = array(
						'date'			=> date('Y-m-d H:i:s'),
						'user_id'		=> $user_id,
						'owner'			=> $owner,
						'type_id'		=> 9,
						'display_state'	=> 2,
						'ip_addr'		=> user_ip()
					);
		   save('audit_log', $user_log_array, $where = '');

            if(empty($_POST['send_email'])){
			/*selecting user info*/
            $p=getValues('users',"*", "WHERE user_id='" . $user_id . "'");
            $email_name="New Password Send";

            /*sending email for new password*/
            $_POST['first_name']=$p->first_name;
            $_POST['username']=$p->username;

            $email_data = getEmailTemplate($_POST, $email_name);

            $this->email->subject($email_data->subject);
            $this->email->message($email_data->email_content);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->to($p->email);

            $this->email->set_mailtype('html');
            if ($this->email->send()) {
                $emailMsg = '?msg=The password has been changed and email sent.';
            } else {
                $emailMsg = '?error=Email sending failed...';
            }
			}else{
			$emailMsg = '?msg=The password has been changed';	
			}

            redirect(ADMIN_DIR . $this->module_name . '/'.$emailMsg);
        }


        $this->load->view(ADMIN_DIR . $this->module_name . '/send_password',$data);
    }
	
	 function account_access()
     {

        $id = intval(getUri(4));
        $data["id"]=$id;


        if (($this->input->post('submit') !== false))
        {
            $access_account = $this->input->post('access_account');
            $user_id = $this->input->post('user_id');


            /*updating new password*/
            $where = "user_id='" . $user_id . "' ";
            save($this->table, array('password' => encryptPassword($password)), $where);
			
			if($_POST['access_account']==2){
			$where = "user_id='" . $user_id . "' ";
			$password = random_string('alnum', 8);
            save($this->table, array('reset_key' => '','login_status'=>1,'password'=>encryptPassword($password)), $where);	
			 if(empty($_POST['send_email'])){
			/*selecting user info*/
            $p=getValues('users',"*", "WHERE user_id='" . $user_id . "'");
            $email_name="Telebox Account Unlock";
			$msg = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					<title>Account Unlock</title>
					</head>
					<body style="background: #fff; margin: 0px auto; font-family: Calibri;">
					<div style="width: 620px;  background: #ffffff; margin:0 auto;">
					  <div style="background:#275d90; height:65px;"><img src="http://www.telebox.co.uk/assets/images/small_logo.png" style="padding-left:15px; padding-top:15px;" /></div>
					  <div style="padding:15px; ">
						<p style="font-size: 21px; font-weight:bold;">Hi '.$p->first_name.',</p>
						<p style="font-size: 16px;">Your account has unlocked.</p>
						<p style="font-size: 16px;">Your Telebox login details have recently been updated :</p>
						<p style="font-size: 16px; padding-top:5px; font-weight:bold"> Username: '.$p->username.'<br>
						  Password: '.$password.' </p>
						<p style="font-size: 16px; padding-top:5px;"> To access your account please visit <br/>
						  <a href="http://www.telebox.co.uk/portal" style="color:#ee7b2a; font-weight:bold;">http://www.telebox.co.uk/portal</a> 
						  
						  </p>
						<p style="font-size: 16px;padding-top:5px;"> Thanks,<br>
						  The Telebox Team </p>
					  </div>
					  <div style="background:#393939;">
						<div style="font-size: 15px; color:#949494; padding:15px;">This email was sent to you as a register user of <a href="www.telebox.co.uk/portal" style="color:#949494">www.telebox.co.uk</a><br />
						  If you need any assistance please contact your account manager, or email <a href="mailto:help@telebox.co.uk" style="color:#949494">help@telebox.co.uk</a></div>
					  </div>
					  <div style="background:#393939; border-top:1px solid #949494">
						<div style="font-size: 15px; color:#fff; padding:15px;">Copyright &copy; '.date('Y').' <a href="www.telebox.co.uk/portal" style="color:#fff; text-decoration:none;">telebox.co.uk</a> All rights reserved</div>
					  </div>
					</div>
					</div>
					</body>
					</html>';

           

            //$email_data = getEmailTemplate($_POST, $email_name);

            $this->email->subject($email_name);
            $this->email->message($msg);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->to($p->email);

            $this->email->set_mailtype('html');
            if ($this->email->send()) {
                $emailMsg = '?msg=The password has been changed and email sent.';
            } else {
                $emailMsg = '?error=Email sending failed...';
            }
			}else{
			$emailMsg = '?msg=Account unlock successfully';	
			}
				
			}
			
			if($_POST['access_account']==1){
			$where = "user_id='" . $user_id . "' ";
            save($this->table, array('status'=>2), $where);	
				
			}
			
			
			

           

            redirect(ADMIN_DIR . $this->module_name . '/'.$emailMsg);
        }


        $this->load->view(ADMIN_DIR . $this->module_name . '/account_access',$data);
    }


    function AJAX()
    {
        $action = getUri(4);
        $JSON['data'] = '';
        switch ($action) {
            case 'reseller_companies':

                $JSON['data'] .= '<option value=""> - Select -</option>';
                $JSON['data'] .= selectBox("SELECT acc_id, acc_name FROM accounts where acc_types IN(" . COMPANY_IDS . ") and parent = '" . getVar('reseller_id') . "' ");
                break;
        }

        echo json_encode($JSON);
    }
	


}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */