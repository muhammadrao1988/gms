<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css'); ?>">
<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "Invoices"); ?>">Fees Management</a></li>
                    <li class="active"><?php echo $title ?></li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <!-- page top buttons -->

        <div class="filter-section" style="padding-top: 10px">
            <div class="row">
                <!--<div class="pull-left" style="margin-left: 15px;">
                    <label for="" style="padding: 5px 0;"> Month: </label>
                </div>-->
                <div class="col-md-8">
                    <form class="form-inline" role="form" method="get"
                          action="<?= base_url(ADMIN_DIR . '/revenue'); ?>">
                        <div class="form-group col-md-8">
                            <label for="" class="pull-left  " style="padding: 5px 0;"> Date Range:&nbsp; </label>
                            <div class="input-group input-large" data-date="13-07-2013" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control datepicker-format" name="from"
                                       value="<?php echo getVar('from') ?>">
                                <span class="input-group-addon">To</span>
                                <input type="text" class="form-control datepicker-format" name="to"
                                       value="<?php echo getVar('to') ?>">
                            </div>

                        </div>
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-green">Search</button>
                            <a href="<?= base_url(ADMIN_DIR . '/revenue'); ?>" class="btn btn-black">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="xgrid table table-bordered table-checks table-striped">
                        <tr>
                            <?php
                            if (getVar('from') != "") {
                                $from_date = date('dM Y', strtotime(getVar('from')));
                                if (getVar('to') != "") {
                                    $to_date = date('dM Y', strtotime(getVar('to')));
                                } else {
                                    $to_date = date('dM Y');
                                }
                            } else {
                                $from_date = date('01M Y');
                                $to_date = date('dM Y');
                            }
                            ?>
                            <th class="text-center">Revenue From <?php echo $from_date ?>
                                To <?php echo $to_date ?> </th>
                        </tr>
                        <tr class="grid_row even">
                            <td class="text-center" id="tot_num">Total Revenue:
                                <strong><?php echo number_format($summary_total); ?></strong></td>
                        </tr>
                    </table>
                    <div class="clearfix">&nbsp;</div>
                    <div class="page-middle-btn">
                        <div class="col-sm-12">
                            <section class="panel">
                                <header class="panel-heading panel-heading-theme reports-chart-tabs">
                    <span class="tools pull-right" style="margin-top: -5px">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                         </span>
                                </header>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div id="expense" class="tab-pane active">
                                            <div class="chartJS" style="height: 265px;">
                                                <canvas id="bar-chart-expense" height="260" width="1603"
                                                        style="width: 100%; height: 260px;"></canvas>
                                                <div id="legend"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
            </div>
        </div>
        <div class="row page-table">

            <div class="clearfix"></div>
            <?php

            $search_ar = getVar('search');

            $grid = new grid();
            $grid->query = $query;
            //$grid->title = $this->module_title .' - List';
            $grid->limit = 25;
            $grid->search_box = false;
            $grid->selectAllCheckbox = false;
            $grid->order_column = 'id';
            $grid->hide_fields = array( 'status','account_id','amount');
            $grid->custom_func = array('invoice_for' => 'invoice_for');
            $grid->custom_col_name_fields = array('machine_member_id'=>'member_id','id'=>'invoice No.');
            //$grid->search_fields_html = array('user_login_status' => '', 'company' => $s_company, 'reseller' => $s_reseller, 'user_id' => $s_user_id, 'username' => $s_username, 'email' => $s_email);

            $grid->form_buttons = array('new', 'delete');
            $grid->url = '?' . $_SERVER['QUERY_STRING'];
            //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
            $grid->grid_buttons = array();
            echo $grid->showGrid();

            ?>
            <!--</div>-->
        </div>
        <!-- end data table -->


    </section>
</section>

<?php
include dirname(__FILE__) . "/../includes/footer.php";
include dirname(__FILE__) . "/../delete.php";
include dirname(__FILE__) . "/../status.php";
include dirname(__FILE__) . "/../charts_file/reports_chart_js.php";

?>
<!-- Content -->
<script type="text/javascript">

    (function () {
        var t;

        function size(animate) {
            if (animate == undefined) {
                animate = false;
            }
            clearTimeout(t);
            t = setTimeout(function () {
                $("canvas").each(function (i, el) {
                    $(el).attr({
                        "width": $(el).parent().width(),
                        "height": $(el).parent().outerHeight()
                    });
                });
                redraw(animate);
                var m = 0;
                $(".chartJS").height("");
                $(".chartJS").each(function (i, el) {
                    m = Math.max(m, $(el).height());
                });
                $(".chartJS").height(m);
            }, 30);
        }

        $(window).on('resize', function () {
            size(false);
        });


        function redraw(animation) {
            var options = {};
            if (!animation) {
                options.animation = false;
            } else {
                options.animation = true;
            }
            var line_chart_options = {
                scaleGridLineColor: "rgba(0,0,0,.05)",
                responsive: true
            };

            var barChartExpense = {
                labels: <?php echo json_encode($report_days); ?>,
                datasets: [

                    {
                        label: "Revenue",
                        fillColor: "rgba(151,187,205,0.5)",
                        strokeColor: "rgba(151,187,205,0.8)",
                        highlightFill: "rgba(151,187,205,0.75)",
                        highlightStroke: "rgba(151,187,205,1)",
                        showTooltip: true,
                        customTooltips: true,
                        tooltipTemplate: "<%= value %>%",
                        data: [<?php echo implode(",", $total_amount) ?>]

                    }


                ]

            }


            /***********************Expense Tab*********************************/
            var ctx5 = $("#bar-chart-expense").get(0).getContext("2d");

            var myLineChart5 = new Chart(ctx5).Bar(barChartExpense);

            $('#tab_call').on('shown.bs.tab', function (e) {

                myLineChart1 = new Chart(ctx1).Bar(barChartCalls, line_chart_options);
                var legendHolder = document.createElement('div');
                legendHolder.innerHTML = myLineChart1.generateLegend();

                document.getElementById('legend').appendChild(legendHolder.firstChild);

            });
        }

        size(true);
    }());
</script>
  