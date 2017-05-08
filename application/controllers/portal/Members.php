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
        $data['query'] = "SELECT 
                              acc.acc_id,
                              acc.acc_name,
                              acc.acc_tel,
                              acc.email,
                              sub.`name`,
                              br.`branch_name`,  
                              acc.acc_date 
                            FROM
                              accounts AS acc 
                              INNER JOIN branches AS br 
                                ON (br.`id` = acc.`branch_id`)
                                INNER JOIN subscriptions AS sub 
                                ON (sub.`id` = acc.`subscriptin_id`)
                               WHERE acc.`status` = 1";


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
        $data['buttons'] = array();
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . '/includes/record_view', $data);
    }


    public function add()
    {
        $email_exists = getVal('users', 'email', 'WHERE `email`="' . getVar('email') . '"');
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
        $email_exists = getVal('users', 'email', 'WHERE `email`="' . getVar('email') . '" AND user_id !="' . getVar('user_id') . '"');
        if ($email_exists != '') {
            $errors[] = $_POST['error'] = 'E-Mail address already registered.';
        }
        if (!$this->module->validate() || count($errors) > 0) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {

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

}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */