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

		 $data['query'] = "SELECT
          users.user_id,
          users.username,
          users.email,
          CONCAT(users.first_name,' ', users.surname) as full_name,
          branches.branch_name
        FROM
          users
           JOIN branches 
            ON (branches.id = users.branch_id)
         
        WHERE 1 ". $where;

        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }


    public function form()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();


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

        if($this->input->post('password')!=$this->input->post('retype_new_pwd')){
            $errors[] = $_POST['error'] = 'Password and Repeat password should be same.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            $username = $this->module->check_username(strtolower(str_replace(' ', '', getVar('first_name') . getVar('surname'))));
            $password = getVar('password');

            $_POST['username'] = $username;
            $_POST['password'] = encryptPassword($password);
            $_POST['parent'] = 50000;
            $_POST['status'] = 1;
            $_POST['login_status'] = 1;

			$user_log_array = array(
				'date'			=> date('Y-m-d H:i:s'),
				'user_id'		=> sessionVar('user_id'),
				'owner'			=> 50000,
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

            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..' );
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


        if (($this->input->post('submit') != "")&&($this->input->post('password')==$this->input->post('retype_new_pwd')))
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


	


}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */