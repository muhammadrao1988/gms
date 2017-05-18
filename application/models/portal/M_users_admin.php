<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_users_admin extends CI_Model
{
    var $table = 'users';
    var $id_field = 'user_id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

    function validate()
    {
        $this->form_validation->set_rules('user_type', 'User type', 'required');
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('surname', 'Surname', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('mob_phone', 'Mobile number', 'required');
        $this->form_validation->set_rules('machine_serial', 'Machine Serial Number', 'required');
       /* $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('retype_new_pwd', 'Retype Password', 'required');*/

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }


    function get_username($username)
    {
        $result = $this->db->query("SELECT acc_id FROM accounts WHERE username='" . $username . "'")->row();
        if ($result->user_id != "") {
            return '0';
        } else {
            return '1';
        }
    }

    function check_username($username)
    {

        $username = strtolower(trim($username));
        if ($this->get_username($username) == 1) {
            return $username;
        } else {
            $this->tmp_i++;
            if ($this->tmp_i < 10) {
                $tmp_n = '0' . $this->tmp_i - 1;
                $username = str_replace('0' . $tmp_n, '', $username);
                $username = $username . '0' . $this->tmp_i;
            } else {
                $username = str_replace($this->tmp_i - 1, '', $username);
                $username = $username . $this->tmp_i;
            }
            return $this->check_username($username);
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */