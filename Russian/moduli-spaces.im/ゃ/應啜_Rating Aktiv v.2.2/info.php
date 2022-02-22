<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';


if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);


if ($ank['id']==0)
{
$ank=get_user($ank['id']);
$set['title']=$ank['nick'].' - анкета '; // заголовок страницы
include_once 'sys/inc/thead.php';

title();
aut();

////Зачисление рейтинга за просмотр анкеты////
 if (isset($user) && $user['id']!=$ank['id'])
 {mysql_query("UPDATE `user` SET `akt_rating` = '".($ank['akt_rating']+0.001)."' WHERE `id` = '$ank[id]' LIMIT 1");}
/////////////////////////////////////////////

echo "<span class=\"status\">$ank[group_name]</span><br />\n";

if ($ank['ank_o_sebe']!=NULL)echo "<span class=\"ank_n\">О себе:</span> <span class=\"ank_d\">$ank[ank_o_sebe]</span><br />\n";




if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "<div class='foot'>&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n</div>\n";

include_once 'sys/inc/tfoot.php';
exit;
}

$ank=get_user($ank['id']);
if(!$ank){header("Location: /index.php?".SID);exit;}








$ank['rating']=intval(@mysql_result(mysql_query("SELECT SUM(`rating`) FROM `user_voice2` WHERE `id_kont` = '$ank[id]'"),0));
$set['title']=$ank['nick'].' - анкета '; // заголовок страницы
include_once 'sys/inc/thead.php';
title();


if ((!isset($_SESSION['refer']) || $_SESSION['refer']==NULL)
&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL &&
!ereg('info\.php',$_SERVER['HTTP_REFERER']))
$_SESSION['refer']=str_replace('&','&amp;',ereg_replace('^http://[^/]*/','/', $_SERVER['HTTP_REFERER']));


if (isset($_POST['rating']) && isset($user)  && $user['id']!=$ank['id'] && $user['balls']>=50 && mysql_result(mysql_query("SELECT SUM(`rating`) FROM `user_voice2` WHERE `id_kont` = '$user[id]'"),0)>=0)
{
$new_r=min(max(@intval($_POST['rating']),-2),2);
mysql_query("DELETE FROM `user_voice2` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' LIMIT 1");

if ($new_r)
mysql_query("INSERT INTO `user_voice2` (`rating`, `id_user`, `id_kont`) VALUES ('$new_r','$user[id]','$ank[id]')");
$ank['rating']=intval(mysql_result(mysql_query("SELECT SUM(`rating`) FROM `user_voice2` WHERE `id_kont` = '$ank[id]'"),0));
mysql_query("UPDATE `user` SET `rating` = '$ank[rating]' WHERE `id` = '$ank[id]' LIMIT 1");

if ($new_r>0)
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] оставил положительный отзыв в [url=/who_rating.php]Вашей анкете[/url]', '$time')");
if ($new_r<0)
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] оставил негативный отзыв в [url=/who_rating.php]Вашей анкете[/url]', '$time')");
if ($new_r==0)
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] оставил нейтральный отзыв в [url=/who_rating.php]Вашей анкете[/url]', '$time')");



msg('Ваше мнение о пользователе успешно изменено');
}
aut();



if ($ank['group_access']>1)echo "<span class='status'>$ank[group_name]</span><br />\n";


avatar($ank['id']);

echo "<span class='ank_n'>ID:</span> <span class='ank_d'>$ank[id]</span><br />\n";

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '$ank[id]' AND `time` > '$time'"), 0)!=0)
{
$q=mysql_query("SELECT * FROM `ban` WHERE `id_user` = '$ank[id]' AND `time` > '$time' ORDER BY `time` DESC LIMIT 5");
while ($post = mysql_fetch_assoc($q))
{
echo "<span class='ank_n'>Забанен до ".vremja($post['time']).":</span>\n";
echo "<span class='ank_d'>".output_text($post['prich'])."</span><br />\n";
}
}
else
{
$narush=mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `id_user` = '$ank[id]'"), 0);
echo "<span class='ank_n'>Нарушений:</span>".(($narush==0)?" <span class='ank_d'>нет</span><br />\n":" <span class=\"ank_d\">$narush</span><br />\n");
}




if ($ank['ank_name']!=NULL)echo "<span class=\"ank_n\">Имя:</span> <span class=\"ank_d\">$ank[ank_name]</span><br />\n";

echo "<span class=\"ank_n\">Пол:</span> <span class=\"ank_d\">".(($ank['pol']==1)?'Мужской':'Женский')."</span><br />\n";


if ($ank['ank_city']!=NULL)echo "<span class=\"ank_n\">Город:</span> <span class=\"ank_d\">$ank[ank_city]</span><br />\n";

if ($ank['ank_d_r']!=NULL && $ank['ank_m_r']!=NULL && $ank['ank_g_r']!=NULL){
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
echo "<span class=\"ank_n\">Дата рождения:</span> <span class=\"ank_d\">$ank[ank_d_r] $ank[mes] $ank[ank_g_r]г.</span><br />\n";
$ank['ank_age']=date("Y")-$ank['ank_g_r'];
if (date("n")<$ank['ank_m_r'])$ank['ank_age']=$ank['ank_age']-1;
elseif (date("n")==$ank['ank_m_r']&& date("j")<$ank['ank_d_r'])$ank['ank_age']=$ank['ank_age']-1;
echo "<span class=\"ank_n\">Возраст:</span> <span class=\"ank_d\">$ank[ank_age]</span><br />\n";
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
echo "<span class=\"ank_n\">День рождения:</span> <span class=\"ank_d\">$ank[ank_d_r] $ank[mes]</span><br />\n";
}

if ($ank['ank_icq']!=NULL && $ank['ank_icq']!=0)
echo "<img src=\"http://web.icq.com/whitepages/online?icq=$ank[ank_icq]&amp;img=27\" alt=\"icq\" height=\"16\" width=\"16\" /> <span class=\"ank_d\">$ank[ank_icq]</span><br />\n";



if ($ank['ank_mail']!=NULL && ($ank['set_show_mail']==1 || isset($user) && ($user['level']>$ank['level'] || $user['level']==4))){

if ($ank['set_show_mail']==0)$hide_mail=' (скрыт)';else $hide_mail=NULL;

if (ereg("(@mail\.ru$)|(@bk\.ru$)|(@inbox\.ru$)|(@list\.ru$)", $ank['ank_mail']))
echo "<img src=\"http://status.mail.ru/?$ank[ank_mail]\" width=\"13\" height=\"13\" alt=\"\" /> <a href=\"mailto:$ank[ank_mail]\" title=\"Написать письмо\" class=\"ank_d\">$ank[ank_mail]</a>$hide_mail<br />\n";
else echo "<span class=\"ank_n\">E-mail:</span> <a href=\"mailto:$ank[ank_mail]\" title=\"Написать письмо\" class=\"ank_d\">$ank[ank_mail]</a>$hide_mail<br />\n";
}

if ($ank['ank_n_tel']!=NULL)echo "<span class=\"ank_n\">Телефон:</span> <span class=\"ank_d\">$ank[ank_n_tel]</span><br />\n";
if ($ank['ank_o_sebe']!=NULL)echo "<span class=\"ank_n\">О себе:</span> <span class=\"ank_d\">$ank[ank_o_sebe]</span><br />\n";





$chat_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_post` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Сообщений в чате:</span> <span class=\"ank_d\">$chat_post</span><br />\n";

$k_them=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Тем в форуме:</span> <span class=\"ank_d\">$k_them</span><br />\n";

$k_p_forum=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Сообщений в форуме:</span> <span class=\"ank_d\">$k_p_forum</span><br />\n";



if (user_access('user_show_add_info') && $user['level']>$ank['level']){
$zakl3=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_user` = '$ank[id]'"),0);
if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
echo "<span class=\"ank_n\">Закладки:</span> <span class=\"ank_d\">$zakl3</span><br />\n";

$guest3=mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Гостевая:</span> <span class=\"ank_d\">$guest3</span><br />\n";


$mail3=mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$ank[id]' OR `id_kont` = '$ank[id]'"),0);
if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
echo "<span class=\"ank_n\">Приватные сообщения:</span> <span class=\"ank_d\">$mail3</span><br />\n";
}
$komm_loads=mysql_result(mysql_query("SELECT COUNT(*) FROM `loads_komm` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Комментарии в загрузках:</span> <span class=\"ank_d\">$komm_loads</span><br />\n";

$news_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `news_komm` WHERE `id_user` = '$ank[id]'"),0);
echo "<span class=\"ank_n\">Комментарии новостей:</span> <span class=\"ank_d\">$news_komm</span><br />\n";


if (isset($access['show_all_info'])){
$user_voice=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_voice2` WHERE `id_user` = '$ank[id]' OR `id_kont` = '$ank[id]'"),0);
if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
echo "<span class=\"ank_n\">Рейтинги:</span> <span class=\"ank_d\">$user_voice</span><br />\n";

$votes_user=mysql_result(mysql_query("SELECT COUNT(*) FROM `votes_user` WHERE `u_id` = '$ank[id]'"),0);
if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
echo "<span class=\"ank_n\">Голосования:</span> <span class=\"ank_d\">$votes_user</span><br />\n";

$obmennik3=mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE `id_user` = '$ank[id]'"),0);
if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
echo "<span class=\"ank_n\">Файлы в обменнике:</span> <span class=\"ank_d\">$obmennik3</span><br />\n";
}

echo "<span class=\"ank_n\">Баллы:</span> <span class=\"ank_d\">$ank[balls]</span><br />\n";

echo "<span class=\"ank_n\">Рейтинг:</span> <span class=\"ank_d\">$ank[rating]</span>".($ank['id']==$user['id']?" <a href='/who_rating.php'>[?]</a>":null)."<br />\n";


//////Рейтинг активности (Вывод)
$activ_plus = ($news_komm + $votes_user + $obmennik3 + $ank[rating] + $guest3 + $k_them)/1000 + $ank['akt_rating'];
$activ_plus = substr("$activ_plus", 0, 4);
if ($ank['aktiv_minus']==0){
echo "<img src='/img/end.png' alt='' class='icon' /> \n";
}else{
echo "<img src='/img/top.png' alt='' class='icon' /> \n";
}
echo "<span class=\"ank_n\">Рейтинг активности:</span> <span class=\"ank_d\">$activ_plus</span>".($ank['id']==$user['id']?" <a href='/info_aktiv.php'>[?]</a>":null)."<br />\n";
//////
if (isset($user)  && $user['id']!=$ank['id'] && $user['balls']>=50 && mysql_result(mysql_query("SELECT SUM(`rating`) FROM `user_voice2` WHERE `id_kont` = '$user[id]'"),0)>=0)
{
echo "<b>Ваше отношение:</b><br />\n";
// мое отношение к пользователю
$my_r=intval(@mysql_result(mysql_query("SELECT `rating` FROM `user_voice2` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"),0));
echo "<form method='post' action='?id=$ank[id]&amp;$passgen'>\n";
echo "<select name='rating'>\n";
echo "<option value='2' ".($my_r==2?'selected="selected"':null).">Замечательное</option>\n";
echo "<option value='1' ".($my_r==1?'selected="selected"':null).">Положительное</option>\n";
echo "<option value='0' ".($my_r==0?'selected="selected"':null).">Нейтральное</option>\n";
echo "<option value='-1' ".($my_r==-1?'selected="selected"':null).">Не очень...</option>\n";
echo "<option value='-2' ".($my_r==-2?'selected="selected"':null).">Негативное</option>\n";
echo "</select>\n";
echo "<input type='submit' value='GO' />\n";
echo "</form>\n";
//echo "<br />\n";
}


$opdirbase=@opendir(H.'sys/add/info');
while ($filebase=@readdir($opdirbase))
if (eregi('\.php$',$filebase))
include_once(H.'sys/add/info/'.$filebase);

echo "<span class=\"ank_n\">Регистрация:</span> <span class=\"ank_d\">".vremja($ank['date_reg'])."</span><br />\n";
echo "<span class=\"ank_n\">Посл. посещение:</span> <span class=\"ank_d\">".vremja($ank['date_last'])."</span><br />\n";

if ($user['level']>$ank['level']){

if ($ank['ip']!=NULL){
if (user_access('user_show_ip') && $ank['ip']!=0){
echo "<span class=\"ank_n\">IP:</span> <span class=\"ank_d\">".long2ip($ank['ip'])."</span>";
if (user_access('adm_ban_ip'))
echo " [<a href='/adm_panel/ban_ip.php?min=$ank[ip]'>Бан</a>]";
echo "<br />\n";
}
}

if ($ank['ip_cl']!=NULL){
if (user_access('user_show_ip') && $ank['ip_cl']!=0){
echo "<span class=\"ank_n\">IP (CLIENT):</span> <span class=\"ank_d\">".long2ip($ank['ip_cl'])."</span>";
if (user_access('adm_ban_ip'))
echo " [<a href='/adm_panel/ban_ip.php?min=$ank[ip_cl]'>Бан</a>]";
echo "<br />\n";
}
}

if ($ank['ip_xff']!=NULL){
if (user_access('user_show_ip') && $ank['ip_xff']!=0){
echo "<span class=\"ank_n\">IP (XFF):</span> <span class=\"ank_d\">".long2ip($ank['ip_xff'])."</span>";
if (user_access('adm_ban_ip'))
echo " [<a href='/adm_panel/ban_ip.php?min=$ank[ip_xff]'>Бан</a>]";
echo "<br />\n";
}
}




if (user_access('user_show_ua') && $ank['ua']!=NULL)
echo "<span class=\"ank_n\">UA:</span> <span class=\"ank_d\">$ank[ua]</span><br />\n";
if (user_access('user_show_ip') && opsos($ank['ip']))
echo "<span class=\"ank_n\">Пров:</span> <span class=\"ank_d\">".opsos($ank['ip'])."</span><br />\n";
if (user_access('user_show_ip') && opsos($ank['ip_cl']))
echo "<span class=\"ank_n\">Пров (CL):</span> <span class=\"ank_d\">".opsos($ank['ip_cl'])."</span><br />\n";
if (user_access('user_show_ip') && opsos($ank['ip_xff']))
echo "<span class=\"ank_n\">Пров (XFF):</span> <span class=\"ank_d\">".opsos($ank['ip_xff'])."</span><br />\n";


}




if ($ank['show_url']==1)
{
if (otkuda($ank['url']))echo "<span class=\"ank_n\">URL:</span> <span class=\"ank_d\"><a href='$ank[url]'>".otkuda($ank['url'])."</a></span><br />\n";
}
if (user_access('user_collisions') && $user['level']>$ank['level'])
{
$mass[0]=$ank['id'];
$collisions=user_collision($mass);


if (count($collisions)>1)
{
echo "<span class=\"ank_n\">Возможные ники:</span><br />\n";
echo "<span class=\"ank_d\">\n";

for ($i=1;$i<count($collisions);$i++)
{
$ank_coll=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$collisions[$i]' LIMIT 1"));
echo "\"<a href='/info.php?id=$ank_coll[id]'>$ank_coll[nick]</a>\"<br />\n";
}

echo "</span>\n";
}
}
if (user_access('adm_ref') && ($ank['level']<$user['level'] || $user['id']==$ank['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user_ref` WHERE `id_user` = '$ank[id]'"), 0))
{
$q=mysql_query("SELECT * FROM `user_ref` WHERE `id_user` = '$ank[id]' ORDER BY `time` DESC LIMIT $set[p_str]");
echo "Посещаемые сайты:<br />\n";
while ($url=mysql_fetch_assoc($q)) {
$site=htmlentities($url['url'], ENT_QUOTES, 'UTF-8');
echo "<a".($set['web']?" target='_blank'":null)." href='/go.php?go=".base64_encode("http://$site")."'>$site</a> (".vremja($url['time']).")<br />\n";
}
echo "<br />\n";
}


echo "<div class='foot'>\n";
if (isset($user) && $user['id']!=$ank['id'])echo "&raquo;<a href=\"/mail.php?id=$ank[id]\">Написать в приват</a><br />\n";
if (isset($user) && $user['id']==$ank['id'])echo "&raquo;<a href=\"/anketa.php\">Изменить анкету</a><br />\n";


if ($user['level']>$ank['level']){


if (user_access('user_prof_edit'))
echo "&raquo;<a href='/adm_panel/user.php?id=$ank[id]'>Редактировать профиль</a><br />\n";



if ($user['id']!=$ank['id']){



if (user_access('user_ban_set') || user_access('user_ban_set_h') || user_access('user_ban_unset'))
echo "&raquo;<a href='/adm_panel/ban.php?id=$ank[id]'>Нарушения (бан)</a><br />\n";





if (user_access('user_delete'))
{

echo "&raquo;<a href='/adm_panel/delete_user.php?id=$ank[id]'>Удалить пользователя</a>";
if (count(user_collision($mass,1))>1)
echo " (<a href='/adm_panel/delete_user.php?id=$ank[id]&amp;all'>Все ники</a>)";
echo "<br />\n";

}
}
}

if (user_access('adm_log_read') && $ank['level']!=0 && ($ank['id']==$user['id'] || $ank['level']<$user['level']))
echo "&raquo;<a href='/adm_panel/adm_log.php?id=$ank[id]'>Отчет по администрированию</a><br />\n";

if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";

echo "</div>\n";
include_once 'sys/inc/tfoot.php';
?>