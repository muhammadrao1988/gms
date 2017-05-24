<?php
/**
 * Created by PhpStorm.
 * User: Saqib ALi Khan
 * Email: saqib12@yahoo.com
 * Date: 5/23/2017
 * Time: 9:02 AM
 */


define('ACCESS_DATABASE' , urldecode("E:/xampp/htdocs/Projects/gym_management_db/gmsDATA.mdb"));
//define('ACCESS_DATABASE' , urldecode("http://localhost/Projects/gym_management_db/gmsDATA.mdb"));
define('BRANCH_ID' , "1");

/*$file = "http://localhost/Projects/gym_management/saqib_atd.php";
echo file_get_contents($file);


die('Call');*/

$pdoConnection = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=".ACCESS_DATABASE."; Uid=Admin; Pwd=;");
$pdoConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$data = $pdoConnection->query("SELECT * FROM CHECKINOUT WHERE IsProcess=0")->fetchAll();
echo json_encode($data);
//echo '<pre>';print_r($data );echo '</pre>';
/*foreach ($data as $row) {
    echo '<pre>';print_r($row );echo '</pre>';
    //$pdoConnection->query("UPDATE CHECKINOUT SET IsProcess=1 WHERE USERID=".$row['USERID']." ");
    //AND CHECKTYPE=".$row['CHECKTYPE']." AND sn=".$row['sn']."
}

die('Script - End Successfully');*/
?>