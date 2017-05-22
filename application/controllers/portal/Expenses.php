<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_members $module
 * @property M_cpanel $m_cpanel
 */
class Expenses extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;
    var $branch_id;
    var $current_user;

    function __construct()
    {
        /* parent::__construct();
         $this->m_cpanel->checkLogin();


         $this->module_name = getUri(2);
         $this->module = 'm_' . $this->module_name;
         $this->load->model(ADMIN_DIR . $this->module);
         $this->module = $this->{$this->module};

         $this->table = $this->module->table;
         $this->id_field = $this->module->id_field;

         $this->module_title = $this->m_cpanel->moduleDetail()->module_title;
         $this->iic_user_type = intval(get_option('iic_user_type'));  */


        parent::__construct();
        $this->m_cpanel->checkLogin();

        //TODO:: Module Name
        $this->module_name = getUri(2);
        $this->module = 'm_' . $this->module_name;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;

        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->iic_user_type = intval(get_option('iic_user_type'));
        $this->branch_id = getVal("users","branch_id"," WHERE user_id='".$this->session->userdata('user_info')->user_id."'");
        $this->current_user = $this->session->userdata('user_info')->user_id;
    }


    public function index()
    {


        $where = '';
        $where .= getFindQuery();
        $search_array = getVar('search');
        $data['search'] = $search_array;
        if(getVar('date_range') && getVar('date_range2')){
            $date_range = date('Y-m-d 00:00:00',strtotime(getVar('date_range')));
            $date_range2 = date('Y-m-d 23:59:59',strtotime(getVar('date_range2')));

        }else{
            $date_range = date('Y-m-01 00:00:00');
            $date_range2 = date('Y-m-d 23:59:59');

        }
        $filter = " AND expense_date BETWEEN '".$date_range."' AND '".$date_range2."' ";

        $data['query'] = "SELECT 
                              id,
                              vendor_name,
                              total_amount,
                              CASE expense_status 
                               WHEN 1 THEN 'Paid'
                               WHEN 2 THEN 'Part Paid' 
                               WHEN 3 THEN 'UnPaid' 
                               ELSE 'N/A'
                               END as 'status',
                               
                               expense_date,
                               SUM(total_amount) as total_amount_summary
                            FROM
                              expenses 
                               WHERE branch_id = ' ".$this->branch_id."' ".$filter.$where. " GROUP BY id";

        $chart = str_replace("GROUP BY id","",$data['query']);
        $chart = $chart." GROUP BY DATE(`expense_date`)";



        $data['chart_total'] = $this->db->query($chart)->result();
        foreach ($data['chart_total'] as $ct) {


            $total_amount[] = ($ct->total_amount_summary=="" ? 0 : $ct->total_amount_summary);
            $report_days[] = date('dM y', strtotime($ct->expense_date));

        }
        $data['total_amount'] = $total_amount;
        $data['report_days'] = $report_days;
        $data['summary_total'] = array_sum($total_amount);

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
        /*$data['buttons'] = array('add');*/
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . '/includes/record_view', $data);
    }


    public function add()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            $expense_date = date('Y-m-d H:i:s',strtotime(getVar('expense_date')));
            $total_amount = array_sum(getVar('amount'));
            $DBdata['expense_date'] = $expense_date;
            $DBdata['created_date'] = date('Y-m-d H:i:s');
            $DBdata['created_by'] = $this->current_user;
            $DBdata['total_amount'] = $total_amount;
            $DBdata['branch_id'] = $this->branch_id;
            $expense_details[] =   array_filter(getVar('label'));
            $expense_details[] =   array_filter(getVar('amount'));
            $DBdata['expense_entry'] = json_encode($expense_details);

            $id = save($this->table, $DBdata);
            //audit log entry
            if($DBdata['expense_status']==1){
                $status_expense = "Paid";
                $type_id = 5;
            }else if($DBdata['expense_status']==2){
                $status_expense = "Part Paid";
                $type_id = 6;
            }else if($DBdata['expense_status']==3){
                $status_expense = "UnPaid";
                $type_id = 7;
            }

            $note = $this->current_user." generated expense invoice with the status of ".$status_expense;

            $audit_log = array(
                            'date'=> $expense_date,
                            'user_id'=> $this->current_user,
                            'type_id'=> $type_id,
                            'note'=> $note,
                            'amount'=> $total_amount,
                            'reference_id'=> $id,
                            'reference_table'=> $this->table,
                            'ip_addr'=> $this->input->ip_address(),
                            'branch_id'=> $this->branch_id
                            );

            save("audit_log",$audit_log);
            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..');
        }
    }


    public function update()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $expense_date = date('Y-m-d H:i:s',strtotime(getVar('expense_date')));
            $total_amount = array_sum(getVar('amount'));
            $DBdata = $DbArray['dbdata'];
            $DBdata['expense_date'] = $expense_date;
            $DBdata['created_date'] = date('Y-m-d H:i:s');
            $DBdata['created_by'] = $this->current_user;
            $DBdata['total_amount'] = $total_amount;
            $DBdata['branch_id'] = $this->branch_id;
            $expense_details[] =   array_filter(getVar('label'));
            $expense_details[] =   array_filter(getVar('amount'));
            $DBdata['expense_entry'] = json_encode($expense_details);
            $where = $DbArray['where'];

            save($this->table, $DBdata, $where);

            if($DBdata['expense_status']==1){
                $status_expense = "Paid";
                $type_id = 5;
            }else if($DBdata['expense_status']==2){
                $status_expense = "Part Paid";
                $type_id = 6;
            }else if($DBdata['expense_status']==3){
                $status_expense = "UnPaid";
                $type_id = 7;
            }

            if(getVar('old_status')==1){
                $old_type_id = 5;
            }else if(getVar('old_status')==2){
                $old_type_id = 6;
            }else if(getVar('old_status')==3){
                $old_type_id = 7;
            }

            $note = $this->current_user." generated expense invoice with the status of ".$status_expense;

            $audit_log = array(
                'date'=> $expense_date,
                'user_id'=> $this->current_user,
                'type_id'=> $type_id,
                'note'=> $note,
                'amount'=> $total_amount,
                'reference_id'=> getVar('id'),
                'reference_table'=> $this->table,
                'ip_addr'=> $this->input->ip_address(),
                'branch_id'=> $this->branch_id
            );
             $audit_log_where = " `reference_id` = '".getVar('id')."' AND `type_id` = '".$old_type_id."'";
            save("audit_log",$audit_log,$audit_log_where);

            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');
        }
    }



    public function delete()
    {
        $JSON = array();
        if(getVar('action')==""){
            $id = getVar('del-id');
        }else{
            $id = getVar('del-all');
        }
        $SQL = "DELETE FROM " . $this->table . " WHERE `" . $this->id_field . "` IN(" . $id . ")";
        $this->db->query($SQL);
        $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Record has been deleted..</div>';
        $redirct_url 		  =  '?msg=Record has been deleted..' ;
        $JSON['redirect_url'] =  $redirct_url;
        echo json_encode($JSON);
    }


}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */