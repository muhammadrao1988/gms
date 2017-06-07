<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Banned_users
 * @property M_attendance $module
 * @property M_cpanel $m_cpanel
 */
class Attendance extends CI_Controller
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
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;

        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->iic_user_type = intval(get_option('iic_user_type'));
        $this->branch_id = getVal("users","branch_id"," WHERE user_id='".$this->session->userdata('user_info')->user_id."'");
        $this->machine_serial = getVal("users","machine_serial"," WHERE user_id='".$this->session->userdata('user_info')->user_id."'");
        date_default_timezone_set('Asia/Karachi');
        $this->is_machine = $this->session->userdata('user_info')->is_machine;
        $this->pk_date_time = date('d-m-Y H:i');
    }


    public function index()
    {

        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        $data['pk_date_time'] = $this->pk_date_time;

        $search = getVar('search');
        if($search['Name']!=''){
            $where = str_replace('Name','act.`Name`',$where);
        }
        $having_record = "";
        if($search['subscription_status']!=''){
            $where = str_replace("AND subscription_status LIKE '%continue%'","",$where);
            $where = str_replace("AND subscription_status LIKE '%expired%'","",$where);
            if($search['subscription_status']=="continue") {
                $having_record = " HAVING subscription_status > 0 ";
            }else if($search['subscription_status']=="expired") {
                $having_record = " HAVING subscription_status <= 0 ";
            }

        }
        if($search['account_id']!=''){
            $where = str_replace(" AND account_id = '".$search['account_id']."'"," AND ac.acc_id = '".$search['account_id']."'",$where);

        }
        $data['query'] = "SELECT 
                          att.id as id,
                          ac.acc_id,                        
                          ac.machine_member_id,                 
                          ac.acc_name ,
                          act.`Name` ,    
                          DATE_FORMAT( ac.`invoice_generate_date`, '%d') AS day_invoice,
                          IF(att.check_type='I','Checked In','Checked Out') as check_type,
                          att.`datetime`,
                          iv.status,
                          iv.id as invoices_id,
                          sub.`period` - FLOOR(DATEDIFF(CURDATE(), MAX(att_sub.`datetime`)))   AS subscription_status,
                          FLOOR(DATEDIFF(CURDATE(), MAX(iv.fees_month)) / 30) AS fees_month,
                           COUNT(DISTINCT(iv_due.id)) AS partial_paid                                                    

                        FROM
                          attendance AS att
                              INNER JOIN accounts AS ac 
                                ON (ac.`acc_id` = att.`acc_id` ) 
                              INNER JOIN acc_types AS act
                                ON  (act.`acc_type_ID` = ac.`acc_types`)
                              INNER JOIN subscriptions AS sub 
                                ON (sub.`id` = ac.`subscription_id`)
                              LEFT JOIN invoices as iv 
                                ON( iv.`acc_id` = ac.acc_id  AND FIND_IN_SET('1',iv.`type`) AND iv.`state` IN (1, 2)  )
                              LEFT JOIN invoices as iv_due 
                                ON( iv_due.`acc_id` = ac.acc_id  AND iv_due.`state` IN (2)  )
                              LEFT JOIN attendance AS att_sub
                                ON (ac.`machine_user_id` = att_sub.`account_id` AND ac.`serial_number` = att_sub.`machine_serial`)
                              where 1 and att.status = 1 
                               AND ac.branch_id='".$this->branch_id."'
                               AND att.machine_serial = '".$this->machine_serial."'
                              ".$where." GROUP by att.id".$having_record;
        /*echo $data['query'];
        die('Call');*/
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }

    public function take_attendance()
    {

        if(!$this->module->validate_id_validate()){
            $this->session->set_flashdata('errors', show_validation_errors());
            redirect(ADMIN_DIR . $this->module_name . '/');
        }else{
            $DbArray = getDbArray($this->table);
            $user_info  = $this->session->userdata('user_info');
            $DBdata = $DbArray['dbdata'];
            if(getVar('datetime')==''){
                $DBdata['datetime'] = $this->pk_date_time;
            }
            $DBdata['datetime'] = date('Y-m-d H:i:s',strtotime($DBdata['datetime']));
            $DBdata['datetime'] = date('Y-m-d H:i:s',strtotime($DBdata['datetime']));

            $DBdata['account_id'] = getVal('accounts','machine_user_id',' where acc_id = "'.getVar('acc_id').'"');
            $DBdata['machine_serial'] = $user_info->machine_serial;
            $DBdata['sensored_id'] = 1;
            $DBdata['status'] = 1;
            $DBdata['is_machine'] = $this->is_machine;
            $DBdata['acc_id'] = getVar('acc_id');
            if($this->is_machine!=1){
                $DBdata['account_id']="";
            }
            $id = save($this->table, $DBdata);

            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..');
        }
    }

    function account_exist($str){
        if ($str > 0)
        {
            //check machine member id
            if(getVal('accounts','acc_id',' where acc_id = "'.$str.'"') > 0) {
                //check attendance
                if($this->is_machine ==1){
                    if(getVal('attendance','id',' where account_id = "'.getVal('accounts','machine_user_id',' where acc_id = "'.$str.'"').'" and DATE(`datetime`) = "'.date('Y-m-d',strtotime($this->pk_date_time)).'" and check_type = "'.getVar('check_type').'"')>0){
                        $this->form_validation->set_message('account_exist', 'Attendance already has taken.');
                        return FALSE;
                    }
                }else{
                    if(getVal('attendance','id',' where 1 and acc_id = "'.$str.'" and DATE(`datetime`) = "'.date('Y-m-d',strtotime($this->pk_date_time)).'" and check_type = "'.getVar('check_type').'"')>0){
                        $this->form_validation->set_message('account_exist', 'Attendance already has taken.');
                        return FALSE;
                    }
                }
                return true;
            }
            $this->form_validation->set_message('account_exist', 'This member id is not exist.');
            return FALSE;
        }
        else
        {
            $this->form_validation->set_message('account_exist', 'Please insert member id');
            return FALSE;
        }

    }


    public function form()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
            $data['row']->user_type = strtolower(getVal('user_types', 'user_type', "WHERE id='" . $data['row']->u_type . "'"));
            if ($data['row']->u_type == RESELLER_TYPE_ID) {
                $data['row']->reseller_id = $data['row']->parent;
            }

        } else if (getVar('reseller_id') > 0) {
            $data['row'] = new stdClass();
            $data['row']->reseller_id = getVar('reseller_id');
            $data['row']->user_type = 'reseller';
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


    public function add()
    {
        $email_exists = getVal('accounts', 'email', 'WHERE `email`="' . getVar('email') . '"');
        if (!empty($email_exists)) {
            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            #for log
            $user_log_array = array(
                'date'			=> date('Y-m-d H:i:s'),
                'user_id'		=> sessionVar('user_id'),
                'type_id'		=> 15,
                'display_state'	=> 2,
                'ip_addr'		=> user_ip()
            );
            save('audit_log', $user_log_array, $where = '');
            #end
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            $id = save($this->table, $DBdata);

            /*------------------------------------------------------------------------------------------*/
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..');
        }
    }


    public function update()
    {
        $email_exists = getVal('accounts', 'email', 'WHERE `email`="' . getVar('email') . '" AND acc_id !="' . getVar('user_id') . '"');
        if ($email_exists != '') {

            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            /*-----------------------------------------$_POST-------------------------------------------------*/
            $_POST['u_type'] = getVal('user_types', 'id', "WHERE LOWER(user_type)='" . getVar('user_type') . "'");
            if (getVar('user_type') == 'customer') {
                $_POST['user_type'] 		= '7';
                $_POST['parent'] 			= getVar('reseller', TRUE, FALSE);
                $_POST['parent_child'] 		= getVar('company', TRUE, FALSE);

            } elseif (getVar('user_type') == 'reseller') {
                $_POST['user_type'] 		= '6';
                $_POST['parent_child'] 		= getVar('reseller', TRUE, FALSE);
                $reseller					= getVar('reseller', TRUE, FALSE);
                $_POST['parent'] 			= getVal('accounts', 'parent', "WHERE acc_id='" . $reseller . "'");
            } elseif (getVar('user_type') == 'administrator') {
                $_POST['user_type'] = '5';
                //$_POST['parent'] = '0';
            } elseif (getVar('user_type') == 'super admin') {
                $_POST['user_type'] = '1';
                //$_POST['parent'] = '0';
            }
            /*------------------------------------------------------------------------------------------*/

            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);
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
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */