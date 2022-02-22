<?

/*--------------------в друзья-------------------*/
$frend_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `frends_new` WHERE (`user` = '$user[id]' AND `to` = '$ank[id]') OR (`user` = '$ank[id]' AND `to` = '$user[id]') LIMIT 1"),0);
$frend=mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]') LIMIT 1"),0);
$not_user=mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0;
if (isset($user) && $user['id']!=$ank['id']){
if (isset($_GET['fok'])){
echo '<center>';
echo "<div class='foot'><form action='/info.php?id=".$ank['id']."' method=\"post\">";
echo "<input class=\"submit\" type=\"submit\" value=\"Закрыть\" />\n";
echo "</form></div>";
echo '</center>';
}
}
if (isset($user) && isset($_GET['frends'])  && $frend_new==0 && $frend==0 ){
if ($user['id']!=$ank['id']){
echo '<center>';
echo "<div class='err'>Пользователь должен будет подтвердить, что вы друзья.</div><div class='foot'><form action='/user/frends/create.php?add=".$ank['id']."' method=\"post\">";
echo "<input class=\"submit\" type=\"submit\" value=\"Пригласить\" />\n";
echo " <a href='/info.php?id=$ank[id]'>Отмена</a><br />\n";
echo "</form></div>";
echo '</center>';
}
}
/*---------------------------------------------------------*/
 // Должность на сайте
if ($ank['group_access']>1) {
	 echo "<div class='err'>$ank[group_name]</div>\n"; }?>
<table class='table_info' cellspacing="0" cellpadding="0">
<tr><td class='block_menu'>
<?
 // Аватар 

$uid = mysql_fetch_array(mysql_query ("SELECT * FROM zags WHERE user_id='$ank[id]'"));
$uid1 = mysql_fetch_array(mysql_query ("SELECT * FROM zags WHERE ank_id='$ank[id]'"));
if(isset($_GET['obruchit'])){
mysql_query("INSERT INTO `zags` (`user_id`, `user_nick`, `ank_id`, `ank_nick`) values('.$user[id].', '$user[nick]', '.$ank[id].', '$ank[nick]')");
$_SESSION['message'] = ('Ваше предложение обручится отправлено, ожидайте ответа');
$massage = "[b][green]Вам предложение обручится от $user[nick] :[/green][/b] [br][url=/info.php?id=$ank[id]&obruchitok]Принять[/url] | [url=/info.php?id=$ank[id]&obruchitnet]Отказатся[/url]";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$massage','$time')");
header("Location:?id=$ank[id]");
}

if(isset($_GET['obruchitok'])){
mysql_query("UPDATE `zags` SET `key` = '1' WHERE `user_id` = '$user[id]' OR `ank_id` = '$user[id]' LIMIT 1");
$_SESSION['message'] = ('Предложение обручится принято');
$massage = "Ваше предложение обручится на $user[nick] принято";
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$uid1[user_id]', '$massage','$time')");
header("Location:?id=$ank[id]");
}

if(isset($_GET['obruchitnet'])){
mysql_query("DELETE FROM `zags` WHERE `ank_id` = '$user[id]' OR `user_id` = '$user[id]'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$uid1[user_id]', 'Ваше предложение обручится на  $user[nick] отклонено','$time')");
$_SESSION['message'] = ('Вы отклонили предложение обручится');
header("Location:?id=$ank[id]");
}

if(isset($_GET['razvod'])){
mysql_query("DELETE FROM `zags` WHERE `ank_id` = '$user[id]' OR `user_id` = '$user[id]'");
$_SESSION['message'] = ('Вы развелись');
header("Location:?id=$ank[id]");
}
if(isset($_GET['razv'])){
echo"<div class='main'>Вы действительно хотите развестись с $ank[nick]:<br/> <a href='/info.php?id=$ank[id]&razvod'><img src='/delacc/ok.gif' alt='*'> Да</a> | <a href='/info.php?id=$ank[id]'><img src='/delacc/delete.gif' alt='*'> Нет</a></div>";
}
echo"<div class='nav2'>";

if ($ank['id'] == $uid1['ank_id'] && $uid1['key'] == '1'){
echo" <img src='/moduls.zags/img/love.png' alt='' />";
echo ($ank['pol'] == 1? 'Женат на ' : 'Замужем за ');
echo "<a href='info.php?id=$uid1[user_id]'>$uid1[user_nick]</a><br />";
}else
if ($ank['id'] == $uid['user_id'] && $uid['key'] == '1'){
echo" <img src='/moduls.zags/img/love.png' alt='' />";
echo ($ank['pol'] == 1? 'Женат на ' : 'Замужем за ');
echo "<a href='info.php?id=$uid[ank_id]'>$uid[ank_nick]</a><br />";
}else{
$user[pol] == $ank[pol];
echo" <img src='/moduls.zags/img/love.png' alt='' />";
echo ($ank['pol'] == 1? 'Не женат' : 'Не замужем');
}
echo"</div>";
echo "<div class='main'>";
echo "<div style='position:relative;'>";
avatar_ank_web($ank['id']);
$ava_q = mysql_query("SELECT * FROM `ava` WHERE `id_ank` = '".$ank['id']."'");
while ($ava = mysql_fetch_assoc($ava_q))
{
if ($ava['time']>=$time)
echo '<img style="position:absolute;top:'.$ava['top'].'px;left:'.$ava['left'].'px;"
src="/otp/'.$ava['otp'].'.png"/>';
}
echo "</div>";
 // Рейтинг
echo "<div class='main'>";
if ($ank['rating']>=0 && $ank['rating']<= 100){
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$ank[rating]%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=100 && $ank['rating']<= 200){
$rat=$ank['rating']-100;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=200 && $ank['rating']<= 300){
$rat=$ank['rating']-200;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=300 && $ank['rating']<= 400){
$rat=$ank['rating']-300;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=400 && $ank['rating']<= 500){
$rat=$ank['rating']-400;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=500 && $ank['rating']<= 600){
$rat=$ank['rating']-500;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=600 && $ank['rating']<= 700){
$rat=$ank['rating']-600;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=700 && $ank['rating']<= 800){
$rat=$ank['rating']-700;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=800 && $ank['rating']<= 900){
$rat=$ank['rating']-800;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}elseif ($ank['rating']>=900 && $ank['rating']<= 1000){
$rat=$ank['rating']-900;
echo "<div style='background-color: #73a8c7; width: 200px; height: 17px;'>
<div style=' background-color: #064a91; height:17px; width:$rat%;'></div>
<span style='position:relative; top:-17px; left:45%; right:57%; color:#ffffff;'>$ank[rating]%</span>
</div>";
}
echo "</div>";echo "<div class='main'>";
echo "<b>ID номер: $ank[id]</b>";
echo "</div>";
/*---------------анкета-------------------*/
echo "<div class='main2'>";
echo "<img src='/style/icons/anketa.gif' alt='*' /> <a href='/user/info/anketa.php?id=$ank[id]'>Анкета</a> ";
if (isset($user) && $user['id']==$ank['id']){
echo "[<img src='/style/icons/edit.gif' alt='*' /> <a href='/user/info/edit.php'>ред</a>]";
}
echo "</div>";
/*---------------------------------------*/

/*------------------------гости---------------------------*/
if (isset($user) && $user['id']==$ank['id']){
echo "<div class='main'>";
$new_g=mysql_result(mysql_query("SELECT COUNT(*) FROM `my_guests` WHERE `id_ank` = '$user[id]' AND `read`='1'"),0);
echo "<img src='/style/icons/guests.gif' alt='*' /> ";
if($new_g!=0)
{
$color = "<font color='red'>";
$color2 = "</font>";
}
else
{
$color = null;
$color2 = null;
}
echo "<a href='/user/myguest/index.php'>".$color."Гости".$color2."</a> \n";
if($new_g!=0)echo "<font color=\"red\">+$new_g</font>\n";
echo "</div>";
}
/*-------------------------------------------------------*//*-----------------лента-----------------*/
if (isset($user) && $user['id']==$ank['id'])
{
echo "<div class='main'>";/*
========================================
Уведомления
========================================
*/	$k_notif = mysql_result(mysql_query("SELECT COUNT(`read`) FROM `notification` WHERE `id_user` = '$user[id]' AND `read` = '0'"), 0); // Уведомления
		
		if($k_notif > 0)
		{
			echo "<img src='/style/icons/notif.png' alt='*' /> ";
			echo "<a href='/user/notification/index.php'><font color='red'>Уведомления</font></a> \n";
			echo "<font color=\"red\">+$k_notif</font> \n";
			echo "<br />";
		}/*
========================================
Обсуждения
========================================
*/
	echo "<img src='/style/icons/chat.gif' alt='*' /> ";
	$new_g=mysql_result(mysql_query("SELECT COUNT(*) FROM `discussions` WHERE `id_user` = '$user[id]' AND `count` > '0'"),0);
		if($new_g!=0)
		{
			echo "<a href='/user/discussions/index.php'><font color='red'>Обсуждения</font></a> \n";
			echo "<font color=\"red\">+$new_g</font> \n";
		}else{
			echo "<a href='/user/discussions/index.php'>Обсуждения</a> \n";
		}
	echo "<br />";$k_l=mysql_result(mysql_query("SELECT COUNT(*) FROM `tape` WHERE `id_user` = '$user[id]'  AND  `read` = '0'"),0);
if($k_l!=0)
{
$color = "<font color='red'>";
$color2 = "</font>";
}
else
{
$color = null;$color2 = null;
}
echo "<img src='/style/icons/lenta.gif' alt='*' /> <a href='/user/tape/'>".$color."Лента".$color2."</a> \n";
if($k_l!=0)echo "<font color=\"red\">+$k_l</font>\n";echo "</div>";
}
/*---------------------------------------*/

echo "<div class='main2'>";
echo "<img src='/style/my_menu/who_rating.png' alt='*' /> <a href='/user/info/who_rating.php?id=$ank[id]'><b>Отзывы</b></a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `user_voice2` WHERE `id_kont` = '".$ank['id']."'"),0).")<br />\n";
echo "</div>";

/*-----------------------------в друзья-------------------------*/
if (isset($user) && $user['id']!=$ank['id']){
echo "<div class='main'>";
if ($frend_new==0 && $frend==0){
echo "<img src='/style/icons/druzya.png' alt='*'/> <a href='/info.php?id=$ank[id]&amp;frends'>Добавить в друзья</a><br />\n";
}elseif ($frend_new==1){
echo "<img src='/style/icons/druzya.png' alt='*'/> <a href='/user/frends/create.php?otm=$ank[id]'>Отклонить заявку</a><br />\n";
}elseif ($frend==2){
echo "<img src='/style/icons/druzya.png' alt='*'/> <a href='/user/frends/create.php?del=$ank[id]'>Удалить из друзей</a><br />\n";
}
echo "</div>";
/*-------------------------------------------------------------*/


/*--------------------Сообщение-----------------------------------*/	
	echo "<div class='main'>";
	echo " <a href=\"/mail.php?id=$ank[id]\"><img src='/style/icons/pochta.gif' alt='*' /> Сообщение</a> \n";
	echo "</div>";
/*----------------------------------------------------------------*/
	
	
/*
========================================
Монеты перевод
========================================
*/
echo "<div class='main2'>";
echo "<img src='/style/icons/many.gif' alt='*' /> <a href=\"/user/money/translate.php?id=$ank[id]\">Перевести $sMonet[0]</a> \n";
echo "</div>";
}

$zags = mysql_fetch_array(mysql_query ("SELECT * FROM zags WHERE user_id='$user[id]' OR ank_id='$user[id]'"));
$zags1 = mysql_fetch_array(mysql_query ("SELECT * FROM zags WHERE user_id='$ank[id]' OR ank_id='$ank[id]'"));
if (isset($user) && $ank['id']!=$user['id'] && $ank['pol']!=$user['pol']){
if ($ank['id'] == $uid['user_id'] && $uid['key'] == '1' && $ank['id'] == $zags['user_id'] OR $ank['id'] == $zags['ank_id'] && $user['id'] == $zags1['user_id'] OR $user['id'] == $zags1['ank_id']){
echo '<div class="main2"><img src="/style/icons/razvod.gif" alt ""> <a href="/info.php?id='.$ank['id'].'&razv">Развестись</a></div>';
}elseif ($ank['id'] == $uid1['ank_id'] && $uid1['key'] == '1' && $ank['id'] == $zags['user_id'] OR $ank['id'] == $zags['ank_id'] && $user['id'] == $zags1['user_id'] OR $user['id'] == $zags1['ank_id']){
echo '<div class="main2"><img src="/style/icons/razvod.gif" alt ""> <a href="/info.php?id='.$ank['id'].'&razv">Развестись</a></div>';
}elseif ($user[id]!==$zags[user_id] && $user[id]!==$zags[ank_id] && $ank[id]!==$uid[user_id] && $ank[id]!==$uid[ank_id] && $ank[id]!==$uid1[user_id] && $ank[id]!==$uid1[ank_id]){
if($ank['pol']==0){

echo '<div class="main2"><img src="/style/icons/meets.gif" alt ""> <a href="/info.php?id='.$ank['id'].'&obruchit">Женится</a></div>';}
if($ank['pol']==1){
echo '<div class="main2"><img src="/style/icons/meets.gif" alt ""> <a href="/info.php?id='.$ank['id'].'&obruchit">Выйти замуж</a></div>';}
}
}

/*-----------------------------настройки-----------------------*/
if (isset($user) && $ank['id']==$user['id']){
echo "<div class='main2'>";
echo "<img src='/style/icons/uslugi.gif' alt='*' /> <a href=\"/user/money/index.php\">Дополнительные услуги</a><br /> \n";echo "<img src='/style/icons/settings.png' alt='*' /> <a href=\"/user/info/settings.php\">Мои настройки</a> | <a href=\"/umenu.php\">Меню</a>\n";
echo "</div>";
}
/*-------------------------------------------------------------*/



/*--------------------------друзья онлайн----------------------*/
$set['p_str']=20;
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` INNER JOIN `user` ON `frends`.`frend`=`user`.`id` WHERE `frends`.`user` = '$ank[id]' AND `frends`.`i` = '1' AND `user`.`date_last`>'".(time()-600)."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];$q = mysql_query("SELECT * FROM `frends` INNER JOIN `user` ON `frends`.`frend`=`user`.`id` WHERE `frends`.`user` = '$ank[id]' AND `frends`.`i` = '1' AND `user`.`date_last`>'".(time()-600)."' ORDER BY `user`.`date_last` DESC LIMIT $start, $set[p_str]");if ($k_post>0)
{
echo "<div class='foot'>Друзья онлайн ($k_post)</div>";
}
while ($post3 = mysql_fetch_assoc($q))
{
$ank3=get_user($post3['frend']);

/*---------зебра---------*/
if ($num==0)
{
echo "  <div class='nav1'>\n";
$num=1;
}
elseif ($num==1)
{
echo "  <div class='nav2'>\n";
$num=0;
}


/*-----------------------*/
echo avatar($ank3['id']);
echo ' <a href="/info.php?id='.$ank3['id'].'">'.$ank3['nick'].'</a>'.medal($ank3['id']).' '.online($ank3['id']).' ('.(($ank3['pol']==1)?'М':'Ж').')<br />';echo '<a href="/mail.php?id='.$ank3['id'].'"><img src="/style/icons/pochta.gif" alt="*" /> Сообщение</a> ';echo "</div>";
}
/*---------------------the end--------------------------*/
?>
</td>
<td class='block_info'>
<?

echo '<table>';

/*---------------------------друзья-----------------------------*/
$k_f = mysql_result(mysql_query("SELECT COUNT(id) FROM `frends_new` WHERE `to` = '$ank[id]' LIMIT 1"), 0);
$k_fr = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' AND `i` = '1'"), 0);
$res = mysql_query("select `frend` from `frends` WHERE `user` = '$ank[id]' AND `i` = '1'");
echo "<div class='top_nav'>";
echo '<img src="/style/icons/druzya.png" alt="" /> <a href="/user/frends/?id='.$ank['id'].'">Друзья</a> ('.$k_fr.'</b>/';$i =0;
while ($k_fr = mysql_fetch_array($res)){
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$k_fr[frend]' && `date_last` > '".(time()-800)."'"),0) != 0) $i++;
}
echo "<span style='color:green'><a href='/user/frends/online.php?id=".$ank['id']."'>$i</a></span>)";
if ($k_f>0 && $ank['id']==$user['id'])echo " <a href='/user/frends/new.php'><font color='red'>+$k_f</font></a>";
echo "</div>";
/*--------------------------------------------------------------*/


/*------------------------фотоальбомы---------------------------*/
echo "<div class='top_nav'>";
echo "<img src='/style/icons/foto.png' alt='*' /> <a href='/foto/$ank[id]/'>Фотографии</a> ";
echo "(" . mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$ank[id]'"),0) . ")";
echo "</div>";
/*--------------------------------------------------------------*/
/*-------------------------Личные файлы---------------------------*/
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '$ank[id]' AND `osn` = '1'"), 0)==0)
{
mysql_query("INSERT INTO `user_files` (`id_user`, `name`,  `osn`) values('$ank[id]', 'Файлы', '1')");
}
$dir_osn = mysql_fetch_assoc(mysql_query("SELECT * FROM `user_files` WHERE `id_user` = '$ank[id]' AND `osn` = '1' LIMIT 1"));
echo "<div class='top_nav'>";
echo "<img src='/style/icons/files.gif' alt='*' /> <a href='/user/personalfiles/$ank[id]/$dir_osn[id]/'>Файлы</a> ";
echo "(" . mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '$ank[id]' AND `osn` > '1'"),0) . "/" . mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `id_user` = '$ank[id]'"),0) . ")";
echo "</div>";
/*----------------------------------------------------------------*/

/*-------------------------Музыка---------------------------------*/
$k_music=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_music` WHERE `id_user` = '$ank[id]'"),0);
echo "<div class='top_nav'>";
echo "<img src='/style/icons/play.png' alt='*' width='16'/> <a href='/user/music/index.php?id=$ank[id]'>Музыка</a> ";
echo "(" . $k_music . ")";
echo "</div>";
/*----------------------------------------------------------------*/

