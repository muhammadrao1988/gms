<?php
/**
 *
 */

class record_view {

    var $DB;
    var $title = '';
    var $query = '';
    var $url = '';
    var $limit = 1;
    var $id_field = '';
    var $image_path = '';
    var $is_front = FALSE;
    var $css_class = '';
    var $grid_buttons = array('view', 'edit', 'status', 'delete','back');

    private $start_limit = 0;
    private $total_pages = 0;

    function __construct() {

        $CI =& get_instance();
        $CI->load->database();
        $this->DB = $CI->db;
    }


    function showView() {

        $CI =& get_instance();
        $result = $this->DB->query($this->query);
        $this->query;

        if (!$this->total_pages) {

            $counter = $this->DB->query(preg_replace("/category_id(.*)?\d\'/", ' 1 ', $this->query));
            $this->total_pages = $counter->num_rows();
        }

        $id_checkbox = FALSE;
        ob_start();
        ?>
    <div class="view-panel">
        <?php
        if ($this->title != '') {
            echo '<div class="panel_header">' . $this->title . '</div>';
        }
        ?>
        <form action="<?php echo DOMAIN_ACTION_URL . $CI->router->class;?>/" method="<?php echo $this->form_method;?>" enctype="multipart/form-data" class="grid_form">
            <table cellpadding="5" cellspacing="0" width="100%">

                <?php
                {

                    $s = -1;
                    foreach ($result->result_array() as $row) {
                        foreach ($row as $field => $val) {
                            $s++;
                            ?>
                            <tr class="grid_row"><?php
                                if ($key == 0 && $this->id_field == '') {
                                    $this->id_field = $field;
                                }
                                if ($this->id_field == $field && !$id_checkbox) {
                                    echo '<input style="display:none;" type="checkbox" name="ids[]" value="' . intval($val) . '" class="chk_box" checked>';
                                    $id_checkbox = TRUE;
                                }
                                ?>
                                <td class="td_title view_header" width="15%"><?php echo ucwords(str_replace('_', ' ', $field));?> :</td>
                                <td width="80%"><?php echo stripslashes(html_entity_decode($val));?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>

            </table>
        </form>
        <div class="paging">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="200">
                        <div class="paging_left">
                            <table>
                                <tr>
                                    <td>
                                        <img style="cursor: pointer;" onclick="window.location='<?php echo $this->url;?>/1'" src="<?php echo ADMIN_VIEWS_URL;?>images/page-first.gif"/>
                                    </td>
                                    <td>
                                        <img style="cursor: pointer;" onclick="window.location='<?php echo $this->url;?><?php echo ($CI->uri->segment(3) > 1) ? $CI->uri->segment(3) - 1 : $CI->uri->segment(3);?>'" src="<?php echo ADMIN_VIEWS_URL;?>images/page-prev.gif"/>
                                    </td>
                                    <td>|</td>
                                    <td>Page
                                        <input type="text" size="2" name="page" value="<?php echo ($CI->uri->segment(3) == '') ? 1 : $CI->uri->segment(3);?>">
                                    </td>
                                    <td>of <?php echo $this->total_pages;?></td>
                                    <td>
                                        <img style="cursor: pointer;" onclick="window.location='<?php echo $this->url;?><?php echo ($CI->uri->segment(3) < $this->total_pages) ? $CI->uri->segment(3) + 1 : $CI->uri->segment(3);?>'" src="<?php echo ADMIN_VIEWS_URL;?>images/page-next.gif"/>
                                    </td>
                                    <td>
                                        <img style="cursor: pointer;" onclick="window.location='<?php echo $this->url;?><?php echo $this->total_pages?>'" src="<?php echo ADMIN_VIEWS_URL;?>images/page-last.gif"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div class="paging_actions">
                            <table>
                                <tr>
                                    <td>
                                        <a href="javascript: void(0);"><img src="<?php echo ADMIN_VIEWS_URL;?>images/refresh.gif" align="left"/></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div class="paging_links">
                            <p align="center">
                            <table>
                                <tr>

                                    <td>
                                        <p align="center"><?//=$this->paging();?></p>
                                    </td>
                                </tr>
                            </table>
                            </p>
                        </div>
                    </td>
                    <td align="right">
                        <div class="paging_right">
                            <table>
                                <tr>
                                    <td width="" align="right">Topics <?php echo  ($this->start_limit + 1); ?>
                                        - <?php echo  ($this->limit > $this->total_pages) ? $this->total_pages : ($CI->uri->segment(3) * $this->limit); ?>
                                        of <?php echo  $this->total_pages; ?></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <?
        return $html = ob_get_clean();
    }
}