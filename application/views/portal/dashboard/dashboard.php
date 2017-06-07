<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
/*if($_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR) || $_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR)."/login/?error=Incorrect%20User%20Name%20or%20Password%20!"
	|| $_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR)."/logout"){
echo '<div class="loader-dashboard"></div>';
	}*/

include dirname(__FILE__) . "/../includes/left_side_bar.php";
$branch_id = getVal("users", "branch_id", " WHERE user_id='" . $this->session->userdata('user_info')->user_id . "'");

$total_member = $this->db->query("SELECT COUNT(acc_id) AS total_member FROM accounts WHERE `status` = 1 AND branch_id = '" . $branch_id . "'")->row()->total_member;
$date_range = date('Y-m-01 00:00:00');
$date_range2 = date('Y-m-d 23:59:59');
$filter = " AND expense_date BETWEEN '" . $date_range . "' AND '" . $date_range2 . "' AND  branch_id = '" . $branch_id . "' ";
$expense_current_month = $this->db->query("SELECT SUM(total_amount) AS total_expense FROM expenses WHERE 1" . $filter)->row()->total_expense;
$expense_today = $this->db->query("SELECT SUM(total_amount) AS today_expense FROM expenses WHERE 1 AND DATE(expense_date)='" . date('Y-m-d') . "' AND  branch_id = '" . $branch_id . "'")->row()->today_expense;
$revenue_month = $this->db->query("Select SUM(received_amount) as month_revenue from invoices where 1 AND branch_id = '" . $branch_id . "' AND fees_datetime BETWEEN '" . $date_range . "' AND '" . $date_range2 . "'")->row()->month_revenue;
$revenue_today = $this->db->query("Select SUM(received_amount) as month_revenue from invoices where 1 AND branch_id = '" . $branch_id . "' AND DATE(fees_datetime) = '" . date('Y-m-d') . "'")->row()->month_revenue;
$total_invoices = $this->db->query("SELECT COUNT(id) AS total_invoices FROM invoices WHERE branch_id = '" . $branch_id . "'")->row()->total_invoices;
$monthly_invoices = $this->db->query("SELECT 
                         
                          MAX(inv.`fees_month`) AS last_paid,
                         
                        inv.`id` 
                        FROM
                          invoices inv 
                          JOIN accounts acc 
                            ON (inv.`acc_id` = acc.`acc_id`) 
                          JOIN acc_types a_type 
                            ON (
                              a_type.`acc_type_ID` = acc.`acc_types`
                            ) 
                        WHERE acc.branch_id =  '".$branch_id."' 
                          AND acc.machine_member_id != '' 
                          AND FIND_IN_SET( '1',inv.`type`) 
                          AND inv.`state` IN (1, 2) 
                          
                        GROUP BY inv.acc_id 
                        HAVING last_paid < DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->num_rows();
$partial_paid_invoice = $this->db->query("SELECT COUNT(id) AS partial_paid FROM invoices WHERE branch_id = '" . $branch_id . "' AND `state`=2")->row()->partial_paid;



?>
<section id="main-content" class="dashboard-main-content">
    <section class="wrapper dashboard-main-wrapper">
        <!--Breadcrumbs-->
        <div class="row breadcrumb">
            Dashboard
            <div class="pull-right" style="color: #fff">
                <?php
                date_default_timezone_set('Asia/Karachi');
                echo date('H:i l jS M Y');
                ?>
            </div>
        </div>
        <!--breadcrumbs end -->
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "members") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon orange"><i class="fa fa-group"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($total_member); ?></span>
                            Total Members
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "invoices") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon pink"><i class="fa fa-file-text"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($total_invoices == "" ? 0 : $total_invoices); ?></span>
                            Total Invoices
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "invoices/monthlyInvoice") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon red-b"><i class="fa fa-file-o"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($monthly_invoices == "" ? 0 : $monthly_invoices); ?></span>
                            Monthly Fee Due Invoices
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "invoices?search[id]=&search[ic:state]=2") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon red-b"><i class="fa fa-file-o"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($partial_paid_invoice); ?></span>
                            Partial Paid Invoices
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <!--<div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon red-b"><i class="fa fa-thumbs-down"></i></span>
                    <div class="mini-stat-info">
                        <span>32720</span>
                        Unpaid Members
                    </div>
                </div>
            </div>-->
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "expenses") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon blue-b"><i class="fa fa-tags"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($expense_current_month == "" ? 0 : $expense_current_month); ?></span>
                            Expense This Month
                        </div>
                    </div>
                </a>

            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "expenses") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon lightblue-b"><i class="fa fa-tag"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($expense_today == "" ? 0 : $expense_today); ?></span>
                            Today Expense
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "revenue") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon green"><i class="fa fa-money"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($revenue_month == "" ? 0 : $revenue_month); ?></span>
                            Revenue This Month
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR . "revenue?from=" . date('d-m-Y') . "&to=") ?>">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon yellow-b"><i class="fa fa-money"></i></span>
                        <div class="mini-stat-info">
                            <span><?php echo number_format($revenue_today == "" ? 0 : $revenue_today); ?></span>
                            Today Revenue
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <div class="clearfix">&nbsp;</div>
</section>
<?php
include dirname(__FILE__) . "/../includes/footer.php";
?>


