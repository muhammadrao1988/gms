<?php


date_default_timezone_set("Asia/Karachi");

$system_path = 'system';
if (realpath($system_path) !== FALSE)
{
    $system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
 $system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path))
{
   // exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}
define('BASEPATH', str_replace("\\", "/", $system_path));
include dirname(__FILE__) ."/../application/config/database.php";

$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


  $sql = "SELECT
  acc.branch_id,
  acc.`machine_member_id`,
  inv.`acc_id`,
  acc.`acc_name`,
  acc.`acc_date`,
  acc.discount,
  acc.discount_value,
  a_type.`monthly_charges`,
  FLOOR(
    DATEDIFF(CURDATE(), MAX(inv.fees_month)) / 30
  ) * a_type.`monthly_charges` AS Amount,
  MAX(inv.`fees_month`) AS last_paid,
  DATE_FORMAT(MAX(inv.`fees_month`), '%M %Y') AS last_paid_month,
  FLOOR(
    DATEDIFF(CURDATE(), MAX(inv.fees_month)) / 30
  ) AS fees_month,
  DATE_FORMAT(
    acc.`invoice_generate_date`,
    '%d'
  ) AS day_invoice,
  inv.`id`
FROM
  invoices inv
  JOIN accounts acc
    ON (inv.`acc_id` = acc.`acc_id`)
  JOIN acc_types a_type
    ON (
      a_type.`acc_type_ID` = acc.`acc_types`
    )
WHERE 1

  AND FIND_IN_SET('1', inv.`type`)
GROUP BY inv.acc_id
HAVING last_paid < DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$sql_result = $mysqli->query($sql) or die($mysqli->error);


if ($sql_result->num_rows) {
    while ($row = $sql_result->fetch_object()) {
        $total_month = $row->fees_month;
        $last_paid =  $row->last_paid;
        $general_date = date('Y-m-d', strtotime($last_paid . ' +30 days'));
        $discount = $row->discount;
        $discount_value = ($row->discount_value=="" ? 0 : $row->discount_value);
        $monthly_charges = $row->monthly_charges;


        if($discount==1){ //percent

            $fees_per_month = ceil(($monthly_charges/100)*$discount_value);
            $fees_per_month = $monthly_charges - $fees_per_month;
            $membership_fee = "Membership fee = ".$monthly_charges.". By default membership discount = " . $discount_value . "%. After discount Membership fee =  " . $fees_per_month;
        }else if($discount==2){//rupess

            $fees_per_month = $monthly_charges - $discount_value;
            $membership_fee = "After ".$discount_value."rs discount Membership fee =  ".$fees_per_month;
        }else {

            $fees_per_month = $monthly_charges;
            $membership_fee = "Generated invoice for membership fee. Membership fee = ".$fees_per_month;
        }
        $fee_invoice_template_array[0]['description'] = $membership_fee;




        for($i=0;$i<$total_month;$i++){
            $from = $general_date;
            $general_date = date('Y-m-d', strtotime($from . ' +30 days'));
            $to = $general_date;


            $fee_invoice_template_array[0]['type'] = 1;
            $fee_invoice_template_array[0]['amount'] = $fees_per_month;
            $fee_invoice_template_array[0]['duration'] = $from . "|" . $to;


            $total_array = array();
            $total_array['subtotal'] = $fees_per_month;
            $total_array['discount'] = 0;
            $total_array['received_amount'] = 0;
            $total_array['total'] = $fees_per_month;
            $total_array['remaining_amount'] = $fees_per_month;

            $account_details = array('fee_invoice' => $fee_invoice_template_array, 'total' => $total_array);


            $insert = "INSERT INTO `invoices`
            (

             `acc_id`,
             `machine_member_id`,
             `state`,
             `subtotal`,
             `discount`,
             `received_amount`,
             `remaining_amount`,
             `amount`,
             `description`,
             `fees_datetime`,
             `fees_month`,
             `type`,
             `amount_details`,
             `status`,
             `branch_id`,
             `created_by`,
              `from_date`,
             `to_date`)
VALUES ('".$row->acc_id."',
        '$row->machine_member_id',
        '4',
        '$fees_per_month',
        '0',
        '0',
        '$fees_per_month',
        '$fees_per_month',
        'Auto generated monthly invoice',
        '$from',
        '$from',
        '1',
        '".json_encode($account_details)."',
        '2',
        '$row->branch_id',
        '999',
        '$from',
        '$to')";
           // echo $insert;
          //  echo "<br>";
            $mysqli->query($insert) or  die($mysqli->error);

        }

    }
}

//////////////////////////////Subscription////////////////////////