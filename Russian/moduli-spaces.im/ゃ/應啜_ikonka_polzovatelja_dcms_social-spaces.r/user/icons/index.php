<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

$set['title']='Иконки';

include_once '../../sys/inc/thead.php';

title();
aut();

only_reg();

?>
<style>
.adv_user_link{float:left;border:1px solid #ccc;-webkit-border-radius:4px;
border-radius:4px;padding:10px !important;
text-decoration:none;margin-right:5px;background:#f7fafa;
background:-webkit-gradient(linear,left top,left bottom,from(#f7fafa),to(#edeef0));}
.oh{overflow:hidden}
</style>
<?

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка иконки</b>';
echo '</div>';


if(!isset($_GET['get'])){
echo '<div class="mess">';
echo '<span class="mess"><b> Магазин </b></span>';
echo '<a href="?get=my_icons"><span class="mess"><b> Мои иконки </b></span></a>';
echo '</div>';


echo '<div class="oh">';

$x=0;
while ($x++<125) echo '<a href ="pay.php?id='.$x.'"><span class="icon_s"><img src="png/'.$x.'.png" class="adv_user_link wa mt_m"></span></a>';

echo '</div>';
}
else
{
echo '<div class="mess">';
echo '<a href="?"><span class="mess"><b> Магазин </b></span></a>';
echo '<span class="mess"><b> Мои иконки </b></span>';
echo '</div>';


$icon=mysql_fetch_assoc(mysql_query("SELECT * FROM `us_icons` WHERE `id_user` = '".$user['id']."'  LIMIT 1"));


if (empty($icon)) 
{
echo '<div class="err"> У вас нет активных иконок!</div>';
}
else
{
echo '<div class="mess">';
echo 'Иконка: <img src="png/'.$icon['id_icon'].'.png">';
if($icon['time'] > $time) {
echo '<br />До: <i>'.vremja($icon['time']).'</i>';
}
else
{
echo '<br />До: <i>срок истек</i>';
}
echo '<br /><a href="?get=my_icons&delete"> <small>Удалить</small></a>';

if(isset($_GET['delete']))
{
mysql_query("DELETE FROM `us_icons` WHERE `id_user` = '".$user['id']."'");
header('Location: index.php?get=my_icons'.SID);
$_SESSION['message']='Иконка успешно удалена!';

}


echo '</div>';

}





}

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="S"/> <a href="/user/money/">Дополнительные услуги</a> | <b>Покупка иконки</b>';
echo '</div>';







err();
include_once '../../sys/inc/tfoot.php';
?>