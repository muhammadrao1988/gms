<?php

function getCellNumber($cellnumber, $row )
{
    return cellNumber($cellnumber);
}

function getCellNumberWithLink($cellnumber, $row )
{
    $CI = & get_instance();
    return '<a href="'.site_url(ADMIN_DIR . $CI->router->class . '/AJAX/editing_number/'.$cellnumber).'" class="view_number">'.cellNumber($cellnumber).'</a>';
}

/*function removeZero($str)
{
    $str = (substr($str, 0, 1) === '0' ? substr($str, 1) : $str);
    return $str;
}


function replaceChar($string, $num = 3, $replacement = 'x')
{

    $newStr = '';
    $length = (strlen($string) - $num);
    for ($i = 1; $i <= $length; $i++) {
        $newStr .= $replacement;
    }
    return $newStr . substr($string, $length, $num);
}

function getLimit($page, $per_page)
{
    $offset = ($page > 0 ? $page : 0);
    return " LIMIT " . $offset . ", " . $per_page;
}*/

