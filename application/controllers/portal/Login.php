<?php
/**
 * Developed by Adnan Bashir.
 * Email: pisces_adnan@hotmail.com
 * Autour: Adnan Bashir
 * Date: 5/26/12
 * Time: 10:35 AM
 */
/**
 * Class Login
 * @property M_login $m_login
 * @property M_cpanel $m_cpanel
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

    var $captach_secret = "6LcQbQoTAAAAAG0Z4-OEFAan6E4Ye4ePu00czEJe";

    public function __construct()
    {

        parent::__construct();
        $this->load->model(ADMIN_DIR . 'm_login');

    }

    function forget_user()
    {
        $this->index();
    }

    public function index()
    {

        if (sessionVar('logged_in')) {
            redirect(ADMIN_DIR . 'dashboard');
        }
		
        $data['page'] = (in_array(getUri(3), array('forget_user', 'forget_pass')) ? getUri(3) : '');

        //Load Login
       // $data['recaptcha'] = recaptcha_get_html(M_login::PUBLICKEY, $error);
		
        $this->load->view(ADMIN_DIR . 'login', $data);
    }

    function forget_pass()
    {
        $this->index();
    }

    function do_forget_pass()
    {

        //$resp = recaptcha_check_answer(M_login::PRIVATEKEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        $captcha = getVar('g-recaptcha-response');
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->captach_secret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        /*-------- recaptcha Validation ------*/
        if($response['success'] == false){
            $captchaError = "reCaptcha not valid";
            $data['captchaError'] = $captchaError;
            if ($this->input->is_ajax_request()) {
                $JSON['error'] = $captchaError;
            } else {
                redirect(ADMIN_DIR . 'login/forget_pass/?error=' . $captchaError);
            }

            $_POST = array_merge($_POST, $JSON);
            $JSON['notification'] = show_validation_errors_login();

            echo json_encode($JSON);
            exit;
        }
        /*-------- recaptcha Validation ------*/

        $result = $this->m_login->checkUser(" AND email='" . getVar('email') . "' AND username = '" . getVar('username') . "'");
        if ($result) {
            /*-------- Email sending ------*/
            //$reset_key = $this->m_login->updateResetKey($result->user_id);
            //$result->reset_key = $reset_key;
			
			if($result->status==2){
			
			$company = $this->m_login->account_name($result->parent_child);
			$reseller = $this->m_login->account_name($result->parent);
            $this->email->subject('Warning Admin Locked Account Attempting to login');
			$message = 'Hi,<br><br>
						The following admin locked account is attempting to unlock their account
						<br/>
						<br/>
						Username: '.$result->username.'
						<br/>
						Company: '.$company->acc_name.'
						<br/>
						Reseller: '.$reseller->acc_name.'
						<br/>
						<br/>
						Thanks,<br>
						Telebox';
            $this->email->message($message);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->set_mailtype('html');
            $this->email->to('info@numbersiq.com');	
			$this->email->send();
			}
            $email_data = getEmailTemplate($result, 'Forget password');

            $this->email->subject($email_data->subject);
            $this->email->message($email_data->email_content);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->set_mailtype('html');
            $this->email->to($result->email);
			
			

            if ($this->email->send()) {
                $param = 'success';
                $JSON['success'] = 'We have sent an email to you with details on resetting your password.';
            } else {
                $param = 'error';
                $JSON['error'] = 'Email sending faild.';
            }
			
			#for log
			
					$owner = $result->parent_child;	
					
					$user_log_array = array(
						'date'			=> date('Y-m-d H:i:s'),
						'user_id'		=> $result->user_id,
						'owner'			=> $owner,
						'type_id'		=> 13,
						'display_state'	=> 2,
						'ip_addr'		=> user_ip()
					);
			save('audit_log', $user_log_array, $where = '');
			#end
			
            if ($this->input->is_ajax_request()) {
                $_POST = array_merge($_POST, $JSON);
                $JSON['notification'] = show_validation_errors_login();
                echo json_encode($JSON);
                exit;
            } else {
                redirect(ADMIN_DIR . 'login/forget_pass/?' . $param . '=' . $errorMsg);
            }
            /*-------- Email sending ------*/
        } else {
            $emailNA = 'Sorry, the information you have entered is incorrect.';
            if ($this->input->is_ajax_request()) {
                $JSON['error'] = $emailNA;
            } else {
                redirect(ADMIN_DIR . 'login/forget_pass/?message=' . $emailNA);
            }
            $_POST = array_merge($_POST, $JSON);
            $JSON['notification'] = show_validation_errors_login();
            echo json_encode($JSON);
            exit;
        }
    }

    function do_forget_user()
    {

        $captcha = getVar('g-recaptcha-response');
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->captach_secret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        /*-------- recaptcha Validation ------*/

        if($response['success'] == false){
            $captchaError = "reCaptcha not valid";
            $data['captchaError'] = $captchaError;
            if ($this->input->is_ajax_request()) {
                $JSON['error'] = $captchaError;
            } else {
                redirect(ADMIN_DIR . 'login/forget_user/?error=' . $captchaError);
            }

            $_POST = array_merge($_POST, $JSON);
            $JSON['notification'] = show_validation_errors_login();

            echo json_encode($JSON);
            exit;
        }
        /*-------- recaptcha Validation ------*/

        $result = $this->m_login->checkUser(" AND email='" . getVar('email') . "'");
        if ($result) {
            /*-------- Email sending ------*/
            $reset_key = $this->m_login->updateResetKey($result->user_id);
            $result->reset_key = $reset_key;
			
			if($result->status==2){
			$company = $this->m_login->account_name($result->parent_child);
			$reseller = $this->m_login->account_name($result->parent);
			
            $this->email->subject('Warning Admin Locked Account Attempting to login');
			$message = 'Hi,<br><br>
						The following admin locked account is attempting to unlock their account
						<br/>
						<br/>
						Username: '.$result->username.'
						<br/>
						Company: '.$company->acc_name.'
						<br/>
						Reseller: '.$reseller->acc_name.'
						<br/>
						<br/>
						Thanks,<br>
						Telebox';
            $this->email->message($message);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->set_mailtype('html');
            $this->email->to('info@numbersiq.com');	
			$this->email->send();
			}
			

            $email_data = getEmailTemplate($result, 'Forget user');

            $this->email->subject($email_data->subject);
            $this->email->message($email_data->email_content);

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->set_mailtype('html');
            $this->email->to($result->email);

            if ($this->email->send()) {
                $param = 'success';
                $JSON['success'] = 'We have sent an email to your username.';
            } else {
                $param = 'error';
                $JSON['error'] = 'Email sending faild.';
            }
			#for log
			
					$owner = $result->parent_child;	
					
					$user_log_array = array(
						'date'			=> date('Y-m-d H:i:s'),
						'user_id'		=> $result->user_id,
						'owner'			=> $owner,
						'type_id'		=> 14,
						'display_state'	=> 2,
						'ip_addr'		=> user_ip()
					);
			save('audit_log', $user_log_array, $where = '');
			#end
            if ($this->input->is_ajax_request()) {
                $_POST = array_merge($_POST, $JSON);
                $JSON['notification'] = show_validation_errors_login();
                echo json_encode($JSON);
                exit;
            } else {
                redirect(ADMIN_DIR . 'login/forget_user/?' . $param . '=' . $errorMsg);
            }
            /*-------- Email sending ------*/
        } else {
            $emailNA = 'Sorry, the information you have entered is incorrect.';
            if ($this->input->is_ajax_request()) {
                $JSON['error'] = $emailNA;
            } else {
                redirect(ADMIN_DIR . 'login/forget_pass/?message=' . $emailNA);
            }
            $_POST = array_merge($_POST, $JSON);
            $JSON['notification'] = show_validation_errors_login();
            echo json_encode($JSON);
            exit;
        }

    }

    public function do_login()
    {

        //$step = getVar('step');
        if (!$this->m_login->validate()) {
            $this->index();
        } else {
            $username = getVar('username');
            $password = encryptPassword(trim(getVar('password', TRUE, FALSE)));
            $result = $this->m_login->chklogin($username, $password);
            if ($result->status == 3) {
                $error = 'Sorry, your account has been locked, please click on forgotten password to reset your password.';
                    //email send
                    $email_data = getEmailTemplate( $result, 'Locked Account Attempted to Login');

                    $this->email->from(get_option('email_admin_noreply'), get_option('email_admin_from'));
                    $this->email->to('noc@telebox.co.uk');
                    $this->email->subject($email_data->subject);
                    $this->email->message($email_data->email_content);
                    $this->email->set_mailtype('html');
                    if ($this->email->send()) {
                        $msg = '';
                    } else {
                        $msg = 'Email sending faild.';
                    }

                redirect(ADMIN_DIR . 'login/?error=' . $error.$msg);
                exit;
            }else if(($result->status == 0 || $result->status==2) && (!empty($result))){

                $note = $result->username." - ".$result->acc_name." - Disabled User attempted to login";
                $user_log_array = array(
                    'date'			=> date('Y-m-d H:i:s'),
                    'note'			=> $note,
                    'type_id'		=> 53,
                    'display_state'	=> 2,
                    'ip_addr'		=> user_ip()
                );
                $this->db->insert('audit_log',$user_log_array);

                $email_data = getEmailTemplate( $result, 'Locked Account Attempted to Login');

                $error = 'Your account is disabled.';
               
                redirect(ADMIN_DIR . 'login/?error=' . $error);
				exit;
				
			}else if (count($result) > 0 && is_object($result)) {

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
                    'user_info' => $result
                ));

                if ($result->login_status == 0) {
                    $data['page'] = "step1";
                    $this->load->view(ADMIN_DIR . 'login', $data);
                } else {
					#log generate
					
					$owner = $result->parent_child;	
					
					$user_log_array = array(
						'date'			=> date('Y-m-d H:i:s'),
						'user_id'		=> $result->user_id,
						'owner'			=> $owner,
						'type_id'		=> 8,
						'display_state'	=> 2,
						'ip_addr'		=> user_ip()
					);
                    $this->db->insert('audit_log',$user_log_array);
					
                    $this->session->set_userdata('logged_in', 1);
                    #checking user redirection module
                    $check_selected_module = $result->default_page_id;

                    //die('Call');
                    if($check_selected_module > 0){

                        $user_redirected_page = getVal("modules","module"," WHERE id='".$check_selected_module."'");
                    }else{
                        $user_redirected_page = "dashboard";
                    }

                    if($result->login_status==2){
                        $this->db->query("UPDATE users SET `login_status` = '1' WHERE 1  AND user_id='".$result->user_id."' LIMIT 1");
                    }

                    /*if( $_SERVER['HTTP_HOST']!="localhost" && ROOT_DIR!="beta"){
                        ##redirect to beta
                        $redirect_beta = DOMAIN_URL."beta/".ADMIN_DIR.$user_redirected_page;
                        redirect($redirect_beta);
                    }else if($_SERVER['HTTP_HOST']!="localhost" && ROOT_DIR=="beta") {
                        ##redirect to beta
                        $redirect_live = str_replace("beta","",DOMAIN_URL).ADMIN_DIR.$user_redirected_page;
                        redirect($redirect_live);
                    }else {
                        redirect(ADMIN_DIR . $user_redirected_page);
                    }*/
                    redirect(ADMIN_DIR . $user_redirected_page);
                }

            } else {
                $users_info = getValues('users', 'user_id,first_name,email,parent,parent_child,u_type', "WHERE username='" . $username . "'");
                $user_id = $users_info->user_id;

                if ($user_id != "") {
                    $password_attempts = $this->session->userdata("password_attempts");

                    if ($password_attempts == "") {
                        $this->session->set_userdata("password_attempts", 1);
                    } else {
                        $this->session->set_userdata("password_attempts", ++$password_attempts);
                    }
                    $password_attempts = $this->session->userdata("password_attempts");
                    if ($password_attempts >= 3) {

                        $where = "AND user_id='" . $user_id . "'";
                        $this->db->query("UPDATE users SET `status` = '3' WHERE 1 " . $where);

                        //$reset_key = $this->m_login->updateResetKey($users_info->user_id);
                        $users_info->reset_key = $reset_key;
                        $users_info->timedate = date('H:i Y-m-d');

                        $email_data = getEmailTemplate($users_info, 'Account Locked Out');

                        $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
                        $this->email->to($users_info->email);
                        $this->email->subject($email_data->subject);
                        $this->email->message($email_data->email_content);
                        $this->email->set_mailtype('html');
                        if ($this->email->send()) {
                            $msg = '';
                        } else {
                            $msg = 'Email sending faild.';
                        }
						#for log
						
						$owner = $users_info->parent_child;	
						
						$user_log_array = array(
							'date'			=> date('Y-m-d H:i:s'),
							'user_id'		=> $users_info->user_id,
							'owner'			=> $owner,
							'type_id'		=> 12,
							'display_state'	=> 2,
							'note'	        => 'User '.$username.' locked their account due to too many invalid password attempts.',
							'ip_addr'		=> user_ip()
						);
                        $this->db->insert('audit_log',$user_log_array);
						#end
                        redirect(ADMIN_DIR . 'login/?error=Sorry, your account has been locked, please click on forgotten password to reset your password.<br>' . $msg);
                    } else {
                        redirect(ADMIN_DIR . 'login/?error=Incorrect User Name or Password !');
                    }

                } else {
                    redirect(ADMIN_DIR . 'login/?error=Incorrect User Name or Password !');
                }
            }

        }
    }

    function do_first_login()
    {

        $step = getVar('step');
        $user_id = sessionVar('user_id');

        if ($step == "step1" && $user_id != '') {
            if (!$this->m_login->step1_validate()) {
                $data['page'] = "step1";
                $this->load->view(ADMIN_DIR . 'login',$data);
            } else {
                save('users', array('security_id' => getVar('select_question'), 'security_ans' => getVar('answer')), "user_id = '" . dbEscape($user_id) . "'");
                $data['page'] = "step2";
                $this->load->view(ADMIN_DIR . 'login', $data);
            }
            
        } else if ($step == "step2" && $user_id != '') {
            if (!$this->m_login->step2_validate()) {
                $data['page'] = "step2";
                $this->load->view(ADMIN_DIR . 'login', $data);
            } else {
                $password = getVar('password');
                $encryptPassword = encryptPassword($password);
                $login_status = 1;
                save('users', array('password' => $encryptPassword, 'login_status' => $login_status), "user_id = '" . dbEscape($user_id) . "'");
                $this->session->set_userdata(array('login_status' => $login_status));
                redirect(ADMIN_DIR);
            }
        } else {
            redirect(ADMIN_DIR);
        }
    }

    function account_unlock()
    {
        $reset_key = getVar('key');
        sessionVar('temp_user_id');
        $data['step'] = $step = getVar('step');
        if ($step == "") {

            $data['step_heading'] = 'Account Unlock Step 1 of 3';
            //$check_time = $this->m_login->checkUser(" AND reset_key=" . $this->db->escape($reset_key) . " AND HOUR(TIMEDIFF(modified_date,NOW())) <= 1");


                $data['step'] = 'step1';
                //$data['recaptcha'] = recaptcha_get_html(M_login::PUBLICKEY, $error);
                $this->load->view(ADMIN_DIR . 'account_unlock', $data);

        } else if ($step == "step2" && sessionVar('temp_user_id')) {
            //$check_time = $this->m_login->checkUser(" AND reset_key=" . $this->db->escape($reset_key) . " AND HOUR(TIMEDIFF(modified_date,NOW())) <= 1");

                //$data['user_id'] = getVal('users', 'user_id', "WHERE reset_key=" . $this->db->escape($reset_key) . " ");
                $data['user_id'] =sessionVar('temp_user_id');
                $data['step'] = "step2";
                $data['step_heading'] = 'Password Reset Step 1 of 2';
                ////
                //$data['question'] = $this->m_login->getUserQuestion($data['user_id']);
                $this->load->view(ADMIN_DIR . 'account_unlock', $data);




        } else if ($step == "step3" && sessionVar('temp_step')==3) {
            $data['user_id'] =  sessionVar('temp_user_id');
            $data['step'] = "step3";
            $this->load->view(ADMIN_DIR . 'account_unlock', $data);
            //$this->load->view('admin/account_unlock', $data);
        }else{
            redirect(ADMIN_DIR . 'login');


        }


    }

    function do_account_unlock($step = '')
    {

        $reset_key = $this->input->get_post('key');
        $step = getVar('step');


        if ($step == "step1") {

            $captcha = getVar('g-recaptcha-response');
            $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->captach_secret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            /*-------- recaptcha Validation ------*/
            if($response['success'] == false){
                $robotError = "reCAPTCHA invalid";
                redirect(ADMIN_DIR . 'login/forget_pass/?error=' . $robotError);
                exit;
            }else {
                $mob_phone= getVar('mob_phone');
                if(substr($mob_phone,0,2)==4){
                    $mob_phone = "0".substr($mob_phone,2);
                }
                $user_id = getVal('users', 'user_id', " WHERE email='" . getVar('email') . "' AND REPLACE(mob_phone,' ','')='".$mob_phone."' AND mob_phone!=''");
                sessionVar('temp_user_id', $user_id);
                if ($user_id == "") {
                    $msg = "Sorry, we can not find your details, please contact support via email - help@telebox.co.uk or contact your account manager for assistance";
                    $user_log_array = array(
                        'date'			=> date('Y-m-d H:i:s'),
                        'user_id'		=> '',
                        'owner'			=> '',
                        'type_id'		=> 44,
                        'note'	        => 'No matching email and/or mobile number',
                        'ip_addr'		=> user_ip()
                    );
                    $this->db->insert('audit_log',$user_log_array);
                    redirect(ADMIN_DIR . 'login/forget_pass/?error=' . $msg);
                } else {
					$status = getVal('users', 'status', " WHERE email='" . getVar('email') . "'  AND REPLACE(mob_phone,' ','')='".$mob_phone."' AND mob_phone!=''");
                    $parent 		= getVal('users', 'parent', "WHERE user_id='" . $user_id . "'");
                    $parent_child   = getVal('users', 'parent_child', "WHERE user_id='" . $user_id . "'");
                    $username   = getVal('users', 'username', "WHERE user_id='" . $user_id . "'");
                    if($status==2){

					$this->email->subject('Warning Admin Locked Account Attempting to login');
					$message = 'Hi,<br><br>
								The following admin locked account is attempting to unlock their account
								<br/>
								<br/>
								Username: '.$result->username.'
								<br/>
								Company: '.$this->m_login->account_name($parent).'
								<br/>
								Reseller: '.$this->m_login->account_name($parent_child).'
								<br/>
								<br/>
								Thanks,<br>
								Telebox';
					$this->email->message($message);
		
					$this->email->from(get_option('email_admin'), get_option('email_admin_from'));
					$this->email->set_mailtype('html');
					$this->email->to('info@numbersiq.com');	
					$this->email->send();
					}

                    if($status==2 || $status==0){
                        redirect(ADMIN_DIR . 'login/forget_pass/?error=You can not reset your password because your account is disabled. Please contact your account manager or email help@telebox.co.uk');
                    }else {
                        $random_code = generate_random_code(4);
                        if (substr(getVar('mob_phone'), 0, 2) == 44) {
                            $mob_phone = getVar('mob_phone');
                        } else if (substr(getVar('mob_phone'), 0, 2) == 07) {
                            $mob_phone = "44" . substr(getVar('mob_phone'), 1);
                        } else {
                            $mob_phone = getVar('mob_phone');
                        }
                        $sms_body = "Your reset password code is " . $random_code;
                        $sms_result = sendSMS($sms_body,$mob_phone,SMS_UID,SMS_PWD,SMS_ORIG,44);

                        $note_log = $username . " was sent a password reset code sent via SMS";
                        $user_log_array = array(
                            'date' => date('Y-m-d H:i:s'),
                            'user_id' => $user_id,
                            'owner' => $parent_child,
                            'type_id' => 45,
                            'note' => $note_log,
                            'ip_addr' => user_ip()
                        );
                        $this->db->insert('audit_log', $user_log_array);

                        $mcdr_outbound = array(
                            'owner' => 50000,
                            'parent' => 50000,
                            'outbound_sender_id' => 'Telebox',
                            'destination_number' => $mob_phone,
                            'body' => $sms_body,
                            'sent_date' => date('Y-m-d H:i:s'),
                            'sent_method' => 2,
                            'sms_response' => $sms_result->result . ": " . $sms_result->resultText,
                            'acc_sms_id_charged' => '',
                        );
                        $this->db->insert('mcdr_outbound', $mcdr_outbound);
                        $this->db->query("UPDATE users SET reset_code = '" . $random_code . "' WHERE 1  AND user_id='" . $user_id . "'");
                        redirect(ADMIN_DIR . 'login/forget_pass/?step=step2');
                    }
                }
            }
        }
        if ($step == "step2" && sessionVar('temp_user_id')) {

            $data['user_id'] = sessionVar('temp_user_id');
            $data['answer'] = getVar('reset_code');
            $answer_status = getVal('users', 'user_id', "WHERE reset_code='" . getVar('reset_code') . "' AND user_id='" . $data['user_id'] . "' ");
            if ($answer_status == "") {
                $error = "Sorry, the code you entered was incorrect, please try resetting your password again, alternatively send an email to help@telebox.co.uk or contact your account manager.";
                $incorrect_code = (sessionVar('temp_incorrect_code')=="" ? 0 : sessionVar('temp_incorrect_code'));
                $incorrect_code++;
                sessionVar('temp_incorrect_code', $incorrect_code);

                if( sessionVar('temp_incorrect_code') >= 4){


                    $user_info_data = "SELECT users.first_name,users.surname,accunts.acc_name,users.parent_child,users.username,CONCAT('') as ip_address
                                    FROM users
                                    LEFT JOIN accunts ON (accunts.acc_id=users.parent_child)
                                    WHERE users.user_id='".sessionVar('temp_user_id')."'";
                    $users_info = $this->db->query($user_info_data)->row();
                    $users_info->ip_address = user_ip();
                    $user_log_array = array(
                        'date'			=> date('Y-m-d H:i:s'),
                        'user_id'		=> sessionVar('temp_user_id'),
                        'owner'			=> $users_info->parent_child,
                        'type_id'		=> 46,
                        'note'	        => 'User '.$users_info->username.' entered the reset code incorrectly 4+ times',
                        'ip_addr'		=> user_ip()
                    );
                    $this->db->insert('audit_log',$user_log_array);
                    $email_data = getEmailTemplate($users_info, 'Invalid Reset Code');

                    $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
                    $this->email->to("noc@telebox.co.uk");
                    $this->email->subject($email_data->subject);
                    $this->email->message($email_data->email_content);
                    $this->email->set_mailtype('html');
                    if ($this->email->send()) {
                        $msg = '';
                    } else {
                        $msg = 'Email sending faild.';
                    }
                    sessionVar('temp_incorrect_code','0');

                    redirect(ADMIN_DIR . 'login?error=Invalid reset code. You have attempted many times. Please try again or contact your account manager. '.$msg);
                }

                redirect(ADMIN_DIR . 'login/forget_pass/?error=' . $error . '&step=step2');

            } else {
                sessionVar('temp_step', 3);
                redirect(ADMIN_DIR . 'login/forget_pass/?acc_lock='.getVar('acc_lock').'&step=step3');
            }

        } else if ($step == "step3" && sessionVar('temp_user_id')!="" &&  sessionVar('temp_step')==3) {

            $password = $this->input->get_post('password');
            //$user_id = getVar('user_id');
            $data['user_id'] = $user_id = sessionVar('temp_user_id');
            if($user_id == ''){
                redirect(ADMIN_DIR);
                exit;
            }
            $value = encryptPassword($password);
            $where = " user_id = " . $user_id . "";
            $dbData = array(
                'password' => $value,
                'reset_key' => '',
                'reset_code' => '',
                'status' => 1,

            );

            save('users', $dbData, $where);
            $user_info_data = "SELECT users.first_name,users.username,users.password,users.parent_child,users.email
                                    FROM users
                                    WHERE users.user_id='".sessionVar('temp_user_id')."'";
            $users_info = $this->db->query($user_info_data)->row();


            $user_log_array = array(
                'date'			=> date('Y-m-d H:i:s'),
                'user_id'		=> sessionVar('temp_user_id'),
                'owner'			=> $users_info->parent_child,
                'type_id'		=> 47,
                'note'	        => 'User '.$users_info->username.' successfully changed their password.',
                'ip_addr'		=> user_ip()
            );
            $this->db->insert('audit_log',$user_log_array);
            $users_info->password = $password;

            $email_data = getEmailTemplate($users_info, 'New Password Reset');

            $this->email->from(get_option('email_admin'), get_option('email_admin_from'));
            $this->email->to($users_info->email);
            $this->email->subject($email_data->subject);
            $this->email->message($email_data->email_content);
            $this->email->set_mailtype('html');
            if ($this->email->send()) {
                $msg = '';
            } else {
                $msg = 'Email sending faild.';
            }

            sessionVar('password_attempts','0');
            sessionVar('temp_user_id','');
            sessionVar('temp_step','');
            redirect(ADMIN_DIR.'login?success=Your password has been updated, please login. '.$msg);
        }else{

            redirect(ADMIN_DIR . 'login?error=Invalid step');
        }

    }

    public function logout()
    {

        $this->session->sess_destroy();
        $this->session->set_userdata(array(
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
            'switch_customer'=>''

			
        ));


        $this->index();
    }

    public function update_pass()
    {
        $user_id = $this->session->userdata('tgm_user_id');
        $old_password = encryptPassword(getVar('old_password',TRUE, FALSE));
        $password = encryptPassword(getVar('password',TRUE, FALSE));
        //todo:: _users
        $sql = "SELECT * FROM users WHERE user_id={$user_id} AND `password`='{$old_password}'";
        $rs = $this->db->query($sql);
        if ($rs->num_rows() > 0) {
            $update_sql = "UPDATE users SET `password` = '{$password}' WHERE user_id = '{$user_id}'";
            $this->db->query($update_sql);
            echo 'Successfully Changed New Password';
        } else {
            echo 'Your old password is wrong...';
        }
    }


}