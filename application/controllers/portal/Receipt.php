<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Banned_users
 * @property M_invoices $module
 * @property M_cpanel $m_cpanel
 */
class Receipt extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;

    function __construct()
    {

        parent::__construct();
        $this->m_cpanel->checkLogin();

        //TODO:: Module Name
        $this->module_name = getUri(2);
        $this->module = 'm_' . $this->module_name;;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;
        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->branch_id = getVal("users", "branch_id", " WHERE user_id='" . $this->session->userdata('user_info')->user_id . "'");
        $this->is_machine = $this->session->userdata('user_info')->is_machine;
        $this->iic_user_type = intval(get_option('iic_user_type'));
    }

    public function index()
    {
        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        $search = getVar('search');
        if($search['receipt_date']!=""){
            $where = str_replace(
                "AND receipt_date LIKE '%".$search['receipt_date']."%'",
                " AND receipt_date LIKE '%".date('Y-m-d',strtotime($search['receipt_date']))."%'",
                $where);

        }

        $data['query'] = "SELECT                               
                               re.`invoice_id`,
                               re.`receipt_id`,
                               ac.machine_member_id ,
                               ac.acc_name,
                               DATE_FORMAT(re.`receipt_date`,'%d-%b-%Y') AS receipt_date,
                               re.`received_amount`

                            FROM
                              invoice_receipt AS re
                              INNER JOIN invoices inv ON(re.invoice_id=inv.id)
                               INNER JOIN accounts AS ac
                                ON (ac.acc_id = re.acc_id) WHERE 1 AND re.branch_id = '" . $this->branch_id . "' " . (($this->is_machine == 1) ? " AND ac.machine_member_id !='' " : '') . "
                           " . $where." GROUP BY re.receipt_id";
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }



    public function view()
    {
        if (getUri(4) != '') {
            $id = intval(getUri(4));
        } elseif (getVar('id') != '') {
            $id = getVar('id');
        } else {
            $this->load->view(ADMIN_DIR . $this->module_name . '?error=Invoice id not found.');
        }

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . " IN (" . $id . ") AND branch_id='" . $this->branch_id . "'";

            $data['row'] = $this->db->query($SQL)->row();


            if ($data['row']->receipt_id == "") {
                redirect(ADMIN_DIR . $this->module_name . '/?error=Invalid access');
            }
            $SQL2 = "SELECT * FROM accounts WHERE acc_id='" . $data['row']->acc_id . "'";
            $data['row2'] = $this->db->query($SQL2)->row();
        }
        $data['buttons'] = array();
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/view', $data);
    }

}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */