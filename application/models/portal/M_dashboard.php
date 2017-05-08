<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
    var $table = '';
    var $id_field = '';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */