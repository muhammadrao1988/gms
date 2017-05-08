<?php

class grid
{

    var $DB;
    var $currentController;
    var $currentMethod;

    var $title = '';
    var $query = '';
    var $url = '';
    var $limit = 25;

    var $id_field = '';

    var $adjacents = 3;
    var $module_uri = 2;

    var $form_buttons = array();
    var $grid_buttons = array();

    var $actionColumn = TRUE;
    var $is_front = FALSE;
    var $sorting = TRUE;
    var $total_table = '';


    var $selectAllCheckbox = TRUE;
    var $serial = FALSE;

    var $search_box = FALSE;
    var $advance_search_html = '';
    var $notification_message = '';
    var $record_not_found = 'Record not found...';

    var $show_paging_bar = TRUE;
    var $show_title_td = FALSE;
    var $show_total_row = FALSE;

    var $image_path = ASSETS_URL;
    var $phpthumb_str = '&w=70&h=70&f=png';

    var $custom_func = array();
    var $center_fields = array();
    var $right_fields = array();
    var $image_fields = array();
    var $hide_fields = array();
    var $custom_col_name_fields = array();
    var $search_fields_html = array();


    var $show_entries = array('25' => '25', '50' => '50', '100' => '100', '500' => '500', '1000' => '1000', '2000' => '2000', 'all' => 'All');

    var $total_record = 0;
    var $total_pages;

    var $get_search_column = 'search';
    var $get_limit_column = 'limit';
    var $get_page_column = 'page';
    var $form_type = 'form-inline';

    var $get_order_column = 'order_by';
    var $order_column = '';

    var $get_order = 'order';
    var $order = 'DESC';

    var $css_class = '';

    var $grid_start = '<div class="widget">';
    var $grid_end = '</div>';
    var $load_other_db = '';
    var $table_class = '';


    public $form_method = 'GET';
    private $order_by = '';
    private $start_limit = 0;
    private $fields = array();
    private $result_array = array();
    private $init = false;
    private $QUERY_STRING;


    function __construct()
    {

        $this->image_path = ASSETS_URL . "images/";
        $CI =& get_instance();

        $this->currentController = $CI->router->fetch_class();
        $this->currentMethod = $CI->router->fetch_method();

        //$CI->load->database('racing', TRUE);
        $CI->load->database('default', TRUE);

        $this->DB = $CI->db;


        if (empty($this->url)) {
            //$this->url = site_url($site_url) . '/';
            $this->url = current_url() . '/';
        }
        $this->QUERY_STRING = str_replace('token=1', '', $CI->input->server('QUERY_STRING'));
        // $this->url .= '?token=1' . (!empty($this->QUERY_STRING) ? '&'.$this->QUERY_STRING :'');

        $this->notification_message = show_validation_errors();

        /*if (!empty($this->id_field) && $this->order_column == '') {
            $this->order_by = $this->id_field . ' ' . $this->order;
        }*/

    }


    /**
     * @return string
     */
    function pagingQuery()
    {

        if ($this->load_other_db != "") {
            $CI =& get_instance();
            $racing = $CI->load->database('racing', TRUE);
            $this->DB = $racing;

        }


        $this->init = true;
        if (strpos($this->query, 'SQL_CALC_FOUND_ROWS') !== 7) {
            $this->query = "SELECT SQL_CALC_FOUND_ROWS" . substr($this->query, 6);
        }

        /**
         * ***********************************************************************************
         * Start
         * Ordering
         * ***********************************************************************************
         */

        if (getVar($this->get_order_column)) {
            $this->order_column = getVar($this->get_order_column);
        }
        if (getVar($this->get_order)) {
            $this->order = getVar($this->get_order);
        }

        if ($this->order_column != '' && $this->order != '') {
            $this->order_by = " ORDER BY `" . $this->order_column . '` ' . $this->order;
        }
        if (strtoupper($this->order) == 'DESC') {
            $this->order = 'ASC';
        } else {
            $this->order = 'DESC';
        }
        /**
         * ***********************************************************************************
         * Start
         * Page and page limit
         * ***********************************************************************************
         */
        $page = getVar($this->get_page_column);
        if ($page) {
            $this->start_limit = ($page - 1) * (getVar($this->get_limit_column) != "" ? getVar($this->get_limit_column) : $this->limit); //first item to display on this page

        }
        /*---------------------------------LIMIT----------------------------------------*/
        if (getVar($this->get_limit_column)) {
            $this->limit = (getVar($this->get_limit_column));
        }

        if ($this->limit != 'all') {
            $this->query .= " {$this->order_by} LIMIT {$this->start_limit}, {$this->limit}";
        }


        $result = $this->DB->query($this->query);
        $list_fields = $result->list_fields();

        $this->result_array = $result->result_array();

        foreach ($list_fields as $field) {
            array_push($this->fields, $field);
        }

        $this->total_record = $this->DB->query("SELECT FOUND_ROWS() as total")->row()->total;

        $this->total_pages = ceil($this->total_record / $this->limit);

        if ($this->load_other_db != "") {
            $CI =& get_instance();
            $default = $CI->load->database('default', TRUE);
            $this->DB = $default;

        }


        return $this->query;
    }

    private function init()
    {
        if (!$this->init) {
            $this->pagingQuery();
        }
    }

    public function showGrid()
    {

        $grid = '<form id="validate" action="' . $this->url . '" method="' . $this->form_method . '" enctype="multipart/form-data" class="grid_form ' . $this->form_type . '">';
        $grid .= (!empty($this->notification_message) ? $this->notification_message : '');
        $grid .= $this->gridHeader();
        $grid .= $this->getAdvanceSearch();
        $grid .= '<div class="table-overflow"><table class="xgrid table table-bordered table-checks table-striped ' . $this->table_class . '">';
        $grid .= $this->getTHead();
        $grid .= $this->getTBody();
        $grid .= '</table></div>';
        if ($this->show_paging_bar) {
            $grid .= $this->getTFoot();
        }
        $grid .= '</form>';


        return $grid;
    }

    /**
     * @return string
     */
    function gridHeader()
    {
        $this->init();
        ob_start();
        if (!empty($this->title)) {
            echo '<div class="navbar-inner"><h6>' . $this->title . '</h6></div>';
        }
        if (count($this->form_buttons) > 0) {
            echo get_form_actions($this->form_buttons, $this->module_uri);
        }

        return ob_get_clean();
    }


    function getAdvanceSearch()
    {
        if (!empty($this->advance_search_html)) {
            return $this->advance_search_html;
        }
    }

    function getTHead()
    {

        $this->init();
        ob_start();
        echo '<thead>';
        if ($this->search_box) {
            if ($this->selectAllCheckbox) {
                echo '<th>&nbsp;</th>';
            }

            $query_col_str = ',' . substr($this->query, (stripos($this->query, 'SQL_CALC_FOUND_ROWS') + 28), (stripos($this->query, 'FROM') - 35));
            $query_col_str = preg_replace('/\,\s(.*)? as\s/', '', $query_col_str);

            $search_request = getVar($this->get_search_column);
            foreach ($this->fields as $i => $field) {

                if ($this->serial && $i == 0) {
                    echo '<th>&nbsp;</th>';
                } else if (!in_array($field, $this->hide_fields)) {
                    $alias = '';
                    preg_match('/\,\s(.*)?\.' . $field . '\b/', $query_col_str, $table_alias);

                    $alias = preg_replace('/(.*)?\(/', '', (!empty($table_alias[1]) ? $table_alias[1] . ':' : ''));

                    if (array_key_exists($field, $this->search_fields_html) !== FALSE) {
                        echo '<th>' . $this->search_fields_html[$field] . '</th>';
                    } else {
                        $name_field = trim($alias) . trim($field);

                        if(isset($search_request[$name_field])){

                            $search_value = $search_request[$name_field];
                        }else{

                            $search_value = "";
                        }

                        echo '<th><input class="form-control" type="text" name="' . $this->get_search_column . '[' .$name_field . ']" id="' . $this->get_search_column . '_' . $field . '" value="' . $search_value . '"></th>';
                    }
                }
            }

            echo '<th align="left"><input type="submit" class="btn btn-green"  value="Search"></th>';
        }

        if ($this->show_title_td) {
            echo $this->show_title_td;
        }

        echo '<tr>';
        if ($this->serial) {
            echo '<th>S.No</th>';
        }
        if ($this->selectAllCheckbox) {
            echo '<th align="center" style="text-align:center;"><input type="checkbox" name="checkRow" id="checkRow" class="styled"/></th>';
        }
        $colspan ="";
        foreach ($this->fields as $key => $field) {
            if ($key == 0 && $this->id_field == '') {
                $this->id_field = $field;
            }
            if (!in_array($field, $this->hide_fields)) {
                $_order = '';
                if ($this->sorting) {
                    $_order = $this->url_sanitize($this->url, $this->get_order_column) . '&' . $this->get_order_column . '=' . $field;
                    $_order = $this->url_sanitize($_order, $this->get_order) . '&' . $this->get_order . '=' . (($field == getVar('order_by')) ? $this->order : 'DESC');

                    $_sorting = (getVar($this->get_order_column) == $field) ? 'sorting_' . strtolower(getVar($this->get_order)) : 'sorting';
                    $_sorting_link_s = '<a href="' . $_order . '">';
                    $_sorting_link_e = '</a>';
                }

                if ((count($this->grid_buttons) == 0 || !$this->actionColumn) && count($this->fields) == ($key + 1)) {
                    $colspan = ' colspan="2" ';
                }

                if (array_key_exists($field, $this->custom_col_name_fields)) {
                    $field = $this->custom_col_name_fields[$field];
                }

                echo '<th ' . $colspan . ' class="' . $_sorting . '">' . $_sorting_link_s . ucwords(str_replace('_', ' ', $field)) . $_sorting_link_e . '</th>';
            }
        }
        if (count($this->grid_buttons) > 0 && $this->actionColumn) {
            echo '<th id="g_act">Actions</th>';
        }
        echo '</thead>';

        return ob_get_clean();
    }


    function getTBody()
    {
        $this->init();
        ob_start();

        echo '<tbody>';

        if ($this->total_record > 0) {
            $i = 0;

            foreach ($this->result_array as $column => $row) {

                $i++;
                if ($i % 2 == 0) {
                    $color_td = "odd";
                } else {
                    $color_td = "even";
                }

                echo '<tr class="grid_row ' . $color_td . '">';
                if ($this->serial) {
                    echo '<td align="center">' . ((getVar($this->get_page_column) > 0) ? ($i + ((getVar($this->get_page_column) - 1) * $this->limit)) : $i) . '</td>';
                }

                if ($this->selectAllCheckbox) {
                    echo '<td align="center"><input type="checkbox" id="check_' . $column . '" class="styled chk_box check_' . $column . '" name="ids[]" value="' . $row[$this->id_field] . '"/></td>';
                }
                $k = 0;
                foreach ($row as $field_name => $val) {
                    $k++;
                    $colspan = '';
                    if ((count($this->grid_buttons) == 0 || !$this->actionColumn) && count($this->fields) == ($k)) {
                        $colspan = ' colspan="2" ';
                    }

                    if (!in_array($field_name, $this->hide_fields)) {
                        if (in_array($field_name, $this->image_fields)) {
                            if (!empty($val) || file_exists($this->image_path . $val)) {
                                $file = $this->image_path . $val;
                            } else {
                                $file = ASSETS_URL . "images/na.gif";
                            }
                            echo '<td ' . $colspan . ' valign="middle" align="center"><img src="' . PHPTHUMB_URL . $file . "&" . $this->phpthumb_str . '" alt="' . $val . '"></td>';
                        } else if (in_array($field_name, $this->center_fields)) {
                            echo '<td ' . $colspan . ' align="center" class="align-center" valign="middle">' . $val . '</td>';
                        } else if (in_array($field_name, $this->right_fields)) {


                            #custom by muhammad

                            if (array_key_exists($field_name, $this->custom_func)) {


                                $val = call_user_func($this->custom_func[$field_name], $val, $row, $this->selected);


                            }

                            echo '<td ' . $colspan . ' class="text-right">' . $val . '</td>';
                        } else {

                            #general custom func
                            if (array_key_exists($field_name, $this->custom_func)) {


                                #general condition
                                $val = call_user_func($this->custom_func[$field_name], array($val, $row), $row, $this->selected);
                            }
                            echo '<td ' . $colspan . ' valign="middle">' . $val . '</td>';
                        }
                    }
                }
                if (count($this->grid_buttons) > 0 && $this->actionColumn) {
                    echo '<td valign="middle" align="center"> ' . get_grid_actions($row, $this->id_field, $this->grid_buttons, $this->module_uri) . '</td>';
                }
                echo '</tr>';
            }

            if ($this->show_total_row) {
                echo $this->show_total_row;
            }


        } else {
            echo '<td colspan="' . (count($this->fields) + 1) . '" valign="middle" >' . $this->record_not_found . '</td>';
        }

        echo '</tbody>';

        return ob_get_clean();
    }

    function getTFoot()
    {
        $this->init();
        ob_start();

        ?>
        <div class="row-fluid">
            <div class="span6" style="float:left">
                <?php echo $this->showPaging(); ?>
            </div>
            <div class="span6" style="float:right">
                <div class="dataTables_info" id="hidden-table-info_info">
                    Showing <?php echo $this->start_limit + 1; ?>
                    to <?php echo ($this->total_record > $this->limit) ? $this->limit : $this->total_record; ?>
                    of <?php echo $this->total_record; ?> entries
                    <div id="data-table_length" class="dataTables_length" style="display:none;">
                        <label><span>Show entries:</span>
                            <select class="" id="rec_limit" name="<?php echo $this->get_limit_column; ?>"
                                    onchange="$(this).parents('.grid_form').submit();">
                                <?php echo selectBox($this->show_entries, getVar($this->get_limit_column)); ?>
                            </select>
                        </label></div>
                </div>
            </div>


        </div>


        <?php
        return ob_get_clean();
    }

    /**
     * @return string
     */


    function showPaging()
    {

        $this->init();

        $adjacents = $this->adjacents;
        //$targetpage = str_replace(array('?', '//?', '/?'), '/?', $this->url);
        $targetpage = $this->url;
        $page = getVar($this->get_page_column);
        if ($page == 0) $page = 1;
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($this->total_record / $this->limit);
        $lpm1 = $lastpage - 1;


        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= ' <div class="dataTables_paginate paging_bootstrap pagination"><ul>';
            //previous button
            if ($page > 1)
                $pagination .= "<li><a href='" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=1'>&laquo; First</a></li><li><a href=\"" . $this->url_sanitize($this->url, 'page') . "page=$prev\">&laquo; Previous</a></li>";
            else
                $pagination .= "<li><a href='" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=1'>&laquo; First</a></li><li class=\"disabled\"><a href='javascript:;' style='cursor:default;'>&laquo; Previous</a></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<li class=\"active\"><a href='javascript:;' style='cursor:default;'>$counter</a></li>";
                    } else {
                        $pagination .= '<li><a href="' . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . '=' . $counter . '">' . $counter . '</a></li>';
                    }
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><a href='javascript:;' style='cursor:default;'>$counter</a></li>";
                        else {
                            $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$counter\">$counter</a></li>";
                        }
                    }
                    $pagination .= "<li><a hrfe='javascript:;' style='cursor:default;'>...</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lastpage\">$lastpage</a></li>";
                } //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=1\">1</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=2\">2</a></li>";
                    $pagination .= "<li><a hrfe='javascript:;' style='cursor:default;'>...</a></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><a href='javascript:;' style='cursor:default;'>$counter</a></li>";
                        else {
                            $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$counter\">$counter</a></li>";
                        }
                    }
                    $pagination .= "<li><a hrfe='javascript:;' style='cursor:default;'>.   ..</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lastpage\">$lastpage</a></li>";
                } else {
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=1\">1</a></li>";
                    $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=2\">2</a></li>";
                    $pagination .= "<li><a hrfe='javascript:;' style='cursor:default;'>...</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class=\"active\"><a href='javascript:;' style='cursor:default;'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href=\"" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$counter\">$counter</a></li>";
                    }
                }
            }
            //next button
            if ($page < $counter - 1) {
                $aa = $this->url_sanitize($this->url, $this->get_page_column);
                $pagination .= "<li><a href=\"$aa&page=$next\">Next &raquo;</a><li><a href='" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lastpage'>Last &raquo;</a></li>";
            } else {
                $pagination .= "<li class=\"disabled\"><a href='javascript:;' style='cursor:default;'>Next &raquo;</a></li><li><a href='" . $this->url_sanitize($this->url, $this->get_page_column) . '&' . $this->get_page_column . "=$lastpage'>Last &raquo;</a></li>";
            }
            $pagination .= "</ul></div>";

        }
        return $pagination;
    }

    function url_sanitize($url, $add_arg = '')
    {
        $url = preg_replace("/" . $add_arg . "\=\w+/", '', $url);
        $url_sanitize = preg_replace("/\&{1,}+/", '', $url);
        if (strpos($url_sanitize, '?') === false) {
            $url_sanitize = str_replace($this->url, $this->url . '?', $url_sanitize);
            $url_sanitize = str_replace('?&', '?', $url_sanitize);

        }

        return $url = str_replace('&&&', '&', str_replace("?&", "?", str_replace("&&", "&", $url)));

    }

    function querystring($strQS, $arRemove, $arAdd = NULL)
    {
        parse_str($strQS, $arQS);
        $arQS = array_diff_key($arQS, array_flip($arRemove));
        //$arQS = $arQS + $arAdd;
        return http_build_query($arQS);
    }


}
