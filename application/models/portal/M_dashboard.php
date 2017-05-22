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
    function existMembersAttendance(){
        return $this->db->query("SELECT *,CONCAT(account_id,'_',check_type,'_',machine_serial) as account_check FROM attendance WHERE `datetime` >= '".date('Y-m-d')." 00:00:00' and `status` = 1")->result_array();
    }

}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */