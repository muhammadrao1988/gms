<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_members $module
 * @property M_cpanel $m_cpanel
 */
class Members extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;

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

    }


    public function index()
    {
        $where = '';
        $where .= getFindQuery();

        $data['title'] = $this->module_title;
        /*$data['query'] = "SELECT
          users.user_id,
          users.username,
          users.email,
          CONCAT(users.first_name,' ', users.surname) as full_name,
          ac_r.acc_name AS reseller,
          ac_c.acc_name AS company,
          ac_s.short_desc AS user_login_status
        FROM
          users
          LEFT JOIN accounts ac_c
            ON (users.parent = ac_c.acc_id AND ac_c.acc_type IN (" . COMPANY_IDS . ") )
          LEFT JOIN accounts ac_r
            ON ( users.parent = ac_c.acc_id AND ac_r.acc_type IN (" . RESELLER_IDS . ") AND ac_c.parent = ac_r.acc_id )
          LEFT JOIN account_status ac_s
            ON (users.login_status = ac_s.id)
        WHERE 1 ". $where;*/

        $search = getVar('search');
        $having_record = "";
        if($search['subscription_status']!=''){
            $where = str_replace("AND subscription_status LIKE '%continue%'","",$where);
            $where = str_replace("AND subscription_status LIKE '%expired%'","",$where);
            $where = str_replace("AND subscription_status LIKE '%no_attendance%'","",$where);
            if($search['subscription_status']=="continue") {
                $having_record = " HAVING subscription_status > 0 ";
            }else if($search['subscription_status']=="expired") {
                $having_record = " HAVING subscription_status <= 0 ";
            }


        }
            /*22-05-2017 According to subscription period*/
            /*$data['query'] = "SELECT
                                  acc.acc_id,
                                  acc.machine_member_id as machine_id,
                                  acc.acc_name,
                                  acc.acc_tel,
                                  acc.email,
                                  br.`branch_name`,
                                  acc.acc_date,
                                  acc.serial_number as machine_serial,
                                  sub.`name`,
                                  IF(DATE(DATE_ADD(acc.acc_date, INTERVAL sub.`period` DAY))<=CURRENT_DATE(),'<span class=\"red\">Expired</span>',CONCAT('<span class=\"green\">',
                                    DATEDIFF(DATE(DATE_ADD(acc.acc_date, INTERVAL sub.`period` DAY)),CURRENT_DATE())-1,' Days left </span>')) AS subscription_status,
                                   iv.`fees_month` as monthly_status,
                                  iv.status
                                FROM
                                  accounts AS acc
                                  INNER JOIN branches AS br
                                    ON (br.`id` = acc.`branch_id`)
                                    INNER JOIN subscriptions AS sub
                                    ON (sub.`id` = acc.`subscription_id`)
                                    LEFT JOIN invoices as iv ON( iv.`acc_id` = acc.acc_id )
                                   WHERE acc.`status` = 1 GROUP BY acc.acc_id ".$branch_id." ".$where;*/
            /*22-05-2017 Expire according to last attendance date.*/
//echo "<a href=\"\""
//<a href=""
        $data['query'] = "SELECT 
                              acc.acc_id,
                              IF(acc.machine_member_id > 0 , acc.machine_member_id ,
                           
                              CONCAT('<a  style=\"font-weight:bold;color:red\" href=\"".site_url(ADMIN_DIR.'members/form/\',acc.acc_id,\'')."\">Create Machine Id</a>')
                              ) AS machine_member_id,
                              acc.acc_name,
                              acc.acc_tel,                          
                              br.`branch_name`,  
                              DATE(acc.acc_date) as acc_date,
                              DATE_FORMAT( acc.`invoice_generate_date`, '%d') AS day_invoice,
                              acc.serial_number as machine_serial,
                              sub.`name`,
                             
                             sub.`period` - FLOOR(DATEDIFF(CURDATE(), MAX(att.`datetime`)))   AS subscription_status,
                              FLOOR(DATEDIFF(CURDATE(), MAX(iv.fees_month)) / 30) AS fees_month,
                              iv.status
                            FROM
                              accounts AS acc 
                              INNER JOIN branches AS br 
                                ON (br.`id` = acc.`branch_id`)
                                INNER JOIN subscriptions AS sub 
                                ON (sub.`id` = acc.`subscription_id`)
                                LEFT JOIN invoices as iv ON( iv.`acc_id` = acc.acc_id  AND FIND_IN_SET(iv.`type`, '1') AND iv.`state` IN (1, 2)  )  
                                LEFT JOIN attendance att ON (acc.`machine_user_id` = att.`account_id` AND acc.`serial_number` = att.`machine_serial`) 
                               WHERE acc.`status` = 1 
                               AND acc.branch_id='".$this->branch_id."'
                               ".$where."  group by acc.acc_id".$having_record ;


        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }


    public function form()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
            $data['row']->user_type = strtolower(getVal('user_types', 'user_type', "WHERE id='" . $data['row']->u_type . "'"));
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
        /*$email_exists = getVal('accounts', 'email', 'WHERE `email`="' . getVar('email') . '"');
        if (!empty($email_exists)) {
            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }*/
        /*if (!$this->module->validate() || count($errors) > 0) {*/
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            #for log
            $user_log_array = array(
                'date'			=> date('Y-m-d H:i:s'),
                'user_id'		=> $this->session->userdata('user_info')->acc_id,
                'type_id'		=> 15,
                'display_state'	=> 2,
                'ip_addr'		=> user_ip()
            );
            //save('audit_log', $user_log_array, $where = '');
            #end

            $DbArray = getDbArray($this->table);
            $user_info = $this->session->userdata('user_info');

            $DBdata = $DbArray['dbdata'];
            $DBdata['date_of_birth']    = date('Y-m-d',strtotime(getVar('date_of_birth')));
            $DBdata['acc_date'] = $DBdata['invoice_generate_date']   = date('Y-m-d H:i:s',strtotime(getVar('acc_date')));
            $DBdata['acc_manager']      = $this->session->userdata('user_info')->user_id;

            //$DBdata['machine_user_id']  = $this->module->getMachineUserId(getVar('machine_member_id'));
            /*$DBdata['branch_id']        = getVal('accounts','branch_id',' where acc_id = "'.$this->session->userdata('user_info')->acc_id.'"');
            $DBdata['serial_number']    = getVal('accounts','serial_number',' where acc_id = "'.$this->session->userdata('user_info')->acc_id.'"');*/
            $DBdata['branch_id']        = $user_info->branch_id;
            $DBdata['serial_number']    = $user_info->machine_serial;
            $DBdata['acc_manager']      = $user_info->user_id;

            $id = save($this->table, $DBdata);
            $this->module->getMachineUserId(getVar('machine_member_id'),base_url(ADMIN_DIR . 'invoices/form/?tempID='.$id.'&msg=Member has been created. Please generate first inovice.'));
            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . 'invoices/form/?msg=Member has been created. Please generate first inovice.');
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
            $DBdata['date_of_birth']    = date('Y-m-d',strtotime(getVar('date_of_birth')));
            $DBdata['acc_manager']      = $this->session->userdata('user_info')->user_id;
            $DBdata['acc_date']     = date('Y-m-d H:i:s',strtotime(getVar('acc_date')));
            //$DBdata['machine_user_id']  = $this->module->getMachineUserId($_REQUEST['machine_member_id']);;
            $DBdata['branch_id']        = getVal('users','branch_id',' where user_id = "'.$this->session->userdata('user_info')->user_id.'"');
            $DBdata['serial_number']    = getVal('users','machine_serial',' where user_id = "'.$this->session->userdata('user_info')->user_id.'"');

            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);
            $this->module->getMachineUserId($_REQUEST['machine_member_id'],base_url(ADMIN_DIR . $this->module_name.'/?msg=Member has been updated.'));
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');

        }
    }

    function status()
    {
        $JSON = array();
        $id = getVar('status-id');
        $login_status_val = getVal('accounts', 'status', 'WHERE acc_id ="' . $id . '"');
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

    function ajax()
    {
        $action = getUri(4);
        //$JSON['data'] = '';
        switch ($action) {
            case 'getSubscriptionCharges':
                if(getVar('id')!='') {
                    echo getVal('subscriptions', 'charges', ' where id = "' . getVar('id') . '"');
                }
                break;
        }

        //echo json_encode($JSON);
    }
    public function insertuserid(){
        save('accounts',array('machine_user_id'=>getVar('userID')),' machine_member_id="'.getVar('member_id').'"');
    }

    public function invoice(){


        $this->load->view(ADMIN_DIR . $this->module_name . '/aa', $data);
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */