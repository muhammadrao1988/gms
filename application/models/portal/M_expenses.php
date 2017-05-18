<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_expenses extends CI_Model
{
    var $table = 'expenses';
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
        $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required');
        $this->form_validation->set_rules('vendor_contact', 'Vendor Contact', 'required');
        $this->form_validation->set_rules('vendor_address', 'Vendor Address', 'required');
        $this->form_validation->set_rules('expense_status', 'Status', 'required');

        $this->form_validation->set_rules('expense_date', 'Date', 'required');
        $this->form_validation->set_rules('label[]', 'Expense Entry Label', 'required');
        $this->form_validation->set_rules('amount[]', 'Expense Amount', 'required');



        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */