<?php
/**
 * @param $query
 * @param string $selected
 * @return string
 */

function selectBox($query, $selected = '')
{
    $CI = & get_instance();
    $CI->load->database();
    $options = '';

    if (is_array($query)) {
        $array = $query;
        if (count($array) > 0) {
            foreach ($array as $key => $val) {
                //$key = array_keys($row);

                if (is_array($selected)) {
                    $options .= '<option value="' . $key . '" ' . ((in_array($key, $selected)) ? 'selected' : '') . '>' . $val . '</option>';
                } else {
                    $options .= '<option value="' . $key . '" ' . (($key == $selected) ? 'selected' : '') . '>' . $val . '</option>';
                }
            }
        }
    } else {
        $result = $CI->db->query($query);

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $key = array_keys($row);

                if (is_array($selected)) {
                    $options .= '<option value="' . $row[$key[0]] . '" ' . ((in_array($row[$key[0]], $selected)) ? 'selected' : '') . '>' . $row[$key[1]] . '</option>';
                } else {
                    $options .= '<option value="' . $row[$key[0]] . '" ' . (($row[$key[0]] == $selected) ? 'selected' : '') . '>' . $row[$key[1]] . '</option>';
                }
            }
        }
    }
    return $options;
}

/**
 * @param $query
 * @param $name
 * @param string $checked
 * @param string $label_position
 * @param array $attrs
 * @param string $type
 * @return string
 */
function checkBox($query, $name, $checked = '', $label_position = 'right', $attrs = array(), $type = 'checkbox')
{
    $CI = & get_instance();
    $CI->load->database();


    $options = '';
    if (is_array($query)) {
        $array = $query;
        if (count($array) > 0) {
            foreach ($array as $key => $val) {

                if (is_array($checked)) {
                    $options .= '<li class="checkbox_li li_' . $key . '">' . (($label_position != 'right') ? $val : '');
                    $options .= '<input type="' . $type . '" value="' . $val . '" name="' . $name . '" ' . ((in_array($key, $checked)) ? 'checked' : '') . '> ';
                    $options .= (($label_position == 'right') ? $val : '') . "</li>";
                } else {
                    $options .= '<li class="checkbox_li li_' . $key . '">' . (($label_position != 'right') ? $val : '');
                    $options .= '<input type="' . $type . '" value="' . $val . '" name="' . $name . '" ' . (($key == $checked) ? 'checked' : '') . '> ';
                    $options .= (($label_position == 'right') ? $val : '') . "</li>";
                }
            }
        }
    } else {
        $result = $CI->db->query($query);
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $key = array_keys($row);

                if (is_array($checked)) {
                    $options .= '<li class="checkbox_li li_' . $row[$key[0]] . '">' . (($label_position != 'right') ? $row[$key[1]] : '');
                    $options .= '<input type="' . $type . '" value="' . $row[$key[0]] . '" name="' . $name . '" ' . ((in_array($row[$key[0]], $checked)) ? 'checked' : '') . '> ';
                    $options .= (($label_position == 'right') ? $row[$key[1]] : '') . "</li>";
                } else {
                    $options .= '<li class="checkbox_li li_' . $row[$key[0]] . '">' . (($label_position != 'right') ? $row[$key[1]] : '');
                    $options .= '<input type="' . $type . '" value="' . $row[$key[0]] . '" name="' . $name . '" ' . (($row[$key[0]] == $checked) ? 'checked' : '') . '> ';
                    $options .= (($label_position == 'right') ? $row[$key[1]] : '') . "</li>";
                }
            }
        }
    }
    return $options;
}

/**
 * @param $name
 * @param bool $xss_clean
 * @return string
 */

function getVar($name, $xss_clean = TRUE, $escape_sql = TRUE)
{
    $CI = & get_instance();
    if ($escape_sql) {
        return $CI->db->escape_str($CI->input->get_post($name, $xss_clean));
    } else {
        return $CI->input->get_post($name, $xss_clean);
    }
}

function getVarDB($name, $xss_clean = TRUE)
{
    $CI = & get_instance();
    return $CI->db->escape_str($CI->input->get_post($name, $xss_clean));
}

function dbEscape($string)
{
    $CI = & get_instance();
    return $CI->db->escape_str($string);
}


/**
 * @param $table
 * @param $column
 * @param string $where
 * @return mixed
 */
function getVal($table, $column, $where = '')
{
    $CI = & get_instance();
    $CI->load->database();
    $q = "SELECT `$column` FROM `$table` " . $where;
    return $CI->db->query($q)->row()->$column;

}

/**
 * @param $table
 * @param string $column
 * @param string $where
 * @return mixed
 */
function getValues($table, $column = '*', $where = '', $single = true)
{
    $CI = & get_instance();
    $CI->load->database();
    $RS = $CI->db->query("SELECT $column FROM `$table` " . $where);
    return (($single) ? $RS->row() : $RS->result());

}

/**
 * @deprecated
 * @param $name
 * @param bool $xss_clean
 * @return string
 */
function gerVar($name, $xss_clean = TRUE)
{
    return getVar($name, $xss_clean);
}

function encryptPassword($password)
{
    return md5( $password );
}

/**
 * @param $number
 * @return string
 */
function getUri($number)
{
    $CI = & get_instance();
    return $CI->uri->segment($number);
}


function cellNumber($cellnumber)
{
    if (!empty($cellnumber)) {
        $cellnumber = (substr($cellnumber, 0, 1) != '0' ? '0' . $cellnumber : $cellnumber);
    }
    return $cellnumber;
}

function removeZero($str)
{
    $str = (substr($str, 0, 1) === '0' ? substr($str, 1) : $str);
    return $str;
}


function replaceChar($string, $num = 3, $replacement = 'x')
{

    $newStr = '';
    $length = (strlen($string) - $num);
    for ($i = 1; $i <= $length; $i++) {
        $newStr .= $replacement;
    }
    return $newStr . substr($string, $length, $num);
}

/**
 * @param $page
 * @param $per_page
 * @return string
 */
function getLimit($page, $per_page)
{
    $offset = ($page > 0 ? $page : 0);
    return " LIMIT " . $offset . ", " . $per_page;
}

/**
 * @param $table
 * @param $data array() 'key' => 'value'
 * @param string $where (WHERE 1=1)
 * @return string insert_id | WHERE
 */
function save($table, $data, $where = '',$last_query='')
{
    $CI = & get_instance();
    $CI->load->database();

    if (empty($where)) {
        $SQL = $CI->db->insert_string($table, $data);

        $CI->db->query($SQL);
        if($last_query!=""){

            echo $CI->db->last_query();


        }
        return $CI->db->insert_id();
    } else {
        $SQL = $CI->db->update_string($table, $data, $where);
        $CI->db->query($SQL);
        return true;
    }
}


function getFindQuery($key = 'search')
{
    $CI = & get_instance();
    $search_q = '';
    $key_isset = $CI->input->get($key);
    if(isset($key_isset)) {
        foreach ($key_isset as $search_f => $search_v) {
            $search_arr = null;
            if (!empty($search_v)) {
                $search_arr = explode(':', $search_f);

                if (count($search_arr) >= 2) {
                    $s_coulum = (!empty($search_arr[0])) ? $search_arr[0] . '.' . $search_arr[1] : $search_arr[1];
                } elseif (count($search_arr) == 1) {
                    $s_coulum = $search_arr[0];
                }
                $search_v = (!is_numeric($search_v)) ? " LIKE '%" . $CI->db->escape_like_str($search_v) . "%' " : " = '" . dbEscape($search_v) . "'";
                //$search_v = (strtoupper($operator) == 'LIKE') ? "%$search_v%" : $search_v;
                $search_q .= " AND " . $s_coulum . $search_v;
            }
        }
    }
    return $search_q;
}


