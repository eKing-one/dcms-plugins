<?


/*------------------------статус форма-----------------------*/
if (isset($user) && isset($_GET['status'])){
if ($user['id'] == $ank['id']){
echo '<div class="main">Статус [512 символов]</div>';
echo "<form action='/info.php?id=" . $ank['id'] . "' method=\"post\">";
echo "$tPanel<textarea type=\"text\" style='' name=\"status\" value=\"\"/></textarea><br /> \n";
echo "<input class=\"submit\" style='' type=\"submit\" value=\"Установить\" />\n";
echo " <a href='/info.php?id=$ank[id]'>Отмена</a><br />\n";
echo "</form>";
include_once 'sys/inc/tfoot.php';
exit;
}
}

/*-----------------------------------------------------------*/
if ($ank['group_access']>1)echo "<div class='err'>$ank[group_name]</div>\n";
echo "<div class='nav1'>";
echo group($ank['id']) . " $ank[nick] ";
echo medal($ank['id']) . " " . online($ank['id']) . " ";

if ((user_access('user_ban_set') || user_access('user_ban_set_h') || user_access('user_ban_unset')) && $ank['id']!=$user['id'])

echo "<a href='/adm_panel/ban.php?id=$ank[id]'><font color=red>[Бан]</font></a>\n";


echo "</div>";


echo "<div class='nav2'>";
echo "<table allspacing='1' cellpadding='1'><tr>";
echo "<td class='bl1'>";
echo "<div class='nav2'>";
avatar($ank['id']);
echo "<br />";
echo "</td>";
echo "<td class='nav2' align= center'>";
echo "<span class=\"ank_n\">Посл. посещение:</span> <span class=\"ank_d\">".vremja($ank['date_last'])."</span><br />\n";
if ($status['id'])
{
echo "<div class='sta'>";
echo output_text($status['msg']) . ' <font style="font-size:11px; color:gray;"></font>';
if ($ank['id']==$user['id'])echo " <a href='?id=$ank[id]&amp;status'><img src='/style/icons/edit.gif' alt='*'>нов</a>";
echo "</div>";
} 

echo "$gorod<span class=\"ank_n\"></span>$a <span class=\"ank_d\">".output_text($ank['ank_city'])."</span>\n";
echo "</div>\n";
echo "</div>";
echo "</td>";
echo "</table> ";
echo "</div>";

echo "<div class='nav1'>";

if (isset($user) && $ank['id']!=$user['id']){
echo "<div class='nav2'>";
echo " <a href=\"/mail.php?id=$ank[id]\"><input value=\"Личное сообщение\" type=\"submit\" />\n";
}

if (isset($user) && $ank['id']!=$user['id']){
if ($frend_new==0 && $frend==0){
echo "<a href='/user/frends/create.php?add=".$ank['id']."'><input value=\"Добавить в друзья\" type=\"submit\" />\n";

}elseif ($frend_new==1){

echo "<a href='/user/frends/create.php?otm=$ank[id]'><input value=\"Отменить заявку\" type=\"submit\" />\n";

}elseif ($frend==2){
}
}
echo "</div>";
/*
===============================
Последние добавленные фото
===============================
*/$sql = mysql_query("SELECT * FROM `gallery_foto` WHERE `id_user` = '$ank[id]' ORDER BY `id` DESC LIMIT 8");
$coll=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$ank[id]' ORDER BY `id` DESC"),0);if ($coll>0){
	echo "<div class='foot'>";
	echo "<img src='/style/icons/pht2.png' alt='*' /> ";
	echo "<a href='/foto/$ank[id]/'>Фотографии</a> ";
	echo "(" . mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$ank[id]'"),0) . ")";
	echo "</div>";
	echo "<div class='nav2'>";
	
	while ($photo = mysql_fetch_assoc($sql)){
		echo "<a href='/foto/$ank[id]/$photo[id_gallery]/$photo[id]/'><img style='padding:1px;  padding: 0px; margin:2px; height: 53px; width:53px; border: 1px #4fdafd solid; vertical-align:top;  background-image: url(); background-position: center top;' src='/foto/foto50/$photo[id].$photo[ras]' alt=''/></a>";
	}
	echo "</div>";
}
/*
=====================================
Анкета пользователя, если автор
то выводим ссылки на редактирование
полей, если нет то нет =)
=====================================
*/

echo "<div class='foot'>Информация</div> ";


if (isset($user) && $ank['id']==$user['id'])
{
$name = "<a href='/user/info/edit.php?act=ank_web&amp;set=name'>";
$date = "<a href='/user/info/edit.php?act=ank_web&amp;set=date'>";
$gorod = "<a href='/user/info/edit.php?act=ank_web&amp;set=gorod'>";
$pol = "<a href='/user/info/edit.php?act=ank_web&amp;set=pol'>";$a = "</a>";}else{
$name = "<font style='padding:1px; color : #005ba8; padding:1px;'>";
$date =  "<font style='padding:1px; color : #005ba8; padding:1px;'>";
$gorod =  "<font style='padding:1px; color : #005ba8; padding:1px;'>";
$pol =   "<font style='padding:1px; color : #005ba8; padding:1px;'>";
$a = "</font>";}




/*
=====================================
Основное
=====================================
*/
echo "<div class='nav1'>";
if ($ank['ank_name']!=NULL)
echo "$name<span class=\"ank_n\">Имя:</span>$a <span class=\"ank_d\">$ank[ank_name]</span><br />\n";
else
echo "$name<span class=\"ank_n\">Имя:</span>$a<br />\n";echo "$pol<span class=\"ank_n\">Пол:</span>$a <span class=\"ank_d\">".(($ank['pol']==1)?'Мужской':'Женский')."</span><br />\n";if ($ank['ank_city']!=NULL)
echo "$gorod<span class=\"ank_n\">Город:</span>$a <span class=\"ank_d\">".output_text($ank['ank_city'])."</span><br />\n";
else
echo "$gorod<span class=\"ank_n\">Город:</span>$a<br />\n";if ($ank['ank_d_r']!=NULL && $ank['ank_m_r']!=NULL && $ank['ank_g_r']!=NULL){
if ($ank['ank_m_r']==1)$ank['mes']='Января';
elseif ($ank['ank_m_r']==2)$ank['mes']='Февраля';
elseif ($ank['ank_m_r']==3)$ank['mes']='Марта';
elseif ($ank['ank_m_r']==4)$ank['mes']='Апреля';
elseif ($ank['ank_m_r']==5)$ank['mes']='Мая';
elseif ($ank['ank_m_r']==6)$ank['mes']='Июня';
elseif ($ank['ank_m_r']==7)$ank['mes']='Июля';
elseif ($ank['ank_m_r']==8)$ank['mes']='Августа';
elseif ($ank['ank_m_r']==9)$ank['mes']='Сентября';
elseif ($ank['ank_m_r']==10)$ank['mes']='Октября';
elseif ($ank['ank_m_r']==11)$ank['mes']='Ноября';
else $ank['mes']='Декабря';
echo "$date<span class=\"ank_n\">Д
ата рождения:</span>$a $ank[ank_d_r] $ank[mes] $ank[ank_g_r]г. <br />\n";
$ank['ank_age']=date("Y")-$ank['ank_g_r'];
if (date("n")<$ank['ank_m_r'])$ank['ank_age']=$ank['ank_age']-1;
elseif (date("n")==$ank['ank_m_r']&& date("j")<$ank['ank_d_r'])$ank['ank_age']=$ank['ank_age']-1;
echo "<span class=\"ank_n\">Возраст:</span> $ank[ank_age] \n";
}
elseif($ank['ank_d_r']!=NULL && $ank['ank_m_r']!=NULL)
{
if ($ank['ank_m_r']==1)$ank['mes']='Января';
elseif ($ank['ank_m_r']==2)$ank['mes']='Февраля';
elseif ($ank['ank_m_r']==3)$ank['mes']='Марта';
elseif ($ank['ank_m_r']==4)$ank['mes']='Апреля';
elseif ($ank['ank_m_r']==5)$ank['mes']='Мая';
elseif ($ank['ank_m_r']==6)$ank['mes']='Июня';
elseif ($ank['ank_m_r']==7)$ank['mes']='Июля';
elseif ($ank['ank_m_r']==8)$ank['mes']='Августа';
elseif ($ank['ank_m_r']==9)$ank['mes']='Сентября';
elseif ($ank['ank_m_r']==10)$ank['mes']='Октября';
elseif ($ank['ank_m_r']==11)$ank['mes']='Ноября';
else $ank['mes']='Декабря';
echo "$date<span class=\"ank_n\">День рождения:</span>$a $ank[ank_d_r] $ank[mes] \n";
}if ($ank['ank_d_r']>=19 && $ank['ank_m_r']==1){echo "| Водолей<br />";}
elseif ($ank['ank_d_r']<=19 && $ank['ank_m_r']==2){echo "| Водолей<br />";}
elseif ($ank['ank_d_r']>=18 && $ank['ank_m_r']==2){echo "| Рыбы<br />";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==3){echo "| Рыбы<br />";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==3){echo "| Овен<br />";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==4){echo "| Овен<br />";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==4){echo "| Телец<br />";}
elseif ($ank['ank_d_r']<=21 && $ank['ank_m_r']==5){echo "| Телец<br />";}
elseif ($ank['ank_d_r']>=20 && $ank['ank_m_r']==5){echo "| Близнецы<br />";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==6){echo "| Близнецы<br />";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==6){echo "| Рак<br />";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==7){echo "| Рак<br />";}
elseif ($ank['ank_d_r']>=23 && $ank['ank_m_r']==7){echo "| Лев<br />";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==8){echo "| Лев<br />";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==8){echo "| Дева<br />";}
elseif ($ank['ank_d_r']<=23 && $ank['ank_m_r']==9){echo "| Дева<br />";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==9){echo "| Весы<br />";}
elseif ($ank['ank_d_r']<=23 && $ank['ank_m_r']==10){echo "| Весы<br />";}
elseif ($ank['ank_d_r']>=22 && $ank['ank_m_r']==10){echo "| Скорпион<br />";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==11){echo "| Скорпион<br />";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==11){echo "| Стрелец<br />";}
elseif ($ank['ank_d_r']<=22 && $ank['ank_m_r']==12){echo "| Стрелец<br />";}
elseif ($ank['ank_d_r']>=21 && $ank['ank_m_r']==12){echo "| Козерог<br />";}
elseif ($ank['ank_d_r']<=20 && $ank['ank_m_r']==1){echo "| Козерог<br />";}echo "</div>\n";
echo "<div class='main_menu'><a href='/user/info/anketa.php?id=$ank[id]'><center>Полная информация »</center></a></div> ";
	

echo "<div class='foot'>Другое</div> ";


/*

=======================================
*/
echo "<div class='nav1'>";
if(isset($user) && $ank['id']!=$user['id']) {
echo "<img src='/style/icons/present.gif' alt='*' /> <a href=\"/user/gift/categories.php?user=$ank[id]\">Сделать подарок</a><br />\n";
}
echo "</div>";

echo "<div class='nav1'>";
echo "<a href='/foto/$ank[id]/'>Фотографии</a> ";
echo "(" . mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$ank[id]'"),0) . ")<br />";
echo "</div>";

echo "<div class='nav1'>";
$k_f = mysql_result(mysql_query("SELECT COUNT(id) FROM `frends_new` WHERE `to` = '$ank[id]' LIMIT 1"), 0);
$k_fr = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' AND `i` = '1'"), 0);
$res = mysql_query("select `frend` from `frends` WHERE `user` = '$ank[id]' AND `i` = '1'");
echo '<a href="/user/frends/?id='.$ank['id'].'">Друзья</a> ('.$k_fr.'</b>/';$i =0;
while ($k_fr = mysql_fetch_array($res)){
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$k_fr[frend]' && `date_last` > '".(time()-600)."'"),0) != 0) $i++;
}
echo "<span style='color:green'><a href='/user/frends/online.php?id=".$ank['id']."'>$i</a></span>)";
if ($k_f>0 && $ank['id']==$user['id'])echo " <a href='/user/frends/new.php'><font color='red'>+$k_f</font></a>";;
echo "</div>";
echo "<div class='nav1'>";
if (isset($user))
{
if ($user['wall']==0)

	echo "<a href='/info.php?id=$ank[id]&amp;wall=1'>Закрыть</a>\n";

	else
	
	echo "<a href='/info.php?id=$ank[id]&amp;wall=0'>Ещё..</a>\n";
	}else{
	}
echo "</div>";if ($user['wall']==0){
echo "</div>";


echo "<div class='nav1'>";
$k_music=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_music` WHERE `id_user` = '$ank[id]'"),0);
echo "<a href='/user/music/index.php?id=$ank[id]'>Аудиозаписи</a> ";
echo "(" . $k_music . ")";
echo "</div>";

echo "<div class='nav1'>";
if(isset($user) && $ank['id']!=$user['id']) {
$k_p = mysql_result(mysql_query("SELECT COUNT(id) FROM `gifts_user` WHERE `id_user` = '$ank[id]' AND `status` = '1'"),0);
echo ' <a href="/user/gift/index.php?id=' . $ank['id'] . '">Подарки</a> (' . $k_p . ')';
}
echo "</div>";


echo "<div class='foot'>Действия</div> ";
echo "<div class='nav1'>";
if ($user['id'] == $ank['id']) {
$kol_black=mysql_result(mysql_query("SELECT COUNT(*) FROM `black_list` WHERE `id_user` = '".$user['id']."'"),0);
echo "<a href='/user/black_list.php'>Черный список</a> ($kol_black)<br />\n"; 
}
echo "</div>";


if(isset($user) && $ank['id']!=$user['id']) {
echo "<div class='nav1'>";

if (!isset($_GET['id_user']) && !isset($_GET['black']) &&  isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `black_list` WHERE `id_user` = '".$user['id']."' AND `id_black_user` = '".$ank['id']."' "), 0) == 0 && $ank['level']==0) {

echo " <a href='/info.php?id=$ank[id]&amp;black'>В черный список </a><br />\n";

} else if ($ank['level']==0) {

echo " <a href='/info.php?id=$ank[id]&amp;out_black'>Удалить из черного списка </a><br />\n";

}


echo "<div class='nav1'>";
if (isset($user) && $ank['id']!=$user['id']){
if ($frend_new==0 && $frend==0){
}elseif ($frend==2){
echo " <a href='/user/frends/create.php?del=$ank[id]'>Удалить из друзей</a><br />\n";

}
}
echo "</div>";

}

}

/*


echo "<div class='foot'>Стена</div> ";
 

========================================


Стена


========================================


*/
echo "<div class='foot'>Стена</div> ";
echo "<div class='foot'>";

include_once 'user/stena/index.php';

/*


========================================


The End


========================================


*/





?>