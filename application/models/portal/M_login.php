<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_login extends CI_Model
{
    const PUBLICKEY = "6Le8o9kSAAAAAL4SmfcfE3aafOqNroRV295h-qXF";
    const PRIVATEKEY = "6Le8o9kSAAAAAHSCqKwaeA5Hkxq46xA8MTFNHfeC";

    function __construct()
    {
        parent::__construct();

    }

    function validate()
    {
        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function checkUser($where)
        {
            $q = "SELECT * FROM `users` LEFT JOIN `security_q` ON (`users`.`security_id` = `security_q`.`sec_id`) WHERE 1 " . $where;
            $query = $this->db->query($q);

            if ($query->num_rows > 0) {
                return $query->row();
            } else {
                return false;
            }
        }
	function account_name($acc_id)
        {
            $q = "SELECT acc_name FROM `accounts` WHERE acc_id = '".$acc_id."'";
            $query = $this->db->query($q);

            if ($query->num_rows > 0) {
                return $query->row();
            } else {
                return false;
            }
        }	



    function updateResetKey($id)
    {
        $reset_key = md5(rand() * 545);
        $this->db->where('user_id', $id);
        $this->db->update('users',array('reset_key' => $reset_key,'modified_date' => date('Y-m-d H:i:s')));
        return $reset_key;
    }

    function resetPasswordFromKey($key, $reset_pass)
    {
        $this->db->query("UPDATE `users` SET `password` = '".md5($reset_pass)."', `reset_key` = '' WHERE reset_key=".$this->db->escape($key)."");
        if($this->db->affected_rows()){
            return $reset_pass;
        }else{
            return false;
        }
    }

	function getUserQuestion($user_id){
		$query = "SELECT security_q.sec_question FROM users INNER JOIN security_q ON (users.security_id = security_q.sec_id AND users.user_id='".$user_id."')";
		$query = $this->db->query($query);
		if ($query->num_rows > 0) {
            return $query->row();
        } else {
            return false;
        }
	}

    function chklogin($username, $password){
        //todo:: _users
        $SQL = "SELECT users.* ,accounts.acc_name
		FROM users 
		JOIN accounts ON (users.parent_child = accounts.acc_id )
		WHERE username='" . ($username) . "' AND accounts.status = 1 ";

        $result = $this->db->query($SQL);

        if($result->num_rows() > 0){
            $result_data = $result->row();
            if($result_data->login_status==3 || $result_data->password==$password){
                return $result_data;
            }else {
            return false;
            }

        }else{
            return false;
        }
    }
    function step1_validate(){
        $this->form_validation->set_rules('select_question', 'Select Question', 'required');
        $this->form_validation->set_rules('answer', 'Answer', 'required');
        $this->form_validation->set_rules('confirm_answer', 'Confirm Answer', 'required');
        $this->form_validation->set_rules('answer', 'Answer', 'required|matches[confirm_answer]');
        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
    function step2_validate(){
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');
        $this->form_validation->set_rules('password', 'Confirm Answer', 'required|matches[confirm_password]');
        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }


}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */