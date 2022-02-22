<?
//Создатель мода Xokano
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
include_once 'inc_bm.php';
if (isset($_GET['money'])){
$set['title']='Покупка монет!';
include_once '../../sys/inc/thead.php';
title();
aut();
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Покупка монет<br />\n";
echo "</div>\n";
echo "<div class='mess'>Курс <b>1 манета</b> = <b>$sum[money] wmr</b> (цены не поднимаются)</div>";
echo "<div class='nav2'>Для покупки оплатите счет <b>$wmr</b> в примечание к платежу укажите <b>$user[nick] money</b>, после оплаты сообщите о платеже на ник <b>$nick</b> в противном случае монеты будут зачислены с задержкой!</div>";
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Покупка монет<br />\n";
echo "</div>\n";
include_once '../../sys/inc/tfoot.php';
}
if (isset($_GET['balls'])){
$set['title']='Покупка баллов!';
include_once '../../sys/inc/thead.php';
title();
aut();
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Покупка баллов!<br />\n";
echo "</div>\n";
echo "<div class='mess'>Курс <b>500 баллов</b> = <b>$sum[balls] wmr</b> (цены не поднимаются)</div>";
echo "<div class='nav2'>Для покупки оплатите счет <b>$wmr</b> в примечание к платежу укажите <b>$user[nick] balls</b>, после оплаты сообщите о платеже на ник <b>$nick</b> в противном случае баллы будут зачислены с задержкой!</div>";
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Покупка баллов!<br />\n";
echo "</div>\n";
include_once '../../sys/inc/tfoot.php';
}
$set['title']='Магазин!';
include_once '../../sys/inc/thead.php';
title();
aut();
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Магазин!<br />\n";
echo "</div>\n";
echo "<div class='main'>Личный счет:<br>Монеты - <b>$user[money]</b><br>Баллы - <b>$user[balls]</b></div>";
echo "<div class='main2'><img src='/style/icons/str.gif'> <a href='balls_money.php?money'>Купить монеты</a></div>";
echo "<div class='main2'><img src='/style/icons/str.gif'> <a href='balls_money.php?balls'>Купить баллы</a></div>";
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a> | Магазин!<br />\n";
echo "</div>\n";
include_once '../../sys/inc/tfoot.php';
?>