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
$result = $dbh->query("select USERINFO.USERID from USERINFO where Badgenumber = '".$_GET['member_id']."'");
$USERID = $result->fetch(PDO::FETCH_ASSOC);

echo $USERID['USERID'];

?>