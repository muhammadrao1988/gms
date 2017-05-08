<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_attendance extends CI_Model
{
    var $table = 'attendance';
    var $id_field = 'id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }
    function validate_id_validate()
    {

        $this->form_validation->set_rules('account_id', 'Member ID', 'callback_account_exist');
        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
    function validate()
    {
        $this->form_validation->set_rules('acc_id', 'Member ID', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */