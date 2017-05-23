<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_revenue $module
 * @property M_cpanel $m_cpanel
 */
class Revenue extends CI_Controller
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
        $this->module = 'm_' . $this->module_name;
        ;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;
        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->branch_id = getVal("users","branch_id"," WHERE user_id='".$this->session->userdata('user_info')->user_id."'");
        $this->iic_user_type = intval(get_option('iic_user_type'));
    }
    public function index()
    {
        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        $search_array = getVar('search');
        //$data['query'] = "Select * from invoices where MONTH (fees_datetime)= MONTH (CURRENT_DATE()) and YEAR(fees_datetime)=YEAR(CURRENT_DATE())";
        $data['search'] = $search_array;
        if(getVar('from') && getVar('to')){
            $from = date('Y-m-d 00:00:00',strtotime(getVar('from')));
            $to = date('Y-m-d 23:59:59',strtotime(getVar('to')));
        }elseif(getVar('from') !='' and getVar('to')==''){
            $from = date('Y-m-d 00:00:00',strtotime(getVar('from')));
            $to = $to = date('Y-m-d 23:59:59');;
        }else{
            $from = date('Y-m-01 00:00:00');
            $to = date('Y-m-d 23:59:59');
        }
        $filter = " AND fees_datetime BETWEEN '".$from."' AND '".$to."' ";
        $data['query'] = "Select id,
                              acc_id as account_id,
                              amount,
                              description,
                              fees_datetime,
                              fees_month,
                              SUM(amount) as total_amount_summary,
                               `type` as invoice_for from invoices where 1 AND branch_id = '".$this->branch_id."' ".$filter.$where." GROUP BY id " ;


        $chart = str_replace("GROUP BY id","",$data['query']);
        $chart = $chart." GROUP BY DATE(`fees_datetime`)";
        $data['chart_total'] = $this->db->query($chart)->result();
        foreach ($data['chart_total'] as $ct) {
            $total_amount[] = ($ct->total_amount_summary=="" ? 0 : $ct->total_amount_summary);
            $report_days[] = date('dM y', strtotime($ct->fees_datetime));
        }
        $data['total_amount'] = $total_amount;
        $data['report_days'] = $report_days;

        $data['summary_total'] = $this->db->query('SELECT 
                                              SUM(amount) AS summary_total
                                            FROM
                                              invoices                                             
                                            WHERE 1'.$filter.$where)->row()->summary_total;

        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }


    public function form()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }

        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }

    public function view()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }
        $data['buttons'] = array();
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . '/includes/record_view', $data);
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */