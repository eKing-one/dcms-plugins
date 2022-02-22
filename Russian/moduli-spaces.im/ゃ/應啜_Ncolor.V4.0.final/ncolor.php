<?php


include_once 'sys/inc/start.php';


include_once 'sys/inc/compress.php';


include_once 'sys/inc/sess.php';


include_once 'sys/inc/home.php';


include_once 'sys/inc/settings.php';


include_once 'sys/inc/db_connect.php';


include_once 'sys/inc/ipua.php';


include_once 'sys/inc/fnc.php';


include_once 'sys/inc/user.php';


$set['title']='Смена Цвета Ника'; // заголовок страницы

//Код масивный,но безопасный:)
include_once 'sys/inc/thead.php';


title();


err();


aut();




$action=htmlspecialchars(trim($_GET['action']));
$color=mysql_fetch_array(mysql_query("SELECT * FROM `ncolor`"));



switch ($action){


default:
echo "  <div class='menu'>\n";
echo "<a href='ncolor.php?action=gradient'>Градиентовые цвета</a>($color[cena1]баллов)<br />";
echo "<a href='ncolor.php?action=stand'>Обычные цвета</a>($color[cena2]баллов)<br />";
echo "</div>";
break;


case 'stand':
if (isset($user) & $user['balls'] < $color[cena2])
{
if ($user['pol']==0){echo "Извини <b>красавица,</b> \n";} else {echo "<b>Извини братан,</b> \n";}
echo "но изменять цвет ника можно только после того как ты наберешь $color[cena2] баллов<br/>У вас <b>$user[balls]</b> баллов\n";
}
else
{
echo "Стоимость обычного цвета $color[cena2] баллов<br/>\n";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav1">';
echo GradientText("$user[nick]", "#FF0000", "#FF0000");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav2">';
echo GradientText("$user[nick]", "#D2691E", "#D2691E");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav3">';
echo GradientText("$user[nick]", "#FF1493", "#FF1493");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav4">';
echo GradientText("$user[nick]", "#0000FF", "#0000FF");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav5">';
echo GradientText("$user[nick]", "#008000", "#008000");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav6">';
echo GradientText("$user[nick]", "#FFFF00", "#FFFF00");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav7">';
echo GradientText("$user[nick]", "#FFD700", "#FFD700");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav8">';
echo GradientText("$user[nick]", "#FFA500", "#FFA500");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav9">';
echo GradientText("$user[nick]", "#8B008B", "#8B008B");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav10">';
echo GradientText("$user[nick]", "#000000", "#000000");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav11">';
echo GradientText("$user[nick]", "#696969", "#696969");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav12">';
echo GradientText("$user[nick]", "#FFFFFF", "#FFFFFF");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav13">';
echo GradientText("$user[nick]", "#669999", "#669999");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=sav14">';
echo GradientText("$user[nick]", "#00FF00", "#00FF00");
echo '</a><br/>';
echo "</div>";

}
break;

case 'sav1':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FF0000'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FF0000'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'sav2':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#D2691E'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#D2691E'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'sav3':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FF1493'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FF1493'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav4':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#0000FF'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#0000FF'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav5':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#008000'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#008000'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav6':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FFFF00'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFFF00'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav7':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FFD700'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFD700'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav8':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FFA500'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFA500'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav9':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#8B008B'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#8B008B'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav10':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#000000'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#000000'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav11':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#696969'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#696969'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav12':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FFFFFF'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFFFFF'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav13':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#669999'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#669999'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

case 'sav14':
if (isset($user) & $user['balls'] < $color[cena2])
{
echo "Недостаточно баллов.Надо :$color[cena2] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena2'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#00FF00'  WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#00FF00'  WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;




case 'gradient':
if (isset($user) & $user['balls'] < $color[cena1])
{
if ($user['pol']==0){echo "Извини <b>красавица,</b> \n";} else {echo "<b>Извини братан,</b> \n";}
echo "но изменять цвет ника можно только после того как ты наберешь $color[cena1] баллов<br/>У вас <b>$user[balls]</b> баллов\n";
}
else
{
echo "Стоимость Градиента $color[cena1] баллов<br/>\n";

echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save1">';
echo GradientText("$user[nick]", "#00008B", "#FFA500");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save2">';
echo GradientText("$user[nick]", "#00FF00", "#0000FF");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save3">';
echo GradientText("$user[nick]", "#000000", "#4B0082");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save4">';
echo GradientText("$user[nick]", "#000080", "#008000");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save5">';
echo GradientText("$user[nick]", "#0000FF", "#FFFF00");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save6">';
echo GradientText("$user[nick]", "#FFFF00", "#0000FF");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save7">';
echo GradientText("$user[nick]", "#9932CC", "#006400");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save8">';
echo GradientText("$user[nick]", "#696969", "#DC143C");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save9">';
echo GradientText("$user[nick]", "#7CFC00", "#B22222");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save10">';
echo GradientText("$user[nick]", "#FF0000", "#0000CD");
echo '</a><br/>';
echo "</div>";
echo "  <div class='p_t'>\n";
echo '<a href="ncolor.php?action=save11">';
echo GradientText("$user[nick]", "#0000CD", "#FF0000");
echo '</a><br/>';
echo "</div>";
}
break;
case 'save1':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#00008B' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFA500' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save2':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#00FF00' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#0000FF' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save3':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#00FFFF' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FAF0E6' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save4':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#000080' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#008000' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save5':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#0000FF' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFFF00' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save6':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FFFF00' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#0000FF' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save7':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#9932CC' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FF1493' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save8':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#696969' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FFDEAD' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save9':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#7CFC00' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#B22222' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save10':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#FF0000' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#0000CD' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;
case 'save11':
if (isset($user) & $user['balls'] < $color[cena1])
{
echo "Недостаточно баллов.Надо :$color[cena1] баллов.А у вас <b>$user[balls]</b>\n";
}else{
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$color['cena1'])."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ncolor`='#0000CD' WHERE `id` = '$user[id]'");
mysql_query("UPDATE `user` SET `ncolor2`='#FF0000' WHERE `id` = '$user[id]'");
echo 'Сохранено!<br/>'; } break;

}


echo '<div class="foot">';


echo "&#8594;<a href='/ncolor.php/'>Цвет ника</a><br />";



echo "</div>";





include_once 'sys/inc/tfoot.php';           ?>
