<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_members_types extends CI_Model
{
    var $table = 'acc_types';
    var $id_field = 'acc_type_ID';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

    function validate()
    {
        $this->form_validation->set_rules('Name', 'Member Type Name', 'required');
        $this->form_validation->set_rules('service_period', 'Service Period', 'required');
        $this->form_validation->set_rules('service_charges', 'Service Charges', 'required');
        $this->form_validation->set_rules('service_offered', 'Service Offered', 'required');


        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */