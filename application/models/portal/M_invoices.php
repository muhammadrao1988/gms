<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_invoices extends CI_Model
{
    var $table = 'invoices';
    var $id_field = 'id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

    function validate()
    {
        $this->form_validation->set_rules('acc_id', 'Account Name', 'required');
        $this->form_validation->set_rules('type[]', 'Fees Type', 'required');
        $this->form_validation->set_rules('amount[]', 'Amount', 'required');
        $this->form_validation->set_rules('fees_month', 'Fees Month', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */