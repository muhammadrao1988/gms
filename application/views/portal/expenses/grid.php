<?php
include dirname(__FILE__) . "/../includes/head.php";
include dirname(__FILE__) . "/../includes/header.php";
include dirname(__FILE__) . "/../includes/left_side_bar.php";
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/style_new.css') ; ?>">
<?php

?>

<section id="main-content" class="inner-main-pages">
    <section class="wrapper">
        <!--mini statistics start-->
        <div class="row">
            <div class="col-lg-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url(ADMIN_DIR . "expenses"); ?>>">Expenses</a></li>
                    <li class="active">Expenses</li>
                </ul>
                <!--breadcrumbs end -->
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="notification"></div>
        <div class="row-sep"></div>
        <div class="row page-top-btn">

            <div class="clearfix">&nbsp;</div>

                <div class="custom-date" style=" border-bottom: 1px solid #d6d6d6; padding: 0 15px;">
                    <form   onsubmit="return validate_calender();">
                        <div class="form-group" style="margin-top:15px;">
                            <div class="col-md-4">
                                <div class="input-group input-large">
                                    <span class="input-group-addon">From</span>
                                    <input type="text" class="form-control datepicker-format" id="date_range11" style="padding-bottom: 0; padding-top: 0" name="date_range"
                                           value="<?php echo (getVar('date_range')=="" ? date('01-m-Y') : getVar('date_range')); ?>">
                                    <span class="input-group-addon">To</span>
                                    <input type="text" class="form-control datepicker-format" id="date_range211" style="padding-bottom: 0; padding-top: 0" name="date_range2"
                                           value="<?php echo (getVar('date_range2')=="" ? date('d-m-Y') : getVar('date_range2')); ?>">
                                </div>

                            </div>
                            <input type="submit" value="Search" class="btn btn-theme"/>
                            &nbsp;&nbsp;
                            <input type="reset" value="Clear" onclick="clear_calender_form();" class="btn btn-theme"/>

                        </div>
                        <div class="alert alert-block alert-danger fade in" id="error_calender" style="display:none;">
                            <!--<button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="fa fa-times"></i>
                            </button>-->
                            Please Select Date Range
                        </div>
                    </form>
                    <table class="xgrid table table-bordered table-checks table-striped">


                        <tr>
                            <?php
                            if(getVar('date_range')!=""){
                                $from_date = date('dM Y',strtotime(getVar('date_range')));
                                $to_date = date('dM Y',strtotime(getVar('date_range2')));

                            }else{
                                $from_date = date('01M Y');
                                $to_date = date('dM Y');

                            }
                            ?>

                            <th class="text-center">Expenses From <?php echo $from_date ?> To <?php echo $to_date?> </th>


                        </tr>


                        <tr class="grid_row even">

                            <td class="text-center" id="tot_num">Total Expense: <strong><?php echo number_format($summary_total); ?></strong></td>


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
        <div class="clearfix">&nbsp;</div>
        <!-- page top buttons -->
        <?php $chart_total = $chart_total; ?>

        <div class="clearfix">&nbsp;</div>
        <div class="row page-table">
            <a href="<?php echo site_url(ADMIN_DIR."expenses/form") ?>" class="btn btn-download" style="margin-left: 15px">Add New Expense</a>
            <div class="clearfix"></div>
                <?php
                $grid = new grid();
                $grid->query = $query;
                //$grid->title = $this->module_title .' - List';
                $grid->limit = 25;
                $grid->search_box = false;
                $grid->selectAllCheckbox = false;
                $grid->order_column = 'expense_date';
                $grid->order = 'DESC';
                $grid->hide_fields = array('id','total_amount_summary');
                $grid->custom_col_name_fields = array();
                $grid->search_fields_html = array('status' => $status, );
                $grid->form_buttons = array('');
                $grid->custom_func = array('expense_date'=>'grid_dateFormat');
                $grid->url = '?' . $_SERVER['QUERY_STRING'];
                //$grid->grid_buttons = array('edit', 'delete', 'status','send_new_password');
                $grid->grid_buttons = array('edit', 'delete');
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

    <script type="text/javascript">

        (function(){
            var t;
            function size(animate){
                if (animate == undefined){
                    animate = false;
                }
                clearTimeout(t);
                t = setTimeout(function(){
                    $("canvas").each(function(i,el){
                        $(el).attr({
                            "width":$(el).parent().width(),
                            "height":$(el).parent().outerHeight()
                        });
                    });
                    redraw(animate);
                    var m = 0;
                    $(".chartJS").height("");
                    $(".chartJS").each(function(i,el){ m = Math.max(m,$(el).height()); });
                    $(".chartJS").height(m);
                }, 30);
            }
            $(window).on('resize', function(){ size(false); });


            function redraw(animation){
                var options = {};
                if (!animation){
                    options.animation = false;
                } else {
                    options.animation = true;
                }
                var line_chart_options = {
                    scaleGridLineColor : "rgba(0,0,0,.05)",
                    responsive: true
                };

                var barChartExpense = {
                    labels : <?php echo json_encode($report_days); ?>,
                    datasets : [

                        {
                            label: "Expense",
                            fillColor: "rgba(151,187,205,0.5)",
                            strokeColor: "rgba(151,187,205,0.8)",
                            highlightFill: "rgba(151,187,205,0.75)",
                            highlightStroke: "rgba(151,187,205,1)",
                            showTooltip: true,
                            customTooltips: true,
                            tooltipTemplate: "<%= value %>%",
                            data :
                                [<?php echo  implode(",",$total_amount ) ?>]

                        }


                    ]

                }


                /***********************Expense Tab*********************************/
                var ctx5 = $("#bar-chart-expense").get(0).getContext("2d");

                var myLineChart5 = new Chart(ctx5).Bar(barChartExpense);

                $('#tab_call').on('shown.bs.tab', function (e) {

                    myLineChart1 = new Chart(ctx1).Bar(barChartCalls,line_chart_options);
                    var legendHolder = document.createElement('div');
                    legendHolder.innerHTML = myLineChart1.generateLegend();

                    document.getElementById('legend').appendChild(legendHolder.firstChild);

                });




            }




            size(true);

        }());
    $(document).ready(function () {

    });
</script>

<!-- Content -->
  