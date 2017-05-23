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
    function getMachineUserId($id){
        $dbName = urldecode(ACCESS_DATABASE);

        if (!file_exists($dbName)) {

            echo 'file not exist';
        }
<<<<<<< HEAD
        //$dbh = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName;");
        try {
            $dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");


        }
        catch (PDOException $e) {
            $dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName;");
        }

=======
        //$dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        try {
            $dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=$dbName;");
        }
        catch (PDOException $e) {
            $dbh = new PDO("odbc:Driver={Microsoft Access Driver (.mdb, .accdb)}; DBQ=$dbName;");
        }
>>>>>>> 2b15803409e6b0073a319921b4b29859bc62dc6e
        //$result = $dbh->query('SELECT * from CHECKINOUT where userid = 1');

        $result = $dbh->query("select USERINFO.USERID from USERINFO where Badgenumber = '".$id."'");


        $USERID = $result->fetch(PDO::FETCH_ASSOC);
<<<<<<< HEAD

=======
        echo '<pre>';print_r($USERID );echo '</pre>';
        die('Call');
>>>>>>> 2b15803409e6b0073a319921b4b29859bc62dc6e
        return $USERID['USERID'];
    }
}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */