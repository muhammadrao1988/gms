<?php
header("Access-Control-Allow-Origin: *");
/**
 * Created by PhpStorm.
 * User: Saqib ALi Khan
 * Email: saqib12@yahoo.com
 * Date: 5/23/2017
 * Time: 9:02 AM
 */

define('ACCESS_DATABASE' , urldecode(__DIR__."/gmsDATA.mdb"));
define('BRANCH_ID' , "1");

$pdoConnection = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=".ACCESS_DATABASE."; Uid=Admin; Pwd=;");
$pdoConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$data = $pdoConnection->query("SELECT * FROM CHECKINOUT WHERE IsProcess=0")->fetchAll();
foreach ($data as $row) {
    $pdoConnection->query("UPDATE CHECKINOUT SET IsProcess=1 WHERE USERID=".$row['USERID']." ");
}
echo json_encode($data);
/*die('Script - End Successfully');*/
?>