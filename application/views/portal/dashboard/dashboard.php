<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
/*if($_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR) || $_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR)."/login/?error=Incorrect%20User%20Name%20or%20Password%20!"
	|| $_SERVER["HTTP_REFERER"]==site_url(ADMIN_DIR)."/logout"){
echo '<div class="loader-dashboard"></div>';
	}*/

include dirname(__FILE__) . "/../includes/left_side_bar.php";
$branch_id =  getVal("users","branch_id"," WHERE user_id='".$this->session->userdata('user_info')->user_id."'");
$month_member = $this->db->query("SELECT COUNT(acc_id) AS month_member FROM accounts WHERE MONTH(accounts.`acc_date`) = MONTH(CURRENT_DATE()) and `status` = 1 AND branch_id = '".$branch_id."'")->row()->month_member;
$total_member= $this->db->query("SELECT COUNT(acc_id) AS total_member FROM accounts WHERE `status` = 1 AND branch_id = '".$branch_id."'")->row()->total_member;
$date_range = date('Y-m-01 00:00:00');
$date_range2 = date('Y-m-d 23:59:59');
$filter = " AND expense_date BETWEEN '".$date_range."' AND '".$date_range2."' AND  branch_id = '".$branch_id."' ";
$expense_current_month = $this->db->query("SELECT SUM(total_amount) AS total_expense FROM expenses WHERE 1".$filter)->row()->total_expense;
$expense_today = $this->db->query("SELECT SUM(total_amount) AS today_expense FROM expenses WHERE 1 AND DATE(expense_date)='".date('Y-m-d')."' AND  branch_id = '".$branch_id."'")->row()->today_expense;

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
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon orange"><i class="fa fa-group"></i></span>
                    <div class="mini-stat-info">
                        <span><?php echo number_format($total_member) ; ?></span>
                        Total Members
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon tar"><i class="fa fa-user"></i></span>
                    <div class="mini-stat-info">
                        <span><?php echo number_format($month_member) ; ?></span>
                        Members Register This Month
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon pink"><i class="fa fa-file-text"></i></span>
                    <div class="mini-stat-info">
                        <span>34,320</span>
                        Monthly Fees Report
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon green"><i class="fa fa-file-o"></i></span>
                    <div class="mini-stat-info">
                        <span>32720</span>
                        Daily Fees Report
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon red-b"><i class="fa fa-thumbs-down"></i></span>
                    <div class="mini-stat-info">
                        <span>32720</span>
                        Unpaid Members
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR."expenses") ?>">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon blue-b"><i class="fa fa-tags"></i></span>
                    <div class="mini-stat-info">
                        <span><?php echo number_format($expense_current_month=="" ? 0 : $expense_current_month);?></span>
                        Expense This Month
                    </div>
                </div>
                </a>

            </div>
            <div class="col-md-3">
                <a href="<?php echo site_url(ADMIN_DIR."expenses") ?>">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon lightblue-b"><i class="fa fa-tag"></i></span>
                    <div class="mini-stat-info">
                        <span><?php echo number_format($expense_today=="" ? 0 : $expense_today);?></span>
                        Today Expense
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-3">
                <div class="mini-stat clearfix">
                    <span class="mini-stat-icon yellow-b"><i class="fa fa-tasks"></i></span>
                    <div class="mini-stat-info">
                        <span>32720</span>
                        Invoices
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="clearfix">&nbsp;</div>
</section>
<?php
include dirname(__FILE__) . "/../includes/footer.php";
?>


