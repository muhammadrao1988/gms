<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_members extends CI_Model
{
    var $table = 'accounts';
    var $id_field = 'acc_id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

    function validate()
    {
        $this->form_validation->set_rules('acc_name', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('acc_tel', 'Mobile number', 'required');
        $this->form_validation->set_rules('acc_types', 'Member Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */