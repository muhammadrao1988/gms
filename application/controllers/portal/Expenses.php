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
                               expense_date
                            FROM
                              expenses 
                               WHERE branch_id = ' ".$this->branch_id."' ".$where;

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
            $DBdata['expense_date'] = date('Y-m-d',strtotime(getVar('expense_date')));
            $DBdata['created_date'] = date('Y-m-d H:i:s');
            $DBdata['created_by'] = $this->current_user;
            $DBdata['total_amount'] = array_sum(getVar('amount'));
            $DBdata['branch_id'] = $this->branch_id;
            $expense_details[] =   array_filter(getVar('label'));
            $expense_details[] =   array_filter(getVar('amount'));
            $DBdata['expense_entry'] = json_encode($expense_details);

            $id = save($this->table, $DBdata);
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
            $DBdata = $DbArray['dbdata'];
            $DBdata['expense_date'] = date('Y-m-d',strtotime(getVar('expense_date')));
            $DBdata['created_date'] = date('Y-m-d H:i:s');
            $DBdata['created_by'] = $this->current_user;
            $DBdata['total_amount'] = array_sum(getVar('amount'));
            $DBdata['branch_id'] = $this->branch_id;
            $expense_details[] =   array_filter(getVar('label'));
            $expense_details[] =   array_filter(getVar('amount'));
            $DBdata['expense_entry'] = json_encode($expense_details);
            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);
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