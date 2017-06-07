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
        $this->is_machine = $this->session->userdata('user_info')->is_machine;
        $this->iic_user_type = intval(get_option('iic_user_type'));
    }
    public function index()
    {
        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        //$data['query'] = "Select * from ".$this->table." where 1".$where;
        $search = getVar('search');
        if($search['ic:type']!=""){
            $where = str_replace("AND ic.type = '".$search['ic:type']."'"," AND FIND_IN_SET('".$search['ic:type']."', ic.`type`)",$where);
        }
        if($search['ic:fees_month']!=""){

             $fees_month = date('Ym',strtotime($search['ic:fees_month']));


            $where = str_replace(
                "AND ic.fees_month LIKE '%".$search['ic:fees_month']."%'",
                " AND EXTRACT( YEAR_MONTH FROM `fees_month` ) = '".$fees_month."'",
                $where);

        }

        //AND FIND_IN_SET('1', iv.`type`)

        $data['query'] = "SELECT                               
                              ic.`id`,
                              ic.acc_id,
                               ic.machine_member_id,
                               ac.acc_name ,
                               CASE ic.state
                            WHEN 1 THEN '<span style=\"color:green;font-weight:bold\">PAID</span>'
                            WHEN 2 THEN '<span style=\"color:red;font-weight:bold\">PARTIAL PAID</span>'
                            WHEN 3 THEN '<span style=\"color:yellow;font-weight:bold\">CANCELLED</span>'
                           END AS state,
                              ic.amount,
                              
                              date(ic.fees_datetime) as fees_datetime,
                              
                              ic.`type`                            
                            FROM
                              invoices AS ic 
                               INNER JOIN accounts AS ac 
                                ON (ac.acc_id = ic.acc_id) WHERE 1 AND ic.branch_id = '".$this->branch_id."' ".(($this->is_machine==1)?" AND ac.machine_member_id !='' ":'')." 
                           ".$where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }
    public function monthlyInvoice(){
        $where = '';
        $where .= getFindQuery();
        $data['title'] = $this->module_title;
        $data['query'] = "SELECT 
                          acc.`machine_member_id`,
                          inv.`acc_id`,
                          acc.`acc_name`,
                          acc.`acc_date`,
                          
                          FLOOR(
                            DATEDIFF(CURDATE(), MAX(inv.fees_month)) / 30
                          ) * a_type.`monthly_charges` AS Amount,
                          MAX(inv.`fees_month`) AS last_paid,
                          DATE_FORMAT(MAX(inv.`fees_month`), '%M %Y') AS last_paid_month,
                          FLOOR(
                            DATEDIFF(CURDATE(), MAX(inv.fees_month)) / 30
                          ) AS fees_month,
                          DATE_FORMAT( acc.`invoice_generate_date`, '%d') AS day_invoice,
                          
                          inv.`id` 
                        FROM
                          invoices inv 
                          JOIN accounts acc 
                            ON (inv.`acc_id` = acc.`acc_id`) 
                          JOIN acc_types a_type 
                            ON (
                              a_type.`acc_type_ID` = acc.`acc_types`
                            ) 
                        WHERE acc.branch_id =  '".$this->branch_id."' 
                          ".(($this->is_machine==1)?" AND acc.machine_member_id !='' ":'')." 
                          AND FIND_IN_SET( '1',inv.`type`) 
                          AND inv.`state` IN (1, 2) 
                          ".$where."
                        GROUP BY inv.acc_id 
                        HAVING last_paid < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";

        $this->load->view(ADMIN_DIR . $this->module_name . '/grid_monthly', $data);
    }

    public function form()
    {
        $id = intval(getUri(4));
        $tempId = getVar('tempID');
        $firstInvoice = getVar('firstInvoice');
        if($tempId && $tempId > 0){

            $branch_id = getVal("accounts","branch_id","WHERE acc_id='".$tempId."'");
            if($branch_id==$this->branch_id){
                $data['tempID'] = $tempId;
                 $SQL = "SELECT * FROM accounts WHERE  acc_id='" . $tempId . "'";
                $data['tempRow'] = $this->db->query($SQL)->row();


            }else{
                $data['tempID'] = 0;
            }
        }else{
            $data['tempID'] = 0;
        }

        if($firstInvoice && $firstInvoice ==1){
            $data['firstInvoice'] = 1;
        }else{
            $data['firstInvoice'] = 0;
        }

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "' AND branch_id='".$this->branch_id."'";
            $data['row'] = $this->db->query($SQL)->row();
            if($data['row']->id==""){
                redirect(ADMIN_DIR . $this->module_name . '/?error=Invalid access');
            }
    }

        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }

    public function view()
    {
        if(getUri(4)!=''){
            $id = intval(getUri(4));
        }elseif(getVar('id') !=''){
            $id = getVar('id');
        }else{
            $this->load->view(ADMIN_DIR . $this->module_name . '?error=Invoice id not found.');
        }

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . " IN (" . $id . ") AND branch_id = '".$this->branch_id."'";
            $data['rows'] = $this->db->query($SQL)->result();
            if($data['rows'][0]->id==""){
                redirect(ADMIN_DIR . $this->module_name . '/?error=Invalid access');
            }
            $SQL2 = "SELECT * FROM accounts WHERE acc_id='" . $data['rows'][0]->acc_id . "'";
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
        }
        else {

            $acc_id = getVar('acc_id');
            $machine_member_id = getVal('accounts','machine_member_id'," WHERE acc_id='".$acc_id."'");
            $branch_id = getVal('accounts','branch_id'," WHERE acc_id='".$acc_id."'");
            $is_first_invoice = getVar('firstInvoice');

            if($is_first_invoice==1){
                $is_register_old = getVar('is_register_old');//if 0 user created today and invoice is current month else user created today but register date before
                $fees_month = getVar('fees_month');
                $fees_per_month = getVar('fees_per_month');
                $discount = getVar('discount');
                $discount = ($discount=="" ? 0 : $discount);
                $type = getVar('type');
                $amount = getVar('amount');
                $total_month = count($fees_per_month);
                $invoice_option = getVar('invoice_option');//1=previous month invoices will add. 2 = Current month invoice generate

                if($invoice_option==2){

                    $from_month = date('Y-m-d');
                    $currentMonth = date('F');
                    $currentDay = date('d');
                    $next_month = date('Y-m',strtotime($currentMonth.' next month'));
                    $next_month_name = date('M',strtotime($currentMonth.' next month'));

                    if (strpos($next_month_name, 'Feb') !== false && $currentDay > 28) {
                        $register_day_to = 28;
                    } else if ($currentDay > 30 && (strpos($next_month_name, 'Apr') !== false OR
                            strpos($next_month_name, 'Jun') !== false OR
                            strpos($next_month_name, 'Sep') !== false OR
                            strpos($next_month_name, 'Nov') !== false
                        )
                    ) {
                        $register_day_to = 30;
                    } else {
                        $register_day_to = $currentDay;
                    }

                    $to_month = $next_month."-".$register_day_to;
                    $last_fees = $from_month ;
                }

                $is_single = 1;

                $firstIndexFessMonth = explode("|",$fees_month[0]);


                if( $invoice_option==1 OR $invoice_option==""){
                    $lastIndexFeesMonth = explode("|",end($fees_month));
                    $from_month = $lastIndexFeesMonth[0];
                    $to_month = $firstIndexFessMonth[1];
                    $is_single = 0;
                    $last_fees = $firstIndexFessMonth[0];
                }
                $fee_invoice_template_array = array();
                $i=0;
                $calculate_amount = 0;
                if($is_single ==1){
                    $fee_invoice_template_array[$i]['type'] = 1;
                    $fee_invoice_template_array[$i]['amount'] = $fees_per_month;
                    $fee_invoice_template_array[$i]['duration'] = $fees_month[0];
                    $calculate_amount = $calculate_amount + $fees_per_month;
                    $i++;
                }else{

                    foreach ($fees_month as $fee){
                        $fee_invoice_template_array[$i]['type'] = 1;
                        $fee_invoice_template_array[$i]['amount'] = $fees_per_month;
                        $fee_invoice_template_array[$i]['duration'] = $fee;
                        $calculate_amount = $calculate_amount + $fees_per_month;
                        $i++;

                    }
                }
                if(isset($type) && count($type) > 0){
                    $other_invoice_template_array = array();
                    $j = 0;
                    foreach ($type as $tp){
                        $other_invoice_template_array[$j]['type'] = $tp;
                        $other_invoice_template_array[$j]['amount'] = $amount[$j];
                        $other_invoice_template_array[$j]['duration'] = date('Y-m-d');
                        $calculate_amount = $calculate_amount + $amount[$j];
                       $j++;


                    }

                }
                $total_array =  array();
                $subtotal = $calculate_amount;
                $total = $calculate_amount - $discount;
                $state = getVar('state');//1=paid 2=partial paid
                $received_amount = $calculate_amount - $discount;
                if($state==2){
                    $received_amount = getVar('received_amount');
                }
                $total_array['subtotal'] = $subtotal;
                $total_array['discount'] = $discount;
                $total_array['received_amount'] = $received_amount;
                $total_array['total'] = $total;
                $total_array['remaining_amount'] = $total - $received_amount;
                $type = array_unique($type);
                $type[] = 1;

                $account_details = array('fee_invoice'=>$fee_invoice_template_array,'other_invoice'=>$other_invoice_template_array,'total'=>$total_array);

                //if user cancel all previous invoice and start from today
                if($invoice_option==2){

                    $this->db->query("UPDATE accounts SET invoice_generate_date='".date('Y-m-d H:i:s')."' WHERE acc_id='".$acc_id."'");
                    $last_fees = date('Y-m-d');
                    $cancel_invoice_template =  array();
                    $k=0;
                    $calculate_amount_cancel = 0;


                    foreach ($fees_month as $fee){

                        $cancel_invoice_template[$k]['type'] = 1;
                        $cancel_invoice_template[$k]['amount'] = $fees_per_month;
                        $cancel_invoice_template[$k]['duration'] = $fee;
                        $calculate_amount_cancel = $calculate_amount_cancel + $fees_per_month;
                        $k++;

                    }

                    $firstIndexFessMonthCancel = explode("|",$fees_month[0]);
                    $lastIndexFessMonthCancel = explode("|",end($fees_month));
                    $from_month_cancel = $firstIndexFessMonthCancel[0];
                    $to_month_cancel = $lastIndexFessMonthCancel[1];

                    $total_array_cancel = array();
                    $total_array_cancel['subtotal'] = $calculate_amount_cancel;
                    $total_array_cancel['discount'] = 0;
                    $total_array_cancel['received_amount'] = 0;
                    $total_array_cancel['total'] = $calculate_amount_cancel;
                    $total_array_cancel['remaining_amount'] = $calculate_amount_cancel;

                    $account_details_cancel = array("fee_invoice"=>$cancel_invoice_template,'total'=>$total_array_cancel);

                    $cancel_invoice_date = array();
                    $cancel_invoice_date['acc_id'] = $acc_id;
                    $cancel_invoice_date['machine_member_id'] = $machine_member_id;
                    $cancel_invoice_date['state'] = 3;
                    $cancel_invoice_date['subtotal'] = $calculate_amount_cancel;
                    $cancel_invoice_date['discount'] = 0;
                    $cancel_invoice_date['received_amount'] = 0;
                    $cancel_invoice_date['remaining_amount'] = $calculate_amount_cancel ;
                    $cancel_invoice_date['amount'] = $calculate_amount_cancel;
                    $cancel_invoice_date['description'] = "Cancel all previous invoices during first invoice creation";
                    $cancel_invoice_date['fees_datetime'] = date('Y-m-d H:i:s');
                    $cancel_invoice_date['fees_month'] = date('Y-m-d');
                    $cancel_invoice_date['type'] = 1;
                    $cancel_invoice_date['amount_details'] = json_encode($account_details_cancel);
                    $cancel_invoice_date['status'] = 3;
                    $cancel_invoice_date['branch_id'] = $branch_id;
                    $cancel_invoice_date['created_by'] = $this->session->userdata('user_info')->user_id;
                    $cancel_invoice_date['from_date'] = $from_month_cancel;
                    $cancel_invoice_date['to_date'] = $to_month_cancel;
                    save("invoices",$cancel_invoice_date);




                }


               $invoice_table = array();
               $invoice_table['acc_id'] = $acc_id;
               $invoice_table['machine_member_id'] = $machine_member_id;
               $invoice_table['state'] = $state;
               $invoice_table['subtotal'] = $subtotal;
               $invoice_table['discount'] = $discount;
               $invoice_table['received_amount'] = $received_amount;
               $invoice_table['remaining_amount'] = $total - $received_amount;
               $invoice_table['amount'] = $total;
               $invoice_table['description'] = getVar('description');
               $invoice_table['fees_datetime'] = date('Y-m-d H:i:s');
               $invoice_table['fees_month'] = $last_fees;
               $invoice_table['type'] = implode(",",$type);
               $invoice_table['amount_details'] = json_encode($account_details);
               $invoice_table['status'] = 2;
               $invoice_table['branch_id'] = $branch_id;
               $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
               $invoice_table['from_date'] = $from_month;
               $invoice_table['to_date'] = $to_month;
                $invoice_table['is_first_invoice'] = 1;
                $invoice_table['first_invoice_option'] = $invoice_option;

               $redirect_id = save("invoices",$invoice_table);




            }else{

                $discount = getVar('discount');
                $discount = ($discount=="" ? 0 : $discount);
                $type = getVar('type');
                $amount = getVar('amount');

                $invoice_template_array = array();
                $i=0;
                $calculate_amount = 0;

                if(isset($type) && count($type) > 0){
                    $j = 0;
                    foreach ($type as $tp){
                        $invoice_template_array[$i]['type'] = $tp;
                        $invoice_template_array[$i]['amount'] = $amount[$j];
                        $invoice_template_array[$i]['duration'] = date('Y-m-d');
                        $calculate_amount = $calculate_amount + $amount[$j];
                        $j++;
                        $i++;

                    }
                    $subtotal = $calculate_amount;
                    $total = $calculate_amount - $discount;
                    $state = getVar('state');//1=paid 2=partial paid
                    $received_amount = $calculate_amount - $discount;
                    if($state==2){
                        $received_amount = getVar('received_amount');
                    }
                    $total_array =  array();
                    $total_array['subtotal'] = $subtotal;
                    $total_array['discount'] = $discount;
                    $total_array['received_amount'] = $received_amount;
                    $total_array['total'] = $total;
                    $total_array['remaining_amount'] = $total - $received_amount;

                    $account_details = array('other_invoice'=>$invoice_template_array,'total'=>$total_array);

                    $type = array_unique($type);

                    $invoice_table = array();
                    $invoice_table['acc_id'] = $acc_id;
                    $invoice_table['machine_member_id'] = $machine_member_id;
                    $invoice_table['state'] = $state;
                    $invoice_table['subtotal'] = $subtotal;
                    $invoice_table['discount'] = $discount;
                    $invoice_table['fees_month'] = date('Y-m-d');
                    $invoice_table['received_amount'] = $received_amount;
                    $invoice_table['remaining_amount'] = $total - $received_amount;
                    $invoice_table['amount'] = $total;
                    $invoice_table['description'] = getVar('description');
                    $invoice_table['fees_datetime'] = date('Y-m-d H:i:s');
                    $invoice_table['amount_details'] = implode(",",$type);
                    $invoice_table['type'] = implode(",",$type);
                    $invoice_table['amount_details'] = json_encode($account_details);
                    $invoice_table['status'] = 2;
                    $invoice_table['branch_id'] = $branch_id;
                    $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                    $redirect_id = save("invoices",$invoice_table);



                }

            }
            redirect(ADMIN_DIR . $this->module_name . '/view?id='.$redirect_id);



        }
    }


    public function update()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        }  else {


            $acc_id = getVar('acc_id');
            $machine_member_id = getVal('accounts','machine_member_id'," WHERE acc_id='".$acc_id."'");
            $branch_id = getVal('accounts','branch_id'," WHERE acc_id='".$acc_id."'");
            $invoice_id = getVar("id");
            $amount_details = getVal("invoices","amount_details"," WHERE id='".$invoice_id."'");
            $amount_details = json_decode($amount_details);


            if($branch_id==$this->branch_id && count($amount_details) > 0 && $invoice_id){
                $account_details = array();
                $fees_month = $amount_details->fee_invoice;
                $fee_invoice_template_array = array();
                $type = getVar('type');
                $amount = getVar('amount');
                $discount = getVar('discount');
                $discount = ($discount=="" ? 0 : $discount);
                $calculate_amount = 0;
                $i=0;
                $type_fee = "";
                foreach ($fees_month as $fee){
                    $fee_invoice_template_array[$i]['type'] = 1;
                    $fee_invoice_template_array[$i]['amount'] = $fee->amount;
                    $fee_invoice_template_array[$i]['duration'] = $fee->duration;
                    $calculate_amount = $calculate_amount + $fee->amount;
                    $type_fee = ",1";
                    $i++;
                    $account_details['fee_invoice'] = $fee_invoice_template_array;

                }
                $j = 0;
                foreach ($type as $tp){
                    $invoice_template_array[$i]['type'] = $tp;
                    $invoice_template_array[$i]['amount'] = $amount[$j];
                    $invoice_template_array[$i]['duration'] = date('Y-m-d');
                    $calculate_amount = $calculate_amount + $amount[$j];
                    $j++;
                    $i++;

                }
                $total_array =  array();
                $subtotal = $calculate_amount;
                $total = $calculate_amount - $discount;
                $state = getVar('state');//1=paid 2=partial paid
                $received_amount = $calculate_amount - $discount;
                if($state==2){
                    $received_amount = getVar('received_amount');
                }
                $total_array['subtotal'] = $subtotal;
                $total_array['discount'] = $discount;
                $total_array['received_amount'] = $received_amount;
                $total_array['total'] = $total;
                $total_array['remaining_amount'] = $total - $received_amount;
                $type = array_unique($type);
                if($type_fee!="") {
                    $type[] = 1;
                }
                $account_details['other_invoice'] = $invoice_template_array;
                $account_details['total'] = $total_array;
                $invoice_table = array();
                $invoice_table['acc_id'] = $acc_id;
                $invoice_table['machine_member_id'] = $machine_member_id;
                $invoice_table['state'] = $state;
                $invoice_table['subtotal'] = $subtotal;
                $invoice_table['discount'] = $discount;
                $invoice_table['received_amount'] = $received_amount;
                $invoice_table['remaining_amount'] = $total - $received_amount;
                $invoice_table['amount'] = $total;
                $invoice_table['description'] = getVar('description');
                $invoice_table['amount_details'] = implode(",",$type);
                $invoice_table['type'] = implode(",",$type);
                $invoice_table['amount_details'] = json_encode($account_details);
                $invoice_table['status'] = 2;
                $invoice_table['branch_id'] = $branch_id;
                $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;

                $where = "id='".$invoice_id."'";

                $redirect_id = save("invoices",$invoice_table,$where);
                redirect(ADMIN_DIR . $this->module_name . '/view?id='.$invoice_id);
            }else{
                redirect(ADMIN_DIR . $this->module_name . '?error=Invalid record');
            }

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
        $acc_id = getVar('acc_id');
        $per_month_fee = $fees_per_month = getVar('per_month_fee');
        $discount = getVar('discount');
        $discount = ($discount=="" ? 0 : $discount );
        $fees_month = getVar('fees_month');
        $total_invoice = count($fees_month);
        $invoice_option = getVar('invoice_option');//1=previous month invoices will add. 2 = Current month invoice generate
        $JSON = array();
        if($acc_id) {
            $machine_member_id = getVal('accounts','machine_member_id'," WHERE acc_id='".$acc_id."'");
            $branch_id = getVal('accounts','branch_id'," WHERE acc_id='".$acc_id."'");
            //$invoice_generate_date = date('d',strtotime($invoice_generate_date));
            if (($total_invoice > 0 && $fees_month) OR $invoice_option==2) {

                if($invoice_option==2){
                    $fees_month = getVar('fees_month_hidden');
                    $from_month = date('Y-m-d');
                    $currentMonth = date('F');
                    $currentDay = date('d');
                    $next_month = date('Y-m',strtotime($currentMonth.' next month'));
                    $next_month_name = date('M',strtotime($currentMonth.' next month'));

                    if (strpos($next_month_name, 'Feb') !== false && $currentDay > 28) {
                        $register_day_to = 28;
                    } else if ($currentDay > 30 && (strpos($next_month_name, 'Apr') !== false OR
                            strpos($next_month_name, 'Jun') !== false OR
                            strpos($next_month_name, 'Sep') !== false OR
                            strpos($next_month_name, 'Nov') !== false
                        )
                    ) {
                        $register_day_to = 30;
                    } else {
                        $register_day_to = $currentDay;
                    }

                    $to_month = $next_month."-".$register_day_to;
                    $last_fees = $from_month ;
                }

                $firstIndexFessMonth = explode("|",$fees_month[0]);


                $is_single = 1;
                if( $invoice_option==1 OR $invoice_option==""){
                    $lastIndexFeesMonth = explode("|",end($fees_month));
                    $from_month = $lastIndexFeesMonth[0];
                    $to_month = $firstIndexFessMonth[1];
                    $is_single = 0;
                    $last_fees = $firstIndexFessMonth[0];
                }
                $fee_invoice_template_array = array();
                $i=0;
                $calculate_amount = 0;
                if($is_single ==1){
                    $fee_invoice_template_array[$i]['type'] = 1;
                    $fee_invoice_template_array[$i]['amount'] = $fees_per_month;
                    $fee_invoice_template_array[$i]['duration'] = $from_month."|".$to_month;
                    $calculate_amount = $calculate_amount + $fees_per_month;
                    $i++;
                }else{

                    foreach ($fees_month as $fee){
                        $fee_invoice_template_array[$i]['type'] = 1;
                        $fee_invoice_template_array[$i]['amount'] = $fees_per_month;
                        $fee_invoice_template_array[$i]['duration'] = $fee;
                        $calculate_amount = $calculate_amount + $fees_per_month;
                        $i++;

                    }
                }


                    $total_array =  array();
                    $subtotal = $calculate_amount;
                    $total = $calculate_amount - $discount;
                    $state = getVar('state');//1=paid 2=partial paid
                    $received_amount = $calculate_amount - $discount;
                    if($state==2){
                        $received_amount = getVar('received_amount');
                    }
                    $total_array['subtotal'] = $subtotal;
                    $total_array['discount'] = $discount;
                    $total_array['received_amount'] = $received_amount;
                    $total_array['total'] = $total;
                    $total_array['remaining_amount'] = $total - $received_amount;

                    $type[] = 1;

                    $account_details = array('fee_invoice'=>$fee_invoice_template_array,'total'=>$total_array);

                    //if user cancel all previous invoice and start from today
                    if($invoice_option==2){




                        $this->db->query("UPDATE accounts SET invoice_generate_date='".date('Y-m-d H:i:s')."' WHERE acc_id='".$acc_id."'");
                        $last_fees = date('Y-m-d');
                        $cancel_invoice_template =  array();
                        $k=0;
                        $calculate_amount_cancel = 0;


                        foreach ($fees_month as $fee){

                            $cancel_invoice_template[$k]['type'] = 1;
                            $cancel_invoice_template[$k]['amount'] = $fees_per_month;
                            $cancel_invoice_template[$k]['duration'] = $fee;
                            $calculate_amount_cancel = $calculate_amount_cancel + $fees_per_month;
                            $k++;

                        }

                        $firstIndexFessMonthCancel = explode("|",$fees_month[0]);
                        $lastIndexFessMonthCancel = explode("|",end($fees_month));

                        $from_month_cancel = $lastIndexFessMonthCancel[0];
                        $to_month_cancel = $firstIndexFessMonthCancel[1];

                        $total_array_cancel = array();
                        $total_array_cancel['subtotal'] = $calculate_amount_cancel;
                        $total_array_cancel['discount'] = 0;
                        $total_array_cancel['received_amount'] = 0;
                        $total_array_cancel['total'] = $calculate_amount_cancel;
                        $total_array_cancel['remaining_amount'] = $calculate_amount_cancel;

                        $account_details_cancel = array("fee_invoice"=>$cancel_invoice_template,'total'=>$total_array_cancel);

                        $cancel_invoice_date = array();
                        $cancel_invoice_date['acc_id'] = $acc_id;
                        $cancel_invoice_date['machine_member_id'] = $machine_member_id;
                        $cancel_invoice_date['state'] = 3;
                        $cancel_invoice_date['subtotal'] = $calculate_amount_cancel;
                        $cancel_invoice_date['discount'] = 0;
                        $cancel_invoice_date['received_amount'] = 0;
                        $cancel_invoice_date['remaining_amount'] = $calculate_amount_cancel ;
                        $cancel_invoice_date['amount'] = $calculate_amount_cancel;
                        $cancel_invoice_date['description'] = "Cancel all previous invoices during first invoice creation";
                        $cancel_invoice_date['fees_datetime'] = date('Y-m-d H:i:s');
                        $cancel_invoice_date['fees_month'] = date('Y-m-d');
                        $cancel_invoice_date['type'] = 1;
                        $cancel_invoice_date['amount_details'] = json_encode($account_details_cancel);
                        $cancel_invoice_date['status'] = 3;
                        $cancel_invoice_date['branch_id'] = $branch_id;
                        $cancel_invoice_date['created_by'] = $this->session->userdata('user_info')->user_id;
                        $cancel_invoice_date['from_date'] = $from_month_cancel;
                        $cancel_invoice_date['to_date'] = $to_month_cancel;

                        save("invoices",$cancel_invoice_date);







                    }


                    $invoice_table = array();
                    $invoice_table['acc_id'] = $acc_id;
                    $invoice_table['machine_member_id'] = $machine_member_id;
                    $invoice_table['state'] = $state;
                    $invoice_table['subtotal'] = $subtotal;
                    $invoice_table['discount'] = $discount;
                    $invoice_table['received_amount'] = $received_amount;
                    $invoice_table['remaining_amount'] = $total - $received_amount;
                    $invoice_table['amount'] = $total;
                    $invoice_table['description'] = getVar('description');
                    $invoice_table['fees_datetime'] = date('Y-m-d H:i:s');
                    $invoice_table['fees_month'] = $last_fees;
                    $invoice_table['type'] = implode(",",$type);
                    $invoice_table['amount_details'] = json_encode($account_details);
                    $invoice_table['status'] = 2;
                    $invoice_table['branch_id'] = $branch_id;
                    $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                    $invoice_table['from_date'] = $from_month;
                    $invoice_table['to_date'] = $to_month;
                    $invoice_table['first_invoice_option'] = $invoice_option;

                    $redirect_id = save("invoices",$invoice_table);



                $JSON['result'] = 200;
                $JSON['msg'] = "Invoice added successfully.";
                $JSON['redirect_url'] = base_url(ADMIN_DIR."invoices/view/".$redirect_id);



            } else {
                $JSON['result'] = 400;
                $JSON['error'] = "Please check fee period checkboxes.";

            }
        }else{
            $JSON['result'] = 400;
            $JSON['error'] = "Invalid record found. Please try again.";


        }

        $_POST = array_merge($_POST, $JSON);

        $JSON['notification'] = show_validation_errors();

        echo json_encode($JSON);



    }

    function payNew(){
        $data['last_invoice'] = getVar('last_invoice');
        $data['acc_id'] = getVar('acc_id');
        $data['month_due'] = getVar('month_due');
        $data['register_day'] = getVar('register_day');
        $acc_types = getVal("accounts","acc_types"," WHERE acc_id='".$data['acc_id']."'");
        $data['one_month_fee'] = getVal("acc_types","monthly_charges"," WHERE acc_type_ID='".$acc_types."'");
        echo $this->load->view(ADMIN_DIR . $this->module_name . '/fees_pay_pop', $data, true);
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */