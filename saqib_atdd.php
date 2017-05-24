<?php
/**
 * Created by PhpStorm.
 * User: Saqib ALi Khan
 * Email: saqib12@yahoo.com
 * Date: 5/23/2017
 * Time: 9:02 AM
 */

$url = "http://localhost:8011/Projects/gym_management_db/saqib_atd.php";

echo file_get_contents($url);


$ch = curl_init($url);

// Execute
curl_exec($ch);

// Check if any error occurred
if (!curl_errno($ch)) {
    $info = curl_getinfo($ch);
    echo 'Took ', $info['total_time'], ' seconds to send a request to ', $info['url'], "\n";
}

// Close handle
curl_close($ch);


?>