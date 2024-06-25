<?php

$k_post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_udobr_name`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];



$res = dbquery("select * from `farm_udobr_name` LIMIT $start, $set[p_str];");

while ($post = dbarray($res)) {
    if ($num == 1) {
        echo "<div class='rowdown'>";
        $num = 0;
    } else {
        echo "<div class='rowup'>";
        $num = 1;
    }

    echo "<img src='/farm/udobr/$post[id].png' height='40' width='40'> <a href='?id=$post[id]'>" . $post['name'] . "</a> <br/>";
    echo "</div>";
}



if ($k_page > 1) str('?', $k_page, $page); // Вывод страниц
