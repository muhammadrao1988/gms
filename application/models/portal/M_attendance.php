<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_attendance extends CI_Model
{
    var $table = 'attendance';
    var $id_field = 'id';

    function __construct()
    {
        parent::__construct();
        if (empty($this->table)) {
            $this->table = getUri(2);
        }
    }
    function validate_id_validate()
    {

        $this->form_validation->set_rules('acc_id', 'Member ID', 'callback_account_exist');
        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }
    function validate()
    {
        $this->form_validation->set_rules('acc_id', 'Member ID', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function generateSubscriptionInvoices($branch_id){
        $subscription = "SELECT
                          att.id AS id,
                          sub.charges,
                          ac.branch_id,
                          ac.acc_id,
                          ac.machine_member_id,
                          ac.acc_name,
                          act.`Name`,
                        
                           MAX(att_sub.`datetime`) AS last_attendance,
                          sub.period,
                          sub.`period` - FLOOR(
                            DATEDIFF(CURDATE(), MAX(att_sub.`datetime`))
                          ) AS subscription_status
                        
                        
                        FROM
                          attendance AS att
                          INNER JOIN accounts AS ac
                            ON (ac.`acc_id` = att.`acc_id`)
                          INNER JOIN acc_types AS act
                            ON (
                              act.`acc_type_ID` = ac.`acc_types`
                            )
                          INNER JOIN subscriptions AS sub
                            ON (sub.`id` = ac.`subscription_id`)
                            LEFT JOIN attendance AS att_sub
                            ON (ac.`acc_id` = att_sub.`acc_id`)
                            WHERE ac.branch_id = '".$branch_id."'
                          GROUP BY ac.acc_id
                          HAVING subscription_status <=0";
        $subscription_result = $this->db->query($subscription);
        $subscription_result_numrow = $subscription_result->num_rows();
        if ($subscription_result_numrow) {
            foreach ($subscription_result->result() as $row_subscription ) {
                $lastAttendance = $row_subscription->last_attendance;
                $acc_id = $row_subscription->acc_id;

                //check already created or not
                $check_invoice_created = "SELECT id FROM invoices WHERE FIND_IN_SET(3,`type`) AND state=4 AND fees_month='" . date('Y-m-d', strtotime($lastAttendance)) . "' AND acc_id='" . $acc_id . "'";
                $invoice_query = $this->db->query($check_invoice_created);
                $numrow_invoice_created = $invoice_query->num_rows();


                if ($numrow_invoice_created) {
                    //do nothing

                } else {

                    $fee_invoice_template_array[0]['type'] = 3;
                    $fee_invoice_template_array[0]['amount'] = $row_subscription->charges;
                    $fee_invoice_template_array[0]['duration'] = $lastAttendance;
                    $fee_invoice_template_array[0]['description'] = "Subscription expired on " . $lastAttendance . ". Invoice generated on the following date " . date('d-M-Y');

                    $total_array = array();
                    $total_array['subtotal'] = $row_subscription->charges;
                    $total_array['discount'] = 0;
                    $total_array['received_amount'] = 0;
                    $total_array['total'] = $row_subscription->charges;
                    $total_array['remaining_amount'] = $row_subscription->charges;

                    $account_details = array('other_invoice' => $fee_invoice_template_array, 'total' => $total_array);

                        $insert = "INSERT INTO `invoices`
                                    (`acc_id`,
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
                            VALUES ('" . $row_subscription->acc_id . "',
                                    '$row_subscription->machine_member_id',
                                    '4',
                                    '$row_subscription->charges',
                                    '0',
                                    '0',
                                    '$row_subscription->charges',
                                    '$row_subscription->charges',
                                    'Auto generated subscription invoice. Subscription expired on $lastAttendance.',
                                    '" . date('Y-m-d H:i:s') . "',
                                    '$lastAttendance',
                                    '3',
                                    '" . json_encode($account_details) . "',
                                    '2',
                                    '$row_subscription->branch_id',
                                    '999',
                                    '$lastAttendance',
                                    '$lastAttendance')";
                   // echo $insert;
                   // echo "<br>";
                    $this->db->query($insert);

                }


            }
        }

    }

}

/* End of file m_events.php */
/* Location: ./application/models/m_events.php */