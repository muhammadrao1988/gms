<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Banned_users
 * @property M_dashboard $module
 * @property M_cpanel $m_cpanel
 */
class Dashboard extends CI_Controller
{


    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    var $day_convert_hour;//this is for graph. If date_frame=today or range_type=day then it value will set in constructor

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
    }


    public function index()
    {

        $this->load->view(ADMIN_DIR . 'dashboard/dashboard', "");
    }

    public function checkNewData()
    {
        $json = array();

        /*Get Attendance by Machine*/
        $today_attendance = json_decode(getVar($_REQUEST['machineAttendance']));
        echo '<pre>';
        print_r($today_attendance);
        echo '</pre>';
        die('Call');
        $today_attendance = getAttendenceMachine();

        if (count($today_attendance > 0)) {
            /*Exist Attendance by DB*/
            $exist_attendance = $this->module->existMembersAttendance();

            $new_attendance = array();

            //$today_data = array_unique(array_column($today_attendance, 'USERID'));
            $exist_data = array_unique(array_column($exist_attendance, 'account_check'));

            $i = 0;
            foreach ($today_attendance as $key => $row) {
                $account_check = $row['USERID'] . "_" . $row['CHECKTYPE'] . "_" . $row['sn'];
                if (in_array($account_check, $exist_data)) {
                    $exist_data[] = $account_check;
                    continue;
                } else {
                    $new_attendance['account_id'] = $row['USERID'];
                    $new_attendance['datetime'] = $row['CHECKTIME'];
                    $new_attendance['check_type'] = $row['CHECKTYPE'];
                    $new_attendance['machine_serial'] = $row['sn'];
                    $new_attendance['sensored_id'] = $row['SENSORID'];
                    $new_attendance['status'] = 1;
                    $getAccountDetail = $this->db->query("SELECT acc_id,acc_date,subscription_id,acc_name FROM accounts WHERE `serial_number`='" . $new_attendance['machine_serial'] . "' AND machine_user_id='" . $new_attendance['account_id'] . "' LIMIT 1")->row();
                    if ($getAccountDetail->acc_id != "") {
                        /***********************Monthly Fee Checking**************/
                        //check monthly fee invoice generated or not.
                        $invoiceCheck = $this->db->query("SELECT COUNT(id) AS tot_rec FROM  invoices where acc_id = '" . $getAccountDetail->acc_id . "' AND  FIND_IN_SET('1',`type`) <> 0 LIMIT 1")->row();
                        if ($invoiceCheck > 0) {
                            $getInvoice = $this->db->query("SELECT * FROM invoices WHERE `status`=1 AND acc_id='" . $getAccountDetail->acc_id . "' AND  FIND_IN_SET('1',`type`) <> 0 LIMIT 1")->row();


                            if ($getInvoice->id != "") {

                                $tot_month = checkMonthlyFeesPaid($getAccountDetail->acc_date, $getInvoice->fees_month);

                                if ($tot_month > 0) {
                                    $json[$i]['monthly_fee'] = $tot_month . " months fee UNPAID";
                                } else {
                                    $json[$i]['monthly_fee'] = "All monthly fees are PAID";
                                }
                            } else {
                                $json[$i]['monthly_fee'] = "Please adjust monthly fee invoice of this user manually.";
                            }

                        } else {
                            //monthly invoice not created
                            $json[$i]['monthly_fee'] = "Please create monthly fee invoice of this user.";
                        }
                        /***********************END*********************************/
                        /***********************Subscription Checking**************/
                        $getLastAttendance = $this->db->query("SELECT `datetime` AS last_attendance, id FROM attendance WHERE machine_serial = '" . $new_attendance['machine_serial'] . "' AND account_id = '" . $new_attendance['account_id'] . "' ORDER BY id DESC LIMIT 1")->row();
                        if ($getLastAttendance->id > 0) {
                            $getSubscriptionDay = $this->db->query("SELECT `period` FROM subscriptions WHERE id='" . $getAccountDetail->subscription_id . "'")->row();
                            if ($getSubscriptionDay->period == "") {
                                $json[$i]['subscription_status'] = "No Subscription assigned to this user.";
                            } else {
                                $subscriptionDay = $getSubscriptionDay->period;
                                $lastAttendance = $getLastAttendance->last_attendance;
                                $totDay = dayDifference($lastAttendance);
                                if ($totDay > $subscriptionDay) {
                                    $json[$i]['subscription_status'] = "Expired";
                                } else {
                                    $json[$i]['subscription_status'] = "Valid";
                                }
                            }

                        } else {
                            $json[$i]['subscription_status'] = "First Attendance entered.";

                        }
                        /***********************END*********************************/
                        save('attendance', $new_attendwance);
                        $json[$i]['account_id'] = $getAccountDetail->acc_id;
                        $json[$i]['datetime'] = $row['CHECKTIME'];
                        $json[$i]['check_type'] = $row['CHECKTYPE'];
                        $json[$i]['account_name'] = $getAccountDetail->acc_name;
                        $json[$i]['is_valid_user'] = 1;
                        echo json_encode($json);
                    }
                    $exist_data[] = $account_check;
                    $i++;
                }
            }

        }
    }

    public function returnAccount()
    {

        $id = $this->session->userdata['switch_customer'];

        $chekcAccount = "SELECT parent_child FROM users WHERE user_id='" . $id . "'";
        $userResult = $this->db->query($chekcAccount)->num_rows();

        if ($userResult > 0) {
            $SQL = "SELECT users.*
                          		FROM users
                          		JOIN accounts ON (users.parent_child = accounts.acc_id )
                          		WHERE users.`user_id`='" . $id . "' AND users.`status`=1
                          		ORDER BY user_type DESC LIMIT 1 ";

            $result = $this->db->query($SQL)->row();
            if (count($result) > 0 && is_object($result)) {
                $currentUser = $this->session->userdata['tgm_user_id'];
                $switchCustomer = $this->session->userdata['switch_customer'];
                $this->session->unset_userdata(array(
                    'logged_in' => '',
                    'tgm_user_id' => '',
                    'user_id' => '',
                    'temp_user_id' => '',
                    'username' => '',
                    'password_attempts' => '',
                    'email' => '',
                    'user_type' => '',
                    'u_type' => '',
                    'user_info' => '',
                    'advice_user_id' => '',
                    'advice_firstname' => '',
                    'advice_lastname' => '',
                    'advice_login' => '',
                    'advice_email' => '',
                    'advice_plan' => '',
                    'switch_customer' => ''


                ));

                $this->session->set_userdata(array(
                    'tgm_user_id' => $result->user_id,
                    'login_status' => $result->login_status,
                    'password_attempts' => '0',
                    'user_id' => $result->user_id,
                    'username' => $result->username,
                    'email' => $result->email,
                    'user_type' => $result->user_type,
                    'u_type' => $result->u_type,
                    'user_template_id' => $result->user_template_id,
                    'parent_id' => $result->parent,
                    'user_info' => $result,


                ));
                $this->session->set_userdata('logged_in', 1);
                die('Call');
                redirect(ADMIN_DIR . 'dashboard');

            } else {
                redirect(ADMIN_DIR . $this->module_name . '/?error=Sorry, you can not return to your own account. This user has blocked.');
            }
        } else {
            redirect(ADMIN_DIR . $this->module_name . '/?error=Sorry, Invalid user id');
        }

    }


    public function update_attendance_live()
    {

        $attendance_arr = json_decode($_POST['json_string']);


        if (is_array($attendance_arr) and count($attendance_arr) > 0) {

            $i = 0;

            foreach ($attendance_arr as $key => $attendance) {
                $getAccountDetail = $this->db->query("SELECT acc_id,acc_date,subscription_id,acc_name,machine_member_id,branch_id,invoice_generate_date FROM accounts WHERE `serial_number`='" . $attendance->sn . "' AND machine_user_id='" . $attendance->USERID . "' AND status = 1 LIMIT 1")->row();
                if ($getAccountDetail->acc_id != "") {

                    if (!getVal("attendance", "id", "WHERE `account_id` = '" . $attendance->USERID . "' AND `check_type` = '" . $attendance->CHECKTYPE . "' AND `machine_serial` = '" . $attendance->sn . "' AND DATE_FORMAT(`datetime`,'%Y-%m-%d') = '" . date('Y-m-d', strtotime($attendance->CHECKTIME)) . "'")) {


                        /***********************Monthly Fee Checking**************/
                        //check monthly fee invoice generated or not.
                        $invoiceCheck = $this->db->query("SELECT COUNT(id) AS tot_rec FROM  invoices where acc_id = '" . $getAccountDetail->acc_id . "' AND  FIND_IN_SET('1',`type`) <> 0 LIMIT 1")->row();
                        if ($invoiceCheck > 0) {
                            $getInvoice = $this->db->query("SELECT  FLOOR(DATEDIFF(CURDATE(), MAX(invoices.fees_month)) / 30) AS month_remain FROM invoices 
                                  WHERE 1 AND acc_id='" . $getAccountDetail->acc_id . "' AND   FIND_IN_SET( '1',invoices.`type`)
                                  AND invoices.`state` IN (1, 2) LIMIT 1")->row();

                                $tot_month = $getInvoice->month_remain;
                                if ($tot_month > 0) {
                                    $json[$i]['monthly_fee'] = $tot_month . " months fee UNPAID";
                                } else if($tot_month==="0") {
                                    $json[$i]['monthly_fee'] = "All monthly fees are PAID";
                                }else {
                                    $json[$i]['monthly_fee'] = "Please create monthly fee invoice of this user.";

                                }

                        } else {
                            //monthly invoice not created
                            $json[$i]['monthly_fee'] = "Please create monthly fee invoice of this user.";
                        }


                        /***********************END*********************************/
                        /***********************Subscription Checking**************/

                        $getLastAttendance = $this->db->query("SELECT `datetime` AS last_attendance, id FROM attendance WHERE machine_serial = '" . $attendance->sn . "' AND account_id = '" . $attendance->USERID . "' ORDER BY `datetime` DESC LIMIT 1")->row();

                        if ($getLastAttendance->id > 0) {
                            $getSubscriptionDay = $this->db->query("SELECT `period`,`charges` FROM subscriptions WHERE id='" . $getAccountDetail->subscription_id . "'")->row();

                            if ($getSubscriptionDay->period == "") {
                                $json[$i]['subscription_status'] = "No Subscription assigned to this user.";
                            } else {
                                $subscriptionDay = $getSubscriptionDay->period;
                                $lastAttendance = $getLastAttendance->last_attendance;
                                $totDay = dayDifference($lastAttendance);
                                if ($totDay > $subscriptionDay) {
                                    //check already created invoice or not
                                    $isAlreadyCreated =  getVal("invoices","id"," WHERE FIND_IN_SET(3,`type`) AND state=4 AND fees_month='".date('Y-m-d',strtotime($lastAttendance))."' AND acc_id='".$getAccountDetail->acc_id."'");
                                    if($isAlreadyCreated=="") {

                                        //generate subscription invoice///////////////////
                                        $other_invoice_template_array = array();
                                        $other_invoice_template_array[0]['type'] = 3;
                                        $other_invoice_template_array[0]['amount'] = $getSubscriptionDay->charges;
                                        $other_invoice_template_array[0]['duration'] = $lastAttendance;
                                        $fee_invoice_template_array[0]['description'] = "Subscription expired on ".$lastAttendance.". Invoice generated on the following date ".date('d-M-Y');
                                        $calculate_amount = $getSubscriptionDay->charges;

                                        $total_array = array();
                                        $subtotal = $calculate_amount;
                                        $total = $calculate_amount;
                                        $state = 4;//1=paid 2=partial paid 4=unpaid
                                        $received_amount = 0;
                                        $total_array['subtotal'] = $subtotal;
                                        $total_array['discount'] = 0;
                                        $total_array['received_amount'] = $received_amount;
                                        $total_array['total'] = $total;
                                        $total_array['remaining_amount'] = $total - $received_amount;
                                        $type = array();
                                        $type[] = 3;

                                        $account_details = array('other_invoice' => $other_invoice_template_array, 'total' => $total_array);
                                        $invoice_table = array();
                                        $invoice_table['acc_id'] = $getAccountDetail->acc_id;
                                        $invoice_table['machine_member_id'] = $getAccountDetail->machine_member_id;
                                        $invoice_table['state'] = $state;
                                        $invoice_table['subtotal'] = $subtotal;
                                        $invoice_table['discount'] = 0;
                                        $invoice_table['received_amount'] = $received_amount;
                                        $invoice_table['remaining_amount'] = $total - $received_amount;
                                        $invoice_table['amount'] = $total;
                                        $invoice_table['description'] = 'Subscription duration expired';
                                        $invoice_table['fees_datetime'] = date('Y-m-d H:i:s');
                                        $invoice_table['fees_month'] = $lastAttendance;
                                        $invoice_table['type'] = implode(",", $type);
                                        $invoice_table['amount_details'] = json_encode($account_details);
                                        $invoice_table['status'] = 2;
                                        $invoice_table['branch_id'] = $getAccountDetail->branch_id;
                                        $invoice_table['created_by'] = $this->session->userdata('user_info')->user_id;
                                        $invoice_table['from_date'] = $lastAttendance;
                                        $invoice_table['to_date'] = $lastAttendance;

                                        $redirect_id = save("invoices", $invoice_table);
                                    }
                                    ///////////////////////////////////////////////////////




                                    $json[$i]['subscription_status'] = "Expired";
                                } else {
                                    $json[$i]['subscription_status'] = "Valid";
                                }
                            }

                        } else {
                            $json[$i]['subscription_status'] = "First Attendance entered.";

                        }


                        /***********************END*********************************/
                        /********************Check pending invoices***************************/
                        $unpaidInvoiceCheck = $this->db->query("SELECT COUNT(id) AS unpaid_invoice FROM  invoices where acc_id = '" . $getAccountDetail->acc_id . "' AND `state` IN(2,4) LIMIT 1")->row();
                        $json[$i]['unpaid_invoice'] = $unpaidInvoiceCheck->unpaid_invoice;
                        /***********************END*********************************/


                        $acc_id = getVal('accounts','acc_id',' WHERE machine_user_id = "'.$attendance->USERID.'" and serial_number="'.$attendance->sn.'"');
                        $this->db->query("INSERT INTO `attendance` SET `account_id` = '" . $attendance->USERID . "', `status` = '1', `datetime` = '" . $attendance->CHECKTIME . "', `check_type` = '" . $attendance->CHECKTYPE . "', `machine_serial` = '" . $attendance->sn . "', `sensored_id` = '" . $attendance->SENSORID . "' ,`acc_id` = '".$acc_id."',`is_machine`=1");

                        $json[$i]['account_id'] = $getAccountDetail->machine_member_id;
                        $json[$i]['datetime'] = $attendance->CHECKTIME;
                        $json[$i]['check_type'] = $attendance->CHECKTYPE;
                        $json[$i]['account_name'] = $getAccountDetail->acc_name;
                        $json[$i]['is_valid_user'] = 1;//first attendance


                    }
                    else{
                        $json[$i]['account_id'] = $getAccountDetail->machine_member_id;
                        $json[$i]['datetime'] = $attendance->CHECKTIME;
                        $json[$i]['check_type'] = $attendance->CHECKTYPE;
                        $json[$i]['account_name'] = $getAccountDetail->acc_name;
                        $json[$i]['is_valid_user'] = 2;//more than one attendance in a day
                    }
                    $i++;
                }

            }

            echo json_encode($json);
        }
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */