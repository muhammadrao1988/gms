<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_receipt extends CI_Model
{
    var $table = 'invoice_receipt';
    var $id_field = 'receipt_id';

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
        //$this->form_validation->set_rules('type[]', 'Fees Type', 'required');
        //$this->form_validation->set_rules('amount[]', 'Amount', 'required');
        //$this->form_validation->set_rules('fees_month', 'Fees Month', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
    function validate_payment(){
        $this->form_validation->set_rules('invoice_id', 'Invoice Id', 'required');
        $this->form_validation->set_rules('fees', 'Invoice Amount', 'required');
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