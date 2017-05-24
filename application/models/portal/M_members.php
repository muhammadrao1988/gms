<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_members extends CI_Model
{
    var $table = 'accounts';
    var $id_field = 'acc_id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }

    function validate()
    {
        $this->form_validation->set_rules('acc_name', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('acc_tel', 'Mobile number', 'required');
        $this->form_validation->set_rules('acc_types', 'Member Type', 'required');
        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function getMachineUserId($id,$redirect_url)
    {
        /*$dbName = ACCESS_DATABASE;
        if (!file_exists($dbName)) {

            echo 'file not exist';
        }
        //$dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        try {
            $dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        }
        catch (PDOException $e) {
            $dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName;");
        }
        //$result = $dbh->query('SELECT * from CHECKINOUT where userid = 1');
        $result = $dbh->query("select USERINFO.USERID from USERINFO where Badgenumber = '".$id."'");
        $USERID = $result->fetch(PDO::FETCH_ASSOC);

        if($USERID['USERID']!=''){
            return $USERID['USERID'];
        }else{
            return 0;
        }*/
        ?>
        <script type="text/javascript">
            document.write('Redirecting....');
            setTimeout(function () {
                var client = new XMLHttpRequest();
                client.open('GET', '<?=USERID_DATA_URL;?>?member_id=<?php echo $id; ?>');
                client.onreadystatechange = function () {
                    //document.getElementById("att_data_html").innerHTML = client.responseText;
                    var json_string_value = client.responseText;
                    console.log(json_string_value);
                    if(json_string_value!='') {
                        var ajax = new XMLHttpRequest();
                        ajax.open('GET', '<?=base_url(ADMIN_DIR . '/members/insertuserid');?>?member_id=<?php echo $id; ?>&userID=' + json_string_value);
                        ajax.onreadystatechange = function () {
                            var json_string_valuedd = client.responseText;
                            console.log(json_string_valuedd);
                            window.location.href = "<?php echo $redirect_url;?>";
                        };
                        ajax.send();
                    }
                };
                client.send();
            },1000);
        </script>
        <?php
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */