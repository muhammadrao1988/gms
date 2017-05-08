<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class multilevels
{

    var $query;
    var $selected;
    var $type = 'menu';
    var $parent = 0;
    var $url;
    var $level_spacing = 15;

    var $id_Column = 'id';
    var $parent_Column = 'parent_id';
    var $title_Column = 'title';
    var $link_Column = 'controller';

    var $call_func;

    var $title_position = 'right';
    var $attrs;

    var $parent_li_start = "<li class='{active_class}'>\n  <a href='{href}'><i class='fa tb-icon {icon}'></i>{link_text}{has_child}</a>\n";
    var $parent_li_end = '</li>';

    var $child_ul_start = "<ul class='sub'>\n";
    var $child_ul_end = "</ul>\n";
    var $child_li_start = "<li class='sub-menu {active_class}'>\n  <a class='expand {active_class} dcjq-parent' href='{href}'><i class='fa tb-icon {icon}'></i>{link_text}{has_child}</a>\n";
    var $child_li_end = '</li>';

    var $active_class = '';
    var $active_link = '';

    var $has_child_html = '';

    var $search = array();
    var $replace = array();

    public function __construct()
    {
        //$this->url = site_url(getUri(1) . "/" . getUri(2));
    }


    function build()
    {
        $CI =& get_instance();
        $CI->load->database();
        $result = $CI->db->query($this->query);

        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        foreach ($result->result_array() as $items) {
            $menu['items'][$items[$this->id_Column]] = $items;
            $menu['parents'][$items[$this->parent_Column]][] = $items[$this->id_Column];
        }

        if ($this->type == 'select') {
            return $this->buildSelect($this->parent, $menu, 0);
        } elseif ($this->type == 'checkbox') {
            return $this->buildCheckBox($this->parent, $menu, 0);
        } else {
            return $this->buildMenu($this->parent, $menu);
        }
    }


    function buildCheckBox($parent, $menu)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            $html .= "<ul>\n";
            foreach ($menu['parents'][$parent] as $itemId) {
                $level++;
                $option_selected = '';
                if (is_array($this->selected)) {
                    if (in_array($menu['items'][$itemId][$this->id_Column], $this->selected)) {
                        $option_selected = 'checked';
                    }
                } else {
                    if ($menu['items'][$itemId][$this->id_Column] == $this->selected) {
                        $option_selected = 'checked';
                    }
                }

                if (!isset($menu['parents'][$itemId])) {
                    $html .= "<li class='multi_checkbox checkbox_li_" . $menu['items'][$itemId][$this->id_Column] . "'>" . (($this->title_position != 'right') ? $menu['items'][$itemId][$this->title_Column] : '');
                    $html .= "\n  <input " . $this->buildAttributes() . $option_selected . " type='checkbox' value='" . $menu['items'][$itemId][$this->id_Column] . "'> \n";
                    $html .= (($this->title_position == 'right') ? $menu['items'][$itemId][$this->title_Column] : '') . "\n";
                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }
                    $html .= "</li>\n";
                }
                if (isset($menu['parents'][$itemId])) {
                    $html .= "<li class='multi_checkbox has_parent checkbox_li_" . $menu['items'][$itemId][$this->id_Column] . "'>" . (($this->title_position != 'right') ? $menu['items'][$itemId][$this->title_Column] : '');
                    $html .= "\n  <input " . $this->buildAttributes() . $option_selected . " type='checkbox' value='" . $menu['items'][$itemId][$this->id_Column] . "'> \n";
                    $html .= (($this->title_position == 'right') ? $menu['items'][$itemId][$this->title_Column] : '');
                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }
                    $html .= $this->buildCheckBox($itemId, $menu);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }

    function buildSelect($parent, $menu, $level)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            $html .= "\n";
            foreach ($menu['parents'][$parent] as $itemId) {
                $option_selected = '';
                if (is_array($this->selected)) {
                    if (in_array($menu['items'][$itemId][$this->id_Column], $this->selected)) {
                        $option_selected = 'selected';
                    }
                } else {
                    if ($menu['items'][$itemId][$this->id_Column] == $this->selected) {
                        $option_selected = 'selected';
                    }
                }
                if (!isset($menu['parents'][$itemId])) {
                    $html .= "\n <option $option_selected value='" . $menu['items'][$itemId][$this->id_Column] . "'>" . nbs(($level) * $this->level_spacing) . $menu['items'][$itemId][$this->title_Column] . "</option>\n";
                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }
                }

                if (isset($menu['parents'][$itemId])) {
                    echo $level;echo '<br />';
                    $html .= "\n <option $option_selected value='" . $menu['items'][$itemId][$this->id_Column] . "'>" . nbs($level * $this->level_spacing) . $menu['items'][$itemId][$this->title_Column] . "</option>\n";
                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }
                    $html .= $this->buildSelect($itemId, $menu, ($level + 1));
                    //$level--;
                }
            }
            $html .= "\n";
        }
        return $html;
    }

    // Menu builder function, parentId 0 is the root
    function buildMenu($parent, $menu)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            foreach ($menu['parents'][$parent] as $itemId) {
                $href = '';
                $title_text = '';
                $active = '';
                if (!isset($menu['parents'][$itemId])) {
                    $href = $this->url . $menu['items'][$itemId][$this->link_Column];
                    $title_text = stripslashes($menu['items'][$itemId][$this->title_Column]);
                    if ($this->active_link == $menu['items'][$itemId][$this->link_Column]) {
                        $active = $this->active_class;
                    }

                    $parent_html = str_replace(array('{href}', '{link_text}', '{title}', '{has_child}', '{active_class}'), array($href, $title_text, $title_text, $this->has_child_html, $active), $this->parent_li_start);

                    if (count($this->search) > 0) {
                        foreach ($this->search as $k => $s) {
                            $parent_html = str_replace($s, $menu['items'][$itemId][$this->replace[$k]], $parent_html);
                        }
                    }
                    $html .= $parent_html;

                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }
                    $html .= $this->parent_li_end;

                }
                if (isset($menu['parents'][$itemId])) {
                    $href = $this->url . $menu['items'][$itemId][$this->link_Column];
                    $title_text = stripslashes($menu['items'][$itemId][$this->title_Column]);
                    if ($this->active_link == $menu['items'][$itemId][$this->link_Column]) {
                        $active = $this->active_class;
                    }

                    $parent_html = str_replace(array('{href}', '{link_text}', '{title}', '{has_child}', '{active_class}'), array($href, $title_text, $title_text, $this->has_child_html,$active), $this->child_li_start);
                    if (count($this->search) > 0) {
                        foreach ($this->search as $k => $s) {
                            $parent_html = str_replace($s, $menu['items'][$itemId][$this->replace[$k]], $parent_html);
                        }
                    }
                    $html .= $parent_html;
                    if (!empty($this->call_func)) {
                        $html .= call_user_func($this->call_func, $menu['items'][$itemId], $this->selected);
                    }

                    $html .= $this->child_ul_start;
                    $html .= $this->buildMenu($itemId, $menu);
                    $html .= $this->child_ul_end;
                    $html .= $this->child_li_end;

                }
            }
        }
        return $html;
    }


    function buildAttributes()
    {
        if (count($this->attrs) && is_array($this->attrs)) {
            $attributes = '';
            foreach ($this->attrs as $key => $attr) {
                $attributes .= $key . '="' . $attr . '" ';
            }
            return $attributes;
        } else {
            return $this->attrs;
        }
    }
}