/*---------------------------Дневники------------------------------*/
echo "<div class='top_nav'>";
$kol_dnev=mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE `id_user` = '".$ank['id']."'"),0);
echo "<img src='/style/icons/zametki.gif' alt='*' /> <a href='/plugins/notes/user.php?id=$ank[id]'>Дневники</a> ($kol_dnev)\n";
echo "</div>";
/*----------------------------------------------------------------*/




/*
========================================
Закладки
========================================
*/
echo "<div class='top_nav'>";
$forum=mysql_result(mysql_query("SELECT COUNT(id_user) FROM `forum_zakl` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$people=mysql_result(mysql_query("SELECT COUNT(id_user) FROM `mark_people` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$files=mysql_result(mysql_query("SELECT COUNT(id_user) FROM `mark_files` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$foto=mysql_result(mysql_query("SELECT COUNT(id_user) FROM `mark_foto` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$notes=mysql_result(mysql_query("SELECT COUNT(id_user) FROM `mark_notes` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$java = mysql_result(mysql_query("SELECT COUNT(id_user) FROM `mark_java` WHERE `id_user` = '" . $ank['id'] . "'"),0);
$zakladki = $people + $files + $foto + $notes + $forum + $java;

echo "<img src='/style/icons/fav.gif' alt='*' /> <a href='/user/bookmark/index.php?id=$ank[id]'>Закладки</a> ($zakladki)<br />\n";
echo "</div>";

echo '</table>';

/*
 Вывод анкеты, фото, и стены
*/
echo "<div class='main2'>";
echo " ".group($ank['id'])." $ank[nick]";
echo " ".medal($ank['id'])." ".online($ank['id'])." <font size='small'>".vremja($ank['date_last'])."</font> ";
if ((user_access('user_ban_set') || user_access('user_ban_set_h') || user_access('user_ban_unset')) && $ank['id']!=$user['id'])
echo "<a href='/adm_panel/ban.php?id=$ank[id]'><font color=red>[Бан]</font></a>\n";
echo "</div>";




//-------------статус вывод------------//

if ($status['id'] || $ank['id'] == $user['id'])
{
echo '<div class="st_1"></div>';echo '<div class="st_2">';
if (isset($user) && $user['id']==$ank['id']){echo "<form style='border:none;' action='?id=".$ank['id']."' method=\"post\">";
echo "<input type=\"text\" style='width:80%;' name=\"status\" value=\"\"/> \n";
echo "<input class=\"submit\" style=' width:15%;' type=\"submit\" value=\"+\" />\n";
echo "</form>";}if ($status['id'])echo output_text($status['msg']) . ' <font style="font-size:10px; color:gray;">' . vremja($status['time']) . '</font>';
echo "</div>"; 
if ($status['id'])
{
	echo " <a href='/user/status/komm.php?id=$status[id]'><img src='/style/icons/bbl4.png' alt=''/> " . mysql_result(mysql_query("SELECT COUNT(*) FROM `status_komm` WHERE `id_status` = '$status[id]'"),0) . " </a> ";
	$l=mysql_result(mysql_query("SELECT COUNT(*) FROM `status_like` WHERE `id_status` = '$status[id]'"),0);
if (isset($user) && $user['id']!=$ank['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `status_like` WHERE `id_status` = '$status[id]' AND `id_user` = '$user[id]' LIMIT 1"),0)==0)
{		echo " <a href='/info.php?id=$ank[id]&amp;like'><img src='/style/icons/like.gif' alt='*'/> Класс!</a> • ";
	$like = $l;
}
else if(isset($user) && $user['id']!=$ank['id'])
{
	echo " <img src='/style/icons/like.gif' alt=''/> Вы и ";
	$like = $l-1;
}
else
{
	echo " <img src='/style/icons/like.gif' alt=''/> ";
	$like = $l;
} 
	echo "<a href='/user/status/like.php?id=$status[id]'> $like чел. </a>";
	echo '</div>';} 
	
	/* Общее колличество статусов */
$st = mysql_result(mysql_query("SELECT COUNT(*) FROM `status` WHERE `id_user` = '$ank[id]'"),0);if ($st > 0)
{
	echo "<div class='main2'>"; // пишем свой див
	echo " &rarr; <a href='/user/status/index.php?id=$ank[id]'>Все статусы</a> (" . $st . ")\n";
	echo "</div>";
}
}
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
echo "$date<span class=\"ank_n\">Дата рождения:</span>$a $ank[ank_d_r] $ank[mes] $ank[ank_g_r]г. <br />\n";
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
	echo '<form action="someplace.html" method="post" name="myForm"><div id="formResponse">';	
	echo ' <a onclick="anketa.submit()" name="myForm"><div class="form_info">Показать подробную информацию</div></a>';	
	echo '</div></form>';	
	echo "<script type='text/javascript'>	
	var anketa = new DHTMLSuite.form({ formRef:'myForm',action:'/ajax/php/anketa.php?id=$ank[id]',responseEl:'formResponse'});	
	var anketaClose = new DHTMLSuite.form({ formRef:'myForm',action:'/ajax/php/anketa.php',responseEl:'formResponse'});
		</script>";
	
	
/*
========================================
Подарки
========================================
*/  
$k_p = mysql_result(mysql_query("SELECT COUNT(id) FROM `gifts_user` WHERE `id_user` = '$ank[id]' AND `status` = '1'"),0);$width = ($webbrowser == 'web' ? '60' : '45'); // Размер подарков при выводе в браузер

if ($k_p > 0)
{		echo '<div class="foot">';
	echo '&rarr; <a href="/user/gift/index.php?id=' . $ank['id'] . '">Все подарки</a> (' . $k_p . ')';
	echo '</div>';
	
	
	$q = mysql_query("SELECT id,id_gift,status FROM `gifts_user` WHERE `id_user` = '$ank[id]' AND `status` = '1' ORDER BY `id` DESC LIMIT 7");
	echo '<div class="nav2">';
	while ($post = mysql_fetch_assoc($q))
	{
		$gift = mysql_fetch_assoc(mysql_query("SELECT id FROM `gift_list` WHERE `id` = '$post[id_gift]' LIMIT 1"));
		echo '<a href="/user/gift/gift.php?id=' . $post['id'] . '"><img src="/sys/gift/' . $gift['id'] . '.png" style="max-width:' . $width . 'px;" alt="Подарок" /></a> ';
	}
	echo '</div>';
	}
	
/*
=====================================
Стена юзвера
=====================================
*/

echo "<div class='foot'>";
if (isset($user))
{
echo "<img src='/style/icons/stena.gif' alt='*' /> ";	if ($user['wall']==0)
	echo "<a href='/info.php?id=$ank[id]&amp;wall=1'>Стена</a>\n";
	else
	echo "<a href='/info.php?id=$ank[id]&amp;wall=0'>Стена</a>\n";
	}else{
	echo "<img src='/style/icons/stena.gif' alt='*' /> Стена";
	}
echo "</div>";if ($user['wall']==0){
include_once 'user/stena/index.php';
}
/*--------------------------------------------------------------*/
?>
</td>
</tr>
</table>
