<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title'] = 'FAQ по рейтингу';
include_once '../sys/inc/thead.php';

title();
aut();
err();
///echo "<input type='text' value='http://$_SERVER[SERVER_NAME]/login.php?id=$user[id]&amp;pass=".htmlspecialchars($user['pass'])."' /><br />\n";

/* Инклудим дополнительные стили */

include_once '../reit/style.php';
include_once '../reit/style1.php';
include_once '../reit/style2.php';

/* END */
echo '<div class="location_bar " id="header_path">
FAQ по рейтингу
</div>';



echo '<div class="list_item gradient_block1">
<b style="color:#009933"> Рейтинг обитателей. </b><br>
У каждого обитателя '.$_SERVER['SERVER_NAME'].' есть рейтинг. <br> Увидеть его можно в анкете. <br> Мы стараемся сделать так, чтобы рейтинг отражал реальную популярность обитателя. <br> Если вы интересный и отзывчивый человек и при этом много общаетесь и добавляете хорошие файлы, то ваш рейтинг обязательно будет увеличиваться. <br><b> Рейтинг считается так: </b><br><span style="color:#990000"> Рейтинг = Активность обитателя + Популярность обитателя </span><br><br><b> На активность влияет: </b><br> 1. Общение в чате, форуме, гостевой, дневниках, личных сообщениях и т.д.<br><br>
<br> При достижении определенного рейтинга, обитатель награждается медалькой и другими наградами. <br><br>
Рейтинг от 10 до 20 - бронзовая медаль. <img src="/reit/img/medal_bronze.gif " alt="" /><br>Рейтинг от 21 до 35 - серебряная медаль. <img src="/reit/img/medal_silver.gif" alt="" /><br />Рейтинг от 36 и выше - золотая медаль. <img src="/reit/img/medal_gold.gif" alt="" /></span>
</div>
<div class="overfl_hid t-bg2 light_border_bottom">
<a class="t-block_item t-light_link t-link_no_underline overfl_hid t-padd_left  " href="/reit/zadanie.php"><span class="t-block_item stnd_padd t-bg_arrow_prev"><span class="t-padd_left"> Назад </span></span></a>
</div>';
include_once '../sys/inc/tfoot.php';

?>