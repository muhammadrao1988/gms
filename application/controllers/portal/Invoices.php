<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_invoices $module
 * @property M_cpanel $m_cpanel
 */
class Invoices extends CI_Controller
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
        //$data['query'] = "Select * from ".$this->table." where 1".$where;
        $data['query'] = "SELECT 
                              ic.`id`,
                              ic.amount,
                              ic.acc_id,
                              ac.acc_name as member_name,
                              date(ic.fees_datetime) as paid_date,
                              MONTHNAME(ic.fees_month) as last_paid_month,
                              ic.`type` as invoice_for                              
                            FROM
                              invoices AS ic 
                               INNER JOIN accounts AS ac 
                                ON (ac.acc_id = ic.acc_id) WHERE 1 AND ic.branch_id = '".$this->branch_id."'
                           ".$where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }
    public function monthlyInvoice(){
        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        /*$data['query'] = "SELECT
                              id,
                              ac.acc_name AS member_name,
                              ac.acc_id,
                              MAX(ic.fees_month) AS fees_month,                              
                              ic.amount,
                              DATE(ic.fees_datetime) AS last_paid,
                              MONTHNAME(ic.fees_month) as last_paid_month,
                              IF(
                                (30 - DATEDIFF(
                                  CURRENT_DATE(),
                                  CONCAT(
                                    DATE_FORMAT(ic.`fees_month`, '%Y-%m-'),
                                    DAY(ac.acc_date)
                                  )
                                )) < 4 
                                AND it.id = 1,
                                CONCAT(
                                  IF(
                                    (30- DATEDIFF(CURRENT_DATE(), CONCAT(
                                    DATE_FORMAT(ic.`fees_month`, '%Y-%m-'),
                                    DAY(ac.acc_date)
                                  ))) > 0,
                                    CONCAT(
                                      (30 - DATEDIFF(CURRENT_DATE(), CONCAT(
                                    DATE_FORMAT(ic.`fees_month`, '%Y-%m-'),
                                    DAY(ac.acc_date)
                                  ))),
                                      ' Days Left '
                                    ),
                                    'Unpiad '
                                  ),
                                  '<button class=\"btn btn-primary btn-sm payment_pop\" type=\"button\" href=\"javascript:void(0);\" data-invoice=\"',
                                  ic.id,
                                  '\"><i class=\"fa fa-money\"></i> Pay</button>'
                                ),
                                '<!--<span class=\"green\"><b>Paid</b></span>-->'
                              ) AS payment_status,
                              it.name AS invoice_for
                            FROM
                              invoices AS ic 
                              INNER JOIN accounts AS ac 
                                ON (ic.`acc_id` = ac.`acc_id`) 
                              INNER JOIN invoice_types AS `it` 
                                ON (ic.`type` = `it`.`id`) 
                            WHERE 1 
                              AND ac.`status` = 1 and ic.type = 1 
                            GROUP BY ic.acc_id ".$where;*/
        $data['query'] = "SELECT 
                              MAX(iv.`id`) AS id,
                              iv.`acc_id`,
                              ac.`acc_name` as member_name,
                              ac.`acc_date` as registration_date,
                              iv.`amount` AS amount,
                              iv.`fees_month`,
                              DATE_FORMAT((select ivv.fees_month from invoices as ivv where ivv.id= MAX(iv.`id`)),'%M %Y') AS last_paid_month,
                              DATE((select ivv.fees_datetime from invoices as ivv where ivv.id = MAX(iv.`id`))) as paid_date,
                              MAX(iv.`fees_month`) as payment_status,
                              iv.status
                            FROM
                              invoices AS iv 
                              INNER JOIN accounts AS ac 
                                ON (ac.`acc_id` = iv.`acc_id`) 
                            WHERE 1 AND iv.branch_id = '".$this->branch_id."' 
                            AND ac.`status` = 1 
                            AND FIND_IN_SET('1', iv.`type`)
                            AND iv.branch_id = '".$this->branch_id."'
                            AND (SELECT DATE_ADD(CONCAT(YEAR(ivvv.fees_month),'-',MONTH(ivvv.fees_month),'-',DAY(ac.`acc_date`)),INTERVAL 30 DAY) AS month_interval FROM invoices AS ivvv WHERE ivvv.acc_id = ac.`acc_id` AND ivvv.`status` = 1) <= CURRENT_DATE() 
                            group by iv.acc_id " .$where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid_monthly', $data);
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
            $SQL2 = "SELECT * FROM accounts WHERE acc_id='" . $data['row']->acc_id . "'";
            $data['row2'] = $this->db->query($SQL2)->row();
        }
        $data['buttons'] = array();
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/view', $data);
    }


    public function add()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            $DBdata['fees_month'] = date('Y-m-d',strtotime(getVar('fees_month')));
            $DBdata['fees_datetime'] = date('Y-m-d H:i:s');
            $DBdata['amount'] = array_sum(getVar('amount'));
            if(in_array('1',getVar('type'))){
                $DBdata['status'] = 1;
            }
            $DBdata['type'] = implode(',',getVar('type'));
            if(in_array('1',getVar('type'))){
                $DBdata['status'] = 1;
            }
            $DBdata['branch_id'] = $this->branch_id;
            $amount_details[] =   array_filter(getVar('type'));
            $amount_details[] =   array_filter(getVar('amount'));
            $DBdata['amount_details'] = json_encode($amount_details);

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

            $DBdata['fees_month'] = date('Y-m-d',strtotime(getVar('fees_month')));
            $DBdata['fees_datetime'] = date('Y-m-d H:i:s');
            $DBdata['amount'] = array_sum(getVar('amount'));
            $DBdata['type'] = implode(',',getVar('type'));
            $amount_details[] =   array_filter(getVar('type'));
            $amount_details[] =   array_filter(getVar('amount'));
            $DBdata['amount_details'] = json_encode($amount_details);
            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');
        }
    }

    function status()
    {
        $JSON = array();
        $id = getVar('status-id');
        $login_status_val = getVal('users', 'status', 'WHERE user_id ="' . $id . '"');
        if($login_status_val==0 ||  $login_status_val==2 || $login_status_val==3 ){
            $status=1;
        }else if($login_status_val==1){
            $status=0;
        }else{
            $status=3;
        }

        $where = $this->id_field . "='" . $id . "' ";
        save($this->table, array('status' => $status), $where);
        $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Status has been changed...</div>';
        $redirct_url 		  =  '?msg=Status has been changed..' ;
        $JSON['redirect_url'] =  $redirct_url;
        echo json_encode($JSON);


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
    public function payPayment()
    {
        if (!$this->module->validate_payment()) {
            $this->session->set_flashdata('errors', show_validation_errors());
            redirect(ADMIN_DIR . $this->module_name . '/monthlyInvoice/?error=Fill all required fields.');
        } else {
            $DBdata['amount'] = getVar('fees');
            $DBdata['description'] = getVar('description');
            $DBdata['fees_month'] = date('Y-m-d', strtotime(getVar('fees_month')));
            $invoices_details = getValues('invoices', '*', ' where id = "' . getVar('invoice_id') . '"');
            $DBdata['acc_id'] = $invoices_details->acc_id;
            $DBdata['fees_datetime'] = date('Y-m-d H:i:s');
            $DBdata['type'] = 1;
            $DBdata['status'] = 1;
            $DBdata['branch_id'] = $this->branch_id;
            $amount_details[] = array((string)$DBdata['type']);
            $amount_details[] = array($DBdata['amount']);
            $DBdata['amount_details'] = json_encode($amount_details);
            $id = save($this->table, $DBdata);
            $id = save($this->table, array('status'=>2),' id = "'.getVar('invoice_id').'"');
            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/monthlyInvoice/?msg=Record has been inserted..');
        }

    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */