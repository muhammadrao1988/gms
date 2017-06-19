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
        //$data['query'] = "Select * from ".$this->table." where 1".$where;
        $search = getVar('search');
        if ($search['ic:type'] != "") {
            $where = str_replace("AND ic.type = '" . $search['ic:type'] . "'", " AND FIND_IN_SET('" . $search['ic:type'] . "', ic.`type`)", $where);
        }
        if ($search['ic:fees_month'] != "") {

            $fees_month = date('Ym', strtotime($search['ic:fees_month']));


            $where = str_replace(
                "AND ic.fees_month LIKE '%" . $search['ic:fees_month'] . "%'",
                " AND EXTRACT( YEAR_MONTH FROM `fees_month` ) = '" . $fees_month . "'",
                $where);

        }
        if($search['ic:fees_datetime']!=""){
            $where = str_replace(
                "AND ic.fees_datetime LIKE '%".$search['ic:fees_datetime']."%'",
                " AND ic.fees_datetime LIKE '%".date('Y-m-d',strtotime($search['ic:fees_datetime']))."%'",
                $where);

        }
        //AND FIND_IN_SET('1', iv.`type`)

        $data['query'] = "SELECT                               
                              ic.`id`,
                              ic.acc_id,
                               ic.machine_member_id,
                               ac.acc_name ,
                              
                               CASE ic.state
                            WHEN 1 THEN ic.received_amount
                            WHEN 2 THEN CONCAT('Total: ',ic.amount,'<br>Received: ',ic.received_amount,'<br>Remain: ',ic.amount - ic.received_amount)
                            WHEN 3 THEN ic.amount
                            WHEN 4 THEN ic.amount
                           END AS amount,   
                                                                                 
                            
                                                       
                              IF(FIND_IN_SET(1,ic.`type`),CONCAT('From ',DATE_FORMAT(ic.from_date,'%d-%b-%Y'),' To ',DATE_FORMAT(ic.to_date,'%d-%b-%Y')),DATE_FORMAT(ic.fees_month,'%d-%b-%Y')) as from_date,                              
                              ic.`type` ,
                              ic.state  ,
                              ic.fees_month
                            FROM
                              invoices AS ic 
                               INNER JOIN accounts AS ac 
                                ON (ac.acc_id = ic.acc_id) WHERE 1 AND ic.branch_id = '" . $this->branch_id . "' " . (($this->is_machine == 1) ? " AND ac.machine_member_id !='' " : '') . " 
                           " . $where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }

    public function monthlyInvoice()
    {
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
                        WHERE acc.branch_id =  '" . $this->branch_id . "' 
                          " . (($this->is_machine == 1) ? " AND acc.machine_member_id !='' " : '') . " 
                          AND FIND_IN_SET( '1',inv.`type`) 
                          AND inv.`state` IN (1, 2) 
                          " . $where . "
                        GROUP BY inv.acc_id 
                        HAVING last_paid < DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";

        $this->load->view(ADMIN_DIR . $this->module_name . '/grid_monthly', $data);
    }

    public function form()
    {
        $id = intval(getUri(4));
        $tempId = getVar('tempID');
        $firstInvoice = getVar('firstInvoice');
        if ($tempId && $tempId > 0) {

            $branch_id = getVal("accounts", "branch_id", "WHERE acc_id='" . $tempId . "'");
            $check_invoice = getVal("invoices", "id", "WHERE acc_id='" . $tempId . "' AND FIND_IN_SET('1', `type`) LIMIT 1");
            if ($branch_id == $this->branch_id && ($check_invoice == 0 OR $check_invoice == "")) {
                $data['tempID'] = $tempId;
                $SQL = "SELECT accounts.acc_id,accounts.parent,accounts.acc_types,
                        accounts.status,accounts.acc_name,accounts.acc_description,
                        accounts.acc_date,accounts.invoice_generate_date,accounts.branch_id,
                        accounts.subscription_id,accounts.serial_number,accounts.machine_member_id,
                         accounts.machine_user_id,accounts.discount,accounts.discount_value,
                         acc_types.Name AS membership_name,acc_types.monthly_charges,
                         subscriptions.period AS subscription_days,subscriptions.name AS subscription_name,subscriptions.charges AS subscription_fee
                         
                        FROM accounts 
                        JOIN acc_types ON (acc_types.acc_type_ID = accounts.acc_types)
                        JOIN subscriptions ON (subscriptions.id = accounts.subscription_id)
                        WHERE  acc_id='" . $tempId . "'";
                $data['tempRow'] = $this->db->query($SQL)->row();


            } else {
                $data['tempID'] = 0;
            }
        } else {
            $data['tempID'] = 0;
        }

        if ($firstInvoice && $firstInvoice == 1) {
            $data['firstInvoice'] = 1;
        } else {
            $data['firstInvoice'] = 0;
        }

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "' AND branch_id='" . $this->branch_id . "'";
            $data['row'] = $this->db->query($SQL)->row();
            if ($data['row']->id == "") {
                redirect(ADMIN_DIR . $this->module_name . '/?error=Invalid access');
            }
            if($data['row']->state!=4){ //only unpaid invoices can be edit.
                redirect(ADMIN_DIR . $this->module_name . '/?error=Invoice number '.$data['row']->id.' can not be edit. Only unpaid invoices can be edit.');
            }
        }
        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
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
            $data['rows'] = $this->db->query($SQL)->result();
            if ($data['rows'][0]->id == "") {
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
            $machine_member_id = getVal('accounts', 'machine_member_id', " WHERE acc_id='" . $acc_id . "'");
            $branch_id = getVal('accounts', 'branch_id', " WHERE acc_id='" . $acc_id . "'");
            $is_first_invoice = getVar('firstInvoice');


            if ($is_first_invoice == 1) {
                if ($branch_id != $this->branch_id) {
                    redirect(ADMIN_DIR . "/members?error=Invalid Access");
                }

                $all_invoice_generate_date = getVar('invoice_generate_date');//From this date next invoices will create
                $all_invoice_generate_date = date('Y-m-d', strtotime($all_invoice_generate_date));
                $account_invoice_generate_date = getVar('account_invoice_generate_date');

                    $all_invoice_day = date('d', strtotime($all_invoice_generate_date));
                    $invoice_next_month = date('m', strtotime($all_invoice_generate_date));
                    $invoice_date = getVar('invoice_date');//this is invoice entry date. For collection calculation
                    $fees_per_month = getVar('fees_per_month');//fees per month after discount value
                    $monthly_charges = getVar('monthly_charges');//fees per month charges without discount
                    $discount = getVar('discount');//1=Percent discount, 2=Rupees Discount 0=No Discount
                    $discount_value = getVar('discount_value');
                    $subscription_fee = getVar('subscription_fee');
                    $other_entry_type = getVar('type');
                    $other_entry_type = array_filter($other_entry_type);
                    $other_entry_type = array_values($other_entry_type);
                    $other_entry_amount = getVar('amount');
                    $other_entry_amount = array_filter($other_entry_amount);
                    $other_entry_amount = array_values($other_entry_amount);
                    $account_invoice_generate_date = getVar('account_invoice_generate_date');
                    $month_due = ceil(dayDifference($all_invoice_generate_date) / 30);

                    if ($discount > 0) {
                        if ($discount == 1) {
                            //discount in percent
                            $membership_fee = "Membership fee = ".$monthly_charges.". By default membership discount = " . $discount_value . "%. After discount Membership fee =  " . $fees_per_month;

                        } else {
                            $membership_fee = "Membership fee = ".$monthly_charges.". By default membership discount =" . $discount_value . "rs. After discount Membership fee =  " . $fees_per_month;
                        }
                        $fee_invoice_template_array[0]['description'] = $membership_fee;
                    } else {
                        $membership_fee = "Memebership fee = " . $fees_per_month;
                        $fee_invoice_template_array[0]['description'] = $membership_fee;
                    }


                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //current invoice with subscription and other
                    //Making Monthly fees data
                    $general_date = $all_invoice_generate_date;
                    $general_date_end = date('Y-m-d', strtotime($general_date . ' +30 days'));

                    $fee_invoice_template_array[0]['duration'] = $all_invoice_generate_date . "|" . $general_date_end;


                    //Subscription invoice
                    $other_invoice_template_array = array();
                    $other_invoice_template_array[0]['type'] = 3;
                    $other_invoice_template_array[0]['amount'] = $subscription_fee;
                    $other_invoice_template_array[0]['duration'] = $all_invoice_generate_date;
                    $other_invoice_template_array[0]['description'] = "Subscription Fee invoice while generating first invoice.";

                    $invoice_type = array(1, 3);
                    $calculate_amount = $subscription_fee + $fees_per_month;

                    //other invoice entry
                    if (isset($other_entry_type) && count($other_entry_type) > 0) {

                        $j = 0;
                        $i = 1;
                        foreach ($other_entry_type as $tp) {
                            $other_invoice_template_array[$i]['type'] = $tp;
                            $other_invoice_template_array[$i]['amount'] = $other_entry_amount[$j];
                            $other_invoice_template_array[$i]['duration'] = $all_invoice_generate_date;
                            $other_invoice_template_array[$i]['description'] = "";
                            $calculate_amount = $calculate_amount + $other_entry_amount[$j];
                            $invoice_type[] = $tp;
                            $j++;
                            $i++;


                        }

                    }
                    $invoice_type = array_unique($invoice_type);
                    $subtotal = $calculate_amount;
                    $total_array = array();
                    $total_array['subtotal'] = $subtotal;
                    $total_array['discount'] = 0;
                    $total_array['received_amount'] = 0;
                    $total_array['total'] = $subtotal;
                    $total_array['remaining_amount'] = $subtotal;
                    $account_details = array('fee_invoice' => $fee_invoice_template_array, 'other_invoice' => $other_invoice_template_array, 'total' => $total_array);

                    $invoice_table = array();
                    $invoice_table['acc_id'] = $acc_id;
                    $invoice_table['machine_member_id'] = $machine_member_id;
                    $invoice_table['state'] = 4;
                    $invoice_table['subtotal'] = $subtotal;
                    $invoice_table['discount'] = 0;
                    $invoice_table['received_amount'] = 0;
                    $invoice_table['remaining_amount'] = $subtotal;
                    $invoice_table['amount'] = $subtotal;
                    $invoice_table['description'] = getVar('description');
                    $invoice_table['fees_datetime'] = date('Y-m-d', strtotime($invoice_date));
                    $invoice_table['fees_month'] = $all_invoice_generate_date;
                    $invoice_table['type'] = implode(",", $invoice_type);
                    $invoice_table['amount_details'] = json_encode($account_details);
                    $invoice_table['status'] = 2;
                    $invoice_table['branch_id'] = $branch_id;
                    $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                    $invoice_table['from_date'] = $all_invoice_generate_date;
                    $invoice_table['to_date'] = $general_date_end;

                    save('invoices', $invoice_table);

                    //Account update

                    $sql_acc = "Update accounts SET invoice_generate_date = '" . $all_invoice_generate_date . "' WHERE acc_id='" . $acc_id . "'";
                    $this->db->query($sql_acc);

                //previous invoice (If user choose current month but registration date is before date)
                if ($month_due > 0) {

                    for($i=1;$i<=$month_due;$i++){
                        $from = $general_date;
                        $general_date = date('Y-m-d', strtotime($general_date . ' +30 days'));
                        $to = $general_date;


                        if(strtotime($general_date)!=strtotime($general_date_end)){

                            // echo "From: ".$from." To:".$to;
                            //  echo "<br>";

                            $fee_invoice_template_array[0]['type'] = 1;
                            $fee_invoice_template_array[0]['amount'] = $fees_per_month;
                            $fee_invoice_template_array[0]['duration'] = $from . "|" . $to;

                            $total_array = array();
                            $total_array['subtotal'] = $fees_per_month;
                            $total_array['discount'] = 0;
                            $total_array['received_amount'] = 0;
                            $total_array['total'] = $fees_per_month;
                            $total_array['remaining_amount'] = $fees_per_month;

                            $account_details = array('fee_invoice' => $fee_invoice_template_array, 'total' => $total_array);
                            $invoice_table = array();
                            $invoice_table['acc_id'] = $acc_id;
                            $invoice_table['machine_member_id'] = $machine_member_id;
                            $invoice_table['state'] = 4;
                            $invoice_table['subtotal'] = $fees_per_month;
                            $invoice_table['discount'] = 0;
                            $invoice_table['received_amount'] = 0;
                            $invoice_table['remaining_amount'] = $fees_per_month;
                            $invoice_table['amount'] = $fees_per_month;
                            $invoice_table['description'] = $membership_fee;
                            $invoice_table['fees_datetime'] = $from;
                            $invoice_table['fees_month'] = $from;
                            $invoice_table['type'] = 1;
                            $invoice_table['amount_details'] = json_encode($account_details);
                            $invoice_table['status'] = 2;
                            $invoice_table['branch_id'] = $branch_id;
                            $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                            $invoice_table['from_date'] = $from;
                            $invoice_table['to_date'] = $to;
                            /* echo '<pre>';
                             print_r($invoice_table);
                             echo '</pre>';*/
                            save("invoices",$invoice_table);

                        }

                    }
                }

                    redirect(ADMIN_DIR . $this->module_name . '?invoices?search[id]=&search[ic:machine_member_id]=' . $machine_member_id . "&msg=Invoice generated successfully.");


            }
            else {

                $type = getVar('type');
                $amount = getVar('amount');
                $state = getVar('state');//1=paid 2=partial paid 3=cancelled 4=unpaid
                $invoice_date = date('Y-m-d',strtotime(getVar('invoice_date')));

                $invoice_template_array = array();
                $i = 0;
                $calculate_amount = 0;

                if (isset($type) && count($type) > 0) {
                    $j = 0;
                    foreach ($type as $tp) {
                        $invoice_template_array[$i]['type'] = $tp;
                        $invoice_template_array[$i]['amount'] = $amount[$j];
                        $invoice_template_array[$i]['duration'] = date('Y-m-d');
                        $invoice_template_array[$i]['description'] ="";
                        $calculate_amount = $calculate_amount + $amount[$j];
                        $j++;
                        $i++;

                    }
                    $subtotal = $calculate_amount;
                    $total = $subtotal;

                    $total_array = array();
                    $total_array['subtotal'] = $subtotal;
                    $total_array['discount'] = 0;
                    $total_array['received_amount'] = 0;
                    $total_array['total'] = $subtotal;
                    $total_array['remaining_amount'] = $subtotal;

                    $account_details = array('other_invoice' => $invoice_template_array, 'total' => $total_array);

                    $type = array_unique($type);

                    $invoice_table = array();
                    $invoice_table['acc_id'] = $acc_id;
                    $invoice_table['machine_member_id'] = $machine_member_id;
                    $invoice_table['state'] = $state;
                    $invoice_table['subtotal'] = $subtotal;
                    $invoice_table['discount'] = 0;
                    $invoice_table['fees_month'] = $invoice_date;
                    $invoice_table['received_amount'] = 0;
                    $invoice_table['remaining_amount'] = $total;
                    $invoice_table['amount'] = $total;
                    $invoice_table['description'] = getVar('description');
                    $invoice_table['fees_datetime'] = $invoice_date;
                    $invoice_table['amount_details'] = implode(",", $type);
                    $invoice_table['type'] = implode(",", $type);
                    $invoice_table['amount_details'] = json_encode($account_details);
                    $invoice_table['status'] = 2;
                    $invoice_table['branch_id'] = $branch_id;
                    $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                    $invoice_table['from_date'] = $invoice_date;
                    $invoice_table['to_date'] = $invoice_date;
                    $redirect_id = save("invoices", $invoice_table);


                }

            }
            redirect(ADMIN_DIR . $this->module_name . '/view?id=' . $redirect_id);


        }
    }


    public function update()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {


            $acc_id = getVar('acc_id');
            $machine_member_id = getVal('accounts', 'machine_member_id', " WHERE acc_id='" . $acc_id . "'");
            $branch_id = getVal('accounts', 'branch_id', " WHERE acc_id='" . $acc_id . "'");
            $invoice_id = getVar("id");
            $amount_details = getVal("invoices", "amount_details", " WHERE id='" . $invoice_id . "'");
            $amount_details = json_decode($amount_details);


            if ($branch_id == $this->branch_id && count($amount_details) > 0 && $invoice_id) {
                $account_details = array();
                $fees_month = $amount_details->fee_invoice;
                $fee_invoice_template_array = array();
                $type = getVar('type');
                $amount = getVar('amount');
                $discount = getVar('discount');
                $discount = ($discount == "" ? 0 : $discount);
                $calculate_amount = 0;
                $i = 0;
                $type_fee = "";
                foreach ($fees_month as $fee) {
                    $fee_invoice_template_array[$i]['type'] = 1;
                    $fee_invoice_template_array[$i]['amount'] = $fee->amount;
                    $fee_invoice_template_array[$i]['duration'] = $fee->duration;
                    $calculate_amount = $calculate_amount + $fee->amount;
                    $type_fee = ",1";
                    $i++;
                    $account_details['fee_invoice'] = $fee_invoice_template_array;

                }
                $j = 0;
                foreach ($type as $tp) {
                    $invoice_template_array[$i]['type'] = $tp;
                    $invoice_template_array[$i]['amount'] = $amount[$j];
                    $invoice_template_array[$i]['duration'] = date('Y-m-d');
                    $calculate_amount = $calculate_amount + $amount[$j];
                    $j++;
                    $i++;

                }
                $total_array = array();
                $subtotal = $calculate_amount;
                $total = $calculate_amount - $discount;
                $state = getVar('state');//1=paid 2=partial paid
                $received_amount = $calculate_amount - $discount;
                if ($state == 2) {
                    $received_amount = getVar('received_amount');
                }
                $total_array['subtotal'] = $subtotal;
                $total_array['discount'] = $discount;
                $total_array['received_amount'] = $received_amount;
                $total_array['total'] = $total;
                $total_array['remaining_amount'] = $total - $received_amount;
                $type = array_unique($type);
                if ($type_fee != "") {
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
                $invoice_table['amount_details'] = implode(",", $type);
                $invoice_table['type'] = implode(",", $type);
                $invoice_table['amount_details'] = json_encode($account_details);
                $invoice_table['status'] = 2;
                $invoice_table['branch_id'] = $branch_id;
                $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;

                $where = "id='" . $invoice_id . "'";

                $redirect_id = save("invoices", $invoice_table, $where);
                redirect(ADMIN_DIR . $this->module_name . '/view?id=' . $invoice_id);
            } else {
                redirect(ADMIN_DIR . $this->module_name . '?error=Invalid record');
            }

        }
    }

    function status()
    {
        $JSON = array();
        $id = getVar('status-id');
        $login_status_val = getVal('users', 'status', 'WHERE user_id ="' . $id . '"');
        if ($login_status_val == 0 || $login_status_val == 2 || $login_status_val == 3) {
            $status = 1;
        } else if ($login_status_val == 1) {
            $status = 0;
        } else {
            $status = 3;
        }

        $where = $this->id_field . "='" . $id . "' ";
        save($this->table, array('status' => $status), $where);
        $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Status has been changed...</div>';
        $redirct_url = '?msg=Status has been changed..';
        $JSON['redirect_url'] = $redirct_url;
        echo json_encode($JSON);


    }

    public function delete()
    {
        $JSON = array();
        if (getVar('action') == "") {
            $id = getVar('del-id');
        } else {
            $id = getVar('del-all');
        }
        $SQL = "DELETE FROM " . $this->table . " WHERE `" . $this->id_field . "` IN(" . $id . ")";
        $this->db->query($SQL);
        $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Record has been deleted..</div>';
        $redirct_url = '?msg=Record has been deleted..';
        $JSON['redirect_url'] = $redirct_url;
        echo json_encode($JSON);


    }

    public function payPayment()
    {
        $acc_id = getVar('acc_id');
        $invoice_id = getVar('invoice_id');
        $sql = "SELECT * FROM invoices WHERE id='".$invoice_id."' AND branch_id='".$this->branch_id."' AND `state` NOT IN(1,3) AND acc_id='".$acc_id."'";
        $row = $this->db->query($sql)->row();
        $JSON = array();
        if($row->id!=""){

           $status = getVar('status');
           $receipt_date = getVar('receipt_date');
           $received_amount = getVar('received_amount');
           $discount = getVar('discount');
           $discount = ($discount=="" ? 0 : $discount);
           $received_discount = $received_amount + $discount;
           $description = getVar('description');
           if($receipt_date==""){
               $JSON['result'] = 400;
               $JSON['error'] = "Please select Date of Receipt";
           }else if( $received_amount==""){
               $JSON['result'] = 400;
               $JSON['error'] = "Please Insert received amount";
           }else if( $received_discount > $row->remaining_amount){
               $JSON['result'] = 400;
               $JSON['error'] = "(Received And Discount) amount should be less than or equal to Amount.";
           }else{

               if($received_discount ==$row->remaining_amount){
                   $status = 1;
               }else{
                   $status = 2;
               }
               if($status==1) {

                   $sql = "UPDATE invoices SET `state`=1, 
                                  received_amount='".$row->subtotal."', 
                                  remaining_amount=0 
                                  WHERE id='".$row->id."'";
               }else{

                   $sql = "UPDATE invoices SET `state`=2, 
                                  received_amount=received_amount + ".$received_discount.", 
                                  remaining_amount=remaining_amount - ".$received_discount." 
                                  WHERE id='".$row->id."'";

               }

               $this->db->query($sql);

               $receipt_table =  array();
               $receipt_table['invoice_id'] = $row->id;
               $receipt_table['acc_id'] = $row->acc_id;
               $receipt_table['branch_id'] = $row->branch_id;
               $receipt_table['receipt_date'] = date('Y-m-d',strtotime($receipt_date));
               $receipt_table['created_at'] = date('Y-m-d H:i:s');
               $receipt_table['created_by'] = $this->session->userdata('user_info')->user_id;
               $receipt_table['subtotal'] = $received_amount;
               $receipt_table['discount'] = $discount;
               $receipt_table['received_amount'] = $received_amount;
               $receipt_table['total'] = $received_discount;
               $receipt_table['status'] = $status;
               $receipt_table['description'] = $description;

               $id = save("invoice_receipt",$receipt_table);


               $JSON['result'] = 200;
               $JSON['msg'] = "Receipt created successfully. Receipt id is ".$id;
               $JSON['redirect_url'] = base_url(ADMIN_DIR . "invoices" );
           }
        }else{
            $JSON['result'] = 400;
            $JSON['error'] = "You do not have access to manipulate this invoice";

        }

        $_POST = array_merge($_POST, $JSON);

        $JSON['notification'] = show_validation_errors();

        echo json_encode($JSON);


    }

    public function cancelPayment(){
        $acc_id = getVar('acc_id');
        $invoice_id = getVar('invoice_id');
        $sql = "SELECT * FROM invoices WHERE id='".$invoice_id."' AND branch_id='".$this->branch_id."' AND `state` NOT IN(1,3) AND acc_id='".$acc_id."'";
        $row = $this->db->query($sql)->row();
        $JSON = array();
        if($row->id!=""){
            $sql = "UPDATE invoices SET `state`=3 WHERE id='".$row->id."'";
            $this->db->query($sql);


            $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Invoice number '.$row->id.' cancelled successfully.</div>';
            $redirct_url = '?msg=Invoice number '.$row->id.' cancelled successfully.';
            $JSON['redirect_url'] = $redirct_url;

        }else{
            $JSON['result'] = 400;
            $JSON['error'] = "You do not have access to cancel this invoice";

            $JSON['notification'] = '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>You do not have access to cancel this invoice.</div>';
            $redirct_url = '?error=You do not have access to cancel this invoice';
            $JSON['redirect_url'] = $redirct_url;

        }

        echo json_encode($JSON);

    }

    function payNew()
    {
        $invoice_id = getVar('last_invoice');
        $acc_id = getVar('acc_id');
        $sql = "SELECT * FROM invoices WHERE id='".$invoice_id."' AND branch_id='".$this->branch_id."' AND `state` NOT IN(1,3) AND acc_id='".$acc_id."'";
        $row = $this->db->query($sql)->row();
        if($row->id!=""){
            $data['result'] = 200;
            $account_detail = "SELECT acc_id,acc_name,discount,discount_value,acc_types,subscription_id FROM accounts WHERE acc_id='".$row->acc_id."'";
            $account_row = $this->db->query($account_detail)->row();
            $data['row'] = $row;
            $data['account_row'] = $account_row;
            $data['one_month_fee'] = getVal("acc_types", "monthly_charges", " WHERE acc_type_ID='" . $account_row->acc_types . "'");
        }else{
            $data['result'] = 400;
        }


        echo $this->load->view(ADMIN_DIR . $this->module_name . '/fees_pay_pop', $data, true);
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */