<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_modules extends CI_Model
{
    var $table = '';
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
        $this->form_validation->set_rules('parent_id', 'Parent', 'required');
        $this->form_validation->set_rules('module', 'Module', 'required');
        $this->form_validation->set_rules('module_title', 'Module Title', 'required');
        $this->form_validation->set_rules('ordering', 'Ordering', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */