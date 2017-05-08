<?php
/**

 */

function randomcode()
{
    $charsets = array();
    $charsets[] = array("count" => 5, "char" => "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
    $charsets[] = array("count" => 5, "char" => "0123456789");
    $code = array();
    foreach ($charsets as $charset) {
        for ($i = 0; $i < $charset["count"]; $i++) {
            $code[] = $charset["char"][rand(0, strlen($charset["char"]) - 1)];
        }
    }
    shuffle($code);
    return implode("", $code);
}

function verifyEMail($email)
{
    if (preg_match('/\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i', $email)) {
        return true;
    }
    return false;
}



function active_link($link, $class = 'active')
{
    if (strpos($_SERVER['SCRIPT_FILENAME'], $link) === FALSE) {
        return false;
    }
    return $class;
}

function encryptPass($passwd)
{
    return md5( $passwd);
}