function fileDownload($file)
{


    $rs = get_file_info($file);
    $file_name = explode('/', $rs['name']);

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $type);
    header('Content-Disposition: attachment; filename=' . end($file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    // Send file headers
}

/**
 * @param $table
 * @param array $ignore
 * @return array
 */
function getDbArray($table, $ignore = array())
{
    $CI = & get_instance();
    $CI->load->database();

    $fields = $CI->db->field_data($table);

    $dbArray = array();
    foreach ($fields as $field) {
        if (!in_array($field->name, $ignore) && (isset($_REQUEST[$field->name]) || isset($_POST[$field->name]) || isset($_GET[$field->name]))) {
            if ($field->primary_key) {
                $dbArray['where'] = "`" . $field->name . "`= '" . getVar($field->name) . "'";
            } else {
                $dbArray['dbdata'][$field->name] = getVar($field->name, 1, 0);
            }
        }
    }

    return $dbArray;
}

function getActionsCheckBox($params, $selected)
{
    $CI = & get_instance();
    $CI->load->database();

    $component_id = $params['component_id'];
    $actions = explode(',', $params['actions']);

    if (count($actions) > 0 && !empty($actions[0])) {
        $userAction = explode(',', $params['user_actions']);
        $html .= '<br>' . nbs(6);
        foreach ($actions as $action) {
            $html .= '<input type="checkbox" ' . ((in_array($action, $userAction)) ? "checked" : "") . ' name="actions[' . $component_id . '][]" id="actions_' . $component_id . '" value="' . $action . '"> ';
            $html .= $action . nbs(3);
        }
        $html .= '<br><br>';
    }
    return $html;
}


function singleColArray($query, $column)
{
    $CI = & get_instance();
    $CI->load->database();

    $rows = $CI->db->query($query)->result();

    $rt_array = array();
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            array_push($rt_array, $row->$column);
        }
    }
    return $rt_array;
}

function array2url($array, $keyName)
{
    $url = '';
    foreach ($array as $key => $val) {
        $url .= (($key == 0) ? '' : '&') . $keyName . '=' . $val;
    }
    return $url;
}

function show_validation_errors()
{
    $error = getVar('error');
    $msg = getVar('msg');
    $success = getVar('success');
    $alert = getVar('alert');
    $info = getVar('info');

    $html = '';
    if (validation_errors() != '' || !(count($error) == 0 || $error == '')) {
        $errors = validation_errors() . (is_array($error) ? join('<br>', $error) : $error);
        $html .= '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>';
        $html .= $errors . '</div>';
    }
    if (!(count($msg) == 0 || $msg == '')) {
        $html .= '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>';
        $html .= (is_array($msg) ? join('<br>', $msg) : $msg) . '</div>';
    }
    if (!(count($success) == 0 || $success == '')) {
        $html .= '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>';
        $html .= (is_array($success) ? join('<br>', $success) : $success) . '</div>';
    }
    if (!(count($alert) == 0 || getVar('alert') == '')) {
        $html .= '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>';
        $html .= (is_array($alert) ? join('<br>', $alert) : $alert) . '</div>';
    }
    if (!(count($info) == 0 || $info == '')) {
        $html .= '<div class="alert alert-info "><button type="button" class="close" data-dismiss="alert">×</button>';
        $html .= (is_array($info) ? join('<br>', $info) : $info) . '</div>';

    }
    return $html;
}


function array2object($array)
{
    $object = new stdClass();
    foreach ($array as $key => $value) {
        $object->$key = $value;
    }
    return $object;
}

function get_enum_values($table, $field)
{
    $CI = & get_instance();
    $CI->load->database();
    $type = $CI->db->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->row()->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    foreach (explode(',', $matches[1]) as $value) {
        $enum[] = trim($value, "'");
    }
    return $enum;
}

/*function getOneRowInArray($array,$key)
{
    foreach ($array as $key => $val) {

    }
}*/

function friendly_url($friendly_url)
{

    return site_url($friendly_url . '.php');
}


function get_option($option)
{
    $CI = & get_instance();
    $CI->load->database();

    return $CI->db->query("SELECT option_value FROM `options` WHERE option_name=" . $CI->db->escape($option) . "")->row()->option_value;
}


function object2array($object)
{
    $return = NULL;
    if (is_array($object)) {
        foreach ($object as $key => $value)
            $return[$key] = object2array($value);
    } else {
        $var = get_object_vars($object);

        if ($var) {
            foreach ($var as $key => $value)
                $return[$key] = ($key && !$value) ? NULL : object2array($value);
        } else return $object;
    }

    return $return;
}


function getDays($date1, $date2)
{
    $ts1 = strtotime($date1);
    $ts2 = strtotime($date2);
    $secondsDifference = abs($ts2 - $ts1);
    return $days = floor($secondsDifference / (60 * 60 * 24));
}


function getModuleDetail()
{

    $CI =& get_instance();
    $CI->load->database();

    $module = $CI->uri->segment(2);
    $sql = "SELECT * FROM modules WHERE module=" . $CI->db->escape($module);
    return $CI->db->query($sql)->row();

}

function getUserActions($module = NULL)
{

    $CI =& get_instance();

    if (!$module) {
        $module = $CI->uri->segment(2);
    }
    $user_actions = $CI->session->userdata('actions');
    return $user_actions = array_unique(explode('|', str_replace(array('update'), array('edit'), $user_actions[$module])));
}

function multiFileArray($inputName)
{

    foreach ($_FILES[$inputName] as $key => $files) {
        for ($i = 0; $i < count($files); $i++) {
            $_FILES[$inputName . $i][$key] = $files[$i];
            if (!in_array($inputName . $i, $_MYFILES))
                $_MYFILES[] = $inputName . $i;
        }
    }
    unset($_FILES[$inputName]);
    return $_MYFILES;
}

if (!function_exists('array_trim')) {

    function array_trim(&$data)
    {

        foreach ($data as &$a) {
            $a = addslashes(trim($a));
        }
    }
}


function _lang($var)
{

    $CI =& get_instance();
    $CI->load->helper('language');

    $lang = $CI->session->userdata('lang');
    if(!$lang){ $lang = 'en'; $language = 'english';}

    //$language = getVal('languages', 'language', "WHERE iso_code='" . dbEscape($lang) . "'");

    $CI->lang->load($lang, strtolower($language));


    return $CI->lang->line($var);
}


function saveLang($table, $id, $lang, $langFields = array())
{
    $CI =& get_instance();
    if (!($lang == 'en' || empty($lang))) {
        $del_SQL = "DELETE FROM `translations` WHERE `table`='" . dbEscape($table) . "' AND pri_id='" . dbEscape($id) . "' AND lang='" . dbEscape($lang) . "'";
        $CI->db->query($del_SQL);
        foreach ($langFields as $field) {
            if (getVar($field, 1, 0) != '') {
                $data = array(
                    'lang' => $lang,
                    'table' => $table,
                    'pri_id' => $id,
                    'column' => $field,
                    'value' => addslashes(getVar($field, 0, 0))
                    //'value' => addslashes($_POST[$field])
                );

                save('translations', $data);
            }
        }
    }
}

function langRecord($table, $id, $lang, $langFields = array(), $rowData = array(), $is_object = true)
{
    $CI =& get_instance();
    if (!($lang == 'en' || empty($lang))) {
        $SQL = "SELECT * FROM `translations` WHERE `table`='" . dbEscape($table) . "' AND pri_id='" . dbEscape($id) . "' AND lang='" . dbEscape($lang) . "' AND `column` IN ('" . join("','", $langFields) . "')";
        $result = $CI->db->query($SQL);

        if ($result->num_rows > 0) {
            $result = $result->result();

            foreach ($langFields as $field) {
                if ($is_object) {
                    $rowData->{$field} = '';
                } else {
                    $rowData[$field] = '';
                }
            }
            foreach ($result as $row) {
                if ($is_object)
                    $rowData->{$row->column} = $row->value;
                else {
                    $rowData[$row->column] = $row->value;
                }
            }
        }
    }

    return $rowData;
}

function updateLangRecord($lang, $langFields, &$rowData)
{

    if (!($lang == 'en' || empty($lang))) {

        foreach ($langFields as $field) {
            unset($rowData[$field]);
        }
    }
}


function sanitize_url_param($param, $url = 'current')
{
    $param_sep = '?';
    if ($url == 'current') {
        $url = prep_url($_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
    }
    if(strpos($url, '?') !== FALSE){

        $param_sep = '&';
    }

    preg_match("/" . $param . "=(.*)\&?/i", $url, $matches);
    $new_URL = str_replace($matches[0], '', $url) . $param_sep;
    $new_URL = preg_replace("/\?(\&{1,})/", '?', $new_URL);

    return $new_URL;
}
function sessionVar($name, $val = '')
{
    $CI = & get_instance();
    if ($val != '') {
        $CI->session->set_userdata($name, $val);
    } else
        return $CI->session->userdata($name);
}
function show_validation_errors_login()
{
    $error = getVar('error');
    $msg = getVar('msg');
    $success = getVar('success');
    $alert = getVar('alert');
    $info = getVar('info');
    $pass_forget = getVar('message');


    $html = '';
    if (validation_errors() != '' || !(count($error) == 0 || $error == '')) {


        $errors = validation_errors() . (is_array($error) ? join('<br>', $error) : $error);
        $html .= '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>';
        $html .= $errors . '</div>';
    }
    if (!(count($pass_forget) == 0 || $pass_forget == '')) {

        $html .= '<div class="col-md-12">
                <section class="panel">
                    <div class="panel-body profile-information">

                       <div class="col-md-12">
                           <div class="profile-desk" style="border-right:0">
                               <h4 style="color:#616365">Incorrect Username and /or email</h4>
                               <p>
                                   ' . (is_array($pass_forget) ? $pass_forget : $pass_forget) . '<br>
								   Please try again, if you are still difficulties, please contact your account manager or email <a href="mailto:help@telebox.co.uk" style="text-decoration:underline;">help@telebox.co.uk</a>
                               </p>
                           </div>
                       </div>

                    </div>
                </section>
            </div>
			<script>
			$(".form-signin").hide();
			</script>';

    }


    if (!(count($msg) == 0 || $msg == '')) {

        $html .= '<div class="col-md-12">
                <section class="panel">
                    <div class="panel-body profile-information">
                       
                       <div class="col-md-12">
                           <div class="profile-desk" style="border-right:0">
                               <h1 style="color:#616365">Thank you...</h1>
                               <br>
                               <p>
                                   ' . (is_array($msg) ? $msg : $msg) . '<br><br>
								   If you do not receive an email, please contact your account manager or email the Telebox Team via <a href="mailto:help@telebox.co.uk" style="text-decoration:underline;">help@telebox.co.uk</a>
                               </p>
							   <br>
                               <a href="' . site_url(ADMIN_DIR) . '" class="btn btn-green" style="float:right;">Return to login</a>
                           </div>
                       </div>
                       
                    </div>
                </section>
            </div>
			<script>
			$(".form-signin").hide();
			</script>';

    }
    if (!(count($success) == 0 || $success == '')) {
        $html .= '<div class="col-md-12">
                <section class="panel">
                    <div class="panel-body profile-information">
                       
                       <div class="col-md-12">
                           <div class="profile-desk" style="border-right:0">
                               <h1 style="color:#616365">Thank you...</h1>
                               <br>
                               <p>
                                   ' . (is_array($success) ? $success : $success) . '<br><br>

                               </p>
							   <br>
                               <a href="' . site_url(ADMIN_DIR) . '" class="btn btn-red" style="float:right;">Click here to login</a>
                           </div>
                       </div>
                       
                    </div>
                </section>
            </div>
			<script>
			$(".form-signin").hide();
			</script>';

    }
    if (!(count($alert) == 0 || getVar('alert') == '')) {
        $html .= '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>';
        $html .= (is_array($alert) ? join('<br>', $alert) : $alert) . '</div>';
    }
    if (!(count($info) == 0 || $info == '')) {
        $html .= '<div class="col-md-12">
                <section class="panel">
                    <div class="panel-body profile-information">
                       
                       <div class="col-md-12">
                           <div class="profile-desk" style="border-right:0">
                               <h1 style="color:#616365">Oooopps...!</h1>
                               <br>
                               <p>
                                   ' . (is_array($info) ? $info : $info) . '<br><br>
								   If you do not receive an email, please contact your account manager or email the Telebox Team via <a href="mailto:help@telebox.co.uk" style="text-decoration:underline;">help@telebox.co.uk</a>
                               </p>
                              
                           </div>
                       </div>
                       
                    </div>
                </section>
            </div>
			<script>
			$(".form-signin").hide();
			</script>';


    }
    return $html;
}

function user_ip()
{
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}
function getEmailTemplate($var_data_query, $email_temp_name)
{
    $CI =& get_instance();
    $email_data = getValues('email_templates', '*', "WHERE `status`=1 AND LOWER(name) = '" . dbEscape(strtolower($email_temp_name)) . "'");

    if (is_object($var_data_query)) {
        $var_data_query = object2array($var_data_query);
    }

    if (!is_array($var_data_query)) {
        $var_data_query = $CI->db->query($var_data_query)->row_array();
    }
    $var_data_query['site_url'] = site_url();
    $var_data_query['current_url'] = current_url();
    $var_data_query['forget_password'] = site_url(ADMIN_DIR.'/login/forget_pass');
    $var_data_query['base_url'] = base_url();

    foreach ($var_data_query as $col => $val) {
        $email_data->email_content = str_replace('{' . $col . '}', $val, $email_data->email_content);
    }
    $email_data->email_content = str_replace('{current_year}', date('Y'), $email_data->email_content);

    return $email_data;

}

function emailFooter(){

    $email_footer = ' <div style="background:#393939;">
            <div style="font-size: 15px; color:#949494; padding:15px;">This email was sent to you as a registered user of <a href="/3" style="color:#949494">sample_project</a><br />
              If you need any assistance please contact your account manager, or email <a href="#" style="color:#949494">help@abc.com</a></div>
          </div>
          <div style="background:#393939; border-top:1px solid #949494">
            <div style="font-size: 15px; color:#fff; padding:15px;">Copyright &copy; '.date('Y').' <a href="#" style="color:#fff; text-decoration:none;">sample_project</a> All rights reserved</div>
          </div>
        </div>
        </div>
        </body>
        </html>';

    return $email_footer;
}

function getAttendenceMachine()
{
    $dbName = urldecode(ACCESS_DATABASE);

    if (!file_exists($dbName)) {

        echo 'file not exist';
    } else {

        //$dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        try {
            $dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        }
        catch (PDOException $e) {
            $dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName;");
        }
        //$result = $dbh->query('SELECT * from CHECKINOUT where userid = 1');
        $result = $dbh->query("select CHECKINOUT.* from CHECKINOUT where 1 AND CHECKTIME>#".date('Y-m-d')."# ORDER BY CHECKINOUT.CHECKTIME DESC");

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}
function getTotalfeesAmount($values)
{
    $acc_details = getVal('accounts','acc_date',' where acc_id = "'.$values[1]['acc_id'].'"');
    $now = time(); // or your date as well
    $your_date = strtotime(date('Y-m',strtotime($values[1]['fees_month'])).'-'.date('d',strtotime($acc_details)));
    $datediff = $now - $your_date;
    $total_month = floor($datediff / (60 * 60 * 24 * 30));
    if($total_month !='0' and $values[1]['status'] == '1'){
        return MONTHLY_FEES * floor($datediff / (60 * 60 * 24 * 30));
    }else{
        return $values[1]['amount'];
    }

}
function invoice_for($val){
    $CI = & get_instance();
    $sql = "SELECT GROUP_CONCAT(`name`) as types FROM invoice_types WHERE id IN (".(($val[0]=='')?0:$val[0]).")";
    return $CI->db->query($sql)->row()->types;
     //getVal('invoice_types','group_concat(name)',' where id in ('.(($val[0]=='')?0:$val[0]).')');
}

function  getPaymemntStatus($val){
    $now = time();
    $fees_date = $val[0];
    $your_date = strtotime(date('Y-m',strtotime($fees_date)).'-'.date('d',strtotime($val[1]['acc_date'])));
    $datediff = $now - $your_date;
    $status = getVal('invoices','status',' where acc_id = "'.$val[1]['acc_id'].'" and status = 1');
    $month = floor($datediff / (60 * 60 * 24 * 30));
    if($month !='0' and $status == '1'){
        $val[1]['invoices_id'] = getVal('invoices','id',' where acc_id = "'.$val[1]['acc_id'].'" and status = 1');
        $paybutton = '<span class="red"><b>Unpaid</b></span> <button class="btn btn-primary btn-sm payment_pop" type="button" href="javascript:void(0);" data-invoice="'.$val[1]['invoices_id'].'"><i class="fa fa-money"></i> Pay</button>';
        return $paybutton;
    }else{
        return '<span class="green"><b>PAID</b></span>';
    }
}

function getSubscriptionStatus($val){
    $now = time();
    $expire_date    = strtotime(date('Y-m-d',strtotime($val[1]['acc_date'].'+ '.$val[0])));
    $days_diffrence = $expire_date - $now;
    $days_left = floor($days_diffrence / (60 * 60 * 24));
    if($days_left <= 0){
        return '<span class="red"><b>Expired</b></span>';
    }else {
        return '<span class="green"><b>'.$days_left. ' Days Left</b></span>';
    }

}
function date_manual()
{
    if (getVar('date_frame') == 'Custom Dates' && getVar('range_type') == 'day') {
        $_GET['date_range'] = getVar('custom_date');
        $_GET['date_range2'] = getVar('custom_date');
    }
    if (getVar('date_frame') == 'Custom Dates' && getVar('range_type') == 'week') {
        if (getVar('week_picker') == '') {
            $_GET['date_range'] = getVar('custom_date');
            $date_second = DateTime::createFromFormat('d/m/Y', getVar('custom_date'));
            $_GET['date_range2'] = date('d/m/Y', strtotime($date_second->format('m/d/Y') . "+6 days"));
        } else {
            $range = explode(' to ', getVar('week_picker'));
            $_GET['date_range'] = $range[0];
            $_GET['date_range2'] = $range[1];
        }
    }
    if (getVar('date_frame') == 'Custom Dates' && getVar('range_type') == 'month') {
        if (getVar('month_picker') == '') {
            $_GET['date_range'] = getVar('custom_date');
            $range2 = DateTime::createFromFormat('d/m/Y', getVar('custom_date'));
            $_GET['date_range2'] = date('t/m/Y', strtotime($range2->format('m/d/Y')));
        } else {
            $month = explode(' ', getVar('month_picker'));
            $date = $month[0] . ' 1 ' . $month[1];
            $_GET['date_range'] = date('d/m/Y', strtotime($date));
            $_GET['date_range2'] = date('t/m/Y', strtotime($date));
        }
    }

}
function checkMonthlyFeesPaid($account_date,$last_fees_date){
    $now = time();
    $fees_date = $last_fees_date;
    $your_date = strtotime(date('Y-m',strtotime($fees_date)).'-'.date('d',strtotime($account_date)));
    $datediff = $now - $your_date;
    $month = floor($datediff / (60 * 60 * 24 * 30));
    return $month;

}