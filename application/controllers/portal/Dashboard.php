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
        $today_attendance = getAttendenceMachine();
        if (count($today_attendance > 0)) {
            /*Exist Attendance by DB*/
            $exist_attendance = $this->module->existMembersAttendance();

            $new_attendance = array();
            //$today_data = array_unique(array_column($today_attendance, 'USERID'));
            $exist_data = array_unique(array_column($exist_attendance, 'account_check'));
            //$today_type = array_unique(array_column($today_attendance, 'CHECKTYPE'));
            //$exist_type = array_unique(array_column($exist_attendance, 'check_type'));
            $i = 0;
            foreach ($today_attendance as $key=>$row){
                $account_check = $row['USERID']."_".$row['CHECKTYPE']."_".$row['sn'];
                if(in_array($account_check,$exist_data)){
                    $exist_data[] = $account_check;
                    continue;
                }else{
                    $new_attendance['account_id'] = $row['USERID'];
                    $new_attendance['datetime'] = $row['CHECKTIME'];
                    $new_attendance['check_type'] = $row['CHECKTYPE'];
                    $new_attendance['machine_serial'] = $row['sn'];
                    $new_attendance['sensored_id'] = $row['SENSORID'];
                    $new_attendance['status'] = 1;
                    save('attendance',$new_attendance);
                    $json[$i]['account_id'] = $row['USERID'];
                    $json[$i]['datetime'] = $row['CHECKTIME'];
                    $json[$i]['check_type'] = $row['CHECKTYPE'];
                    $exist_data[] = $account_check;
                    $i++;
                }
            }
            echo json_encode($json);
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

    public function save_dashboard_configuration()
    {
        $owner_id = $this->session->userdata['user_info']->parent_child;
        if (getVar('save_dashboard_config') == 1 && $owner_id != "") {

            $report_array = array("today", "yesterday", "this_week", "current_month");
            $graph_array = array("call", "min", "acd", "mcall");

            $selected_graph_value = getVar('selected_graph_value');
            $selected_report_value = getVar('selected_report_value');


            if (in_array($selected_graph_value, $graph_array) && in_array($selected_report_value, $report_array)) {

                $configuration = array(
                    'report_summary' => array(
                        'date_frame' => $selected_report_value,
                        'selected_graph' => $selected_graph_value
                    )
                );
                $json_encode_configuration = json_encode($configuration);
                $dbdata['configuration'] = $json_encode_configuration;
                $where = "acc_id ='" . $owner_id . "'";
                save("accounts", $dbdata, $where);
                $result_array = array('result' => 200);
                echo json_encode($result_array);
            } else {
                $result_array = array('result' => 400);
                echo json_encode($result_array);
            }

        }
    }

    public function viewSearchResult()
    {

        if (getVar('number') != '') {
            $data = array();
            $owner_id = $this->session->userdata['user_info']->parent_child;
            $where = " WHERE numbers.owner = '" . $owner_id . "'";

            $full_number = getVar('number');
            /////// to remove intial zero
            $getfirstletter = substr($full_number, 0, 1);
            if ($getfirstletter == 0) {
                $length_number = strlen($full_number);
                $full_number = substr($full_number, 1, $length_number);
            }
            //$where 				.= str_replace("full_number = '".$search_data['full_number']."'","full_number LIKE '%".$full_number."%'",getFindQuery());
            //$where 			.= getFindQuery();

            $data['title'] = $this->module_title;

            $where .= ' AND numbers.full_number LIKE "%' . $full_number . '"';


            $queryAll = "SELECT COUNT(id) as num_row FROM numbers" . $where;
            $total_row = $this->db->query($queryAll)->row();
            $data['total_record'] = $total_row->num_row;

            if ($data['total_record'] > 0) {
                $data['resultsPerPage'] = $resultsPerPage = 10;
                $data['paged'] = $paged = getVar('page');
                if ($paged > 0) {
                    $page_limit = $resultsPerPage * ($paged - 1);
                    $pagination_sql = " LIMIT  $page_limit, $resultsPerPage";
                } else {
                    $pagination_sql = " LIMIT 0 , $resultsPerPage";
                }
                $queryResult = "SELECT id,full_number,num_name FROM numbers" . $where . $pagination_sql;


                $data['num_rows'] = $num_rows = $this->db->query($queryResult)->num_rows();
                $data['allRows'] = $allRows = $this->db->query($queryResult)->result();
                $html = '';
                foreach ($allRows as $row) {
                    $html .= '<div class="panel">
                                <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $row->full_number . '">
                                    <h4 class="panel-title">' . numberWithSpace($row->full_number) . ' - ' . $row->num_name . '
                                        <span class="tools pull-right"><a href="javascript:void(0);" class="fa fa-chevron-right"></a></span>
                                    </h4>
                                </div>
                                <div id="collapse' . $row->full_number . '" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <p>Please select what you would like to do</p>
                                            <a action="report_number" href="' . site_url(ADMIN_DIR . "my_numbers_reports/detailed_view/?number=" . $row->full_number) . '&date_frame=today" class="btn btn-report styled-btn" data-original-title="REPORT"><i class="fa fa-file-text-o"></i> View Report</a>
                                            &nbsp;
                                            <a action="edit" href="' . site_url(ADMIN_DIR . "numbers/form/number_manager/" . $row->full_number) . '" class="btn btn-black styled-btn" data-original-title="Manage"><i class="fa fa-wrench"></i>Edit Configuration</a>
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                }
                if ($num_rows == $resultsPerPage) {
                    $html .= '<div class="loadbutton" style="text-align: center; padding-top: 5px;"><span class="loadmore link-color" style="font-weight: bold;cursor: pointer" data-page="' . ($paged + 1) . '">View More</span></div>';
                }
                echo $html;
            } else {
                echo '<div class="alert alert-info ">No Record found.</div>';
            }
        } else {
            echo '<div class="alert alert-info ">Invalid number found. Please try again</div>';
        }

        // echo $this->load->view(ADMIN_DIR . 'dashboard/dashboard_search_modal', $data, true);

    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */