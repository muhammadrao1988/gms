<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_subscription extends CI_Model
{
    var $table = 'subscriptions';
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
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('period', 'Number of Days', 'required');
        $this->form_validation->set_rules('charges', 'Charges', 'required');



        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */