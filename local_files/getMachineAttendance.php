<?php
header("Access-Control-Allow-Origin: *");
define('ACCESS_DATABASE' , urldecode(__DIR__."/gmsDATA.mdb"));
try {
    $dbh = new PDO("odbc:DRIVER={Driver do Microsoft Access (*.mdb)}; DBQ=".ACCESS_DATABASE);
}
catch (PDOException $e) {
    $dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=".ACCESS_DATABASE);
}
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//$result = $dbh->query("select CHECKINOUT.* from CHECKINOUT where 1 AND CHECKTIME>#".date('Y-m-d')."# ORDER BY CHECKINOUT.CHECKTIME DESC");
$data = $dbh->query("SELECT * FROM CHECKINOUT WHERE IsProcess=0")->fetchAll();

foreach ($data as $row) {
    $dbh->query("UPDATE CHECKINOUT SET IsProcess=1 WHERE USERID=".$row['USERID']." ");
}
echo json_encode($data);

//echo  json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>