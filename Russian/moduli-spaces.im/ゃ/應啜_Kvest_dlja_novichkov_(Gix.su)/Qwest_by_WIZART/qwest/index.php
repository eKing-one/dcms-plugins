<?php
/*
Автор: WIZART
e-mail: bi3apt@gmail.com
icq: 617878613
Сайт: WizartWM.RU
Сайт2: Krasavo.RU
*/
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
if ($user['qwest']!=0){
header("location:/index.php");
exit();
}
$set['title']="Квест новичка";
include_once H.'sys/inc/thead.php';
only_reg();
title();
aut();
if (isset($_GET['avatar'])){
echo "<div class='main'><center>Установить аватар ".(mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '".$user['id']."' AND `avatar` = '1'"),0)==0?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для того чтобы установить аватар загрузите фотографию в ваш фотоальбом и нажмите под фотографией на ссылку >Сделать главной< .</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Установить аватар</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['anketa'])){
echo "<div class='main'><center>Заполнить анкету ".($user['ank_name']==NULL || $user['ank_o_sebe']==NULL || $user['ank_city']==NULL || $user['ank_d_r']==0 || $user['ank_m_r']==0 || $user['ank_g_r']==0?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для выполнения этого пункта у вас должны быть заполнены в анкете следущие пункты:<br/><b>Имя</b><br/><b>Дата рождения</b><br/><b>Город</b><br/><b>О себе</b></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Заполнить анкету</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['frend'])){
echo "<div class='main'><center>Найти друзей ".(mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '".$user['id']."' AND `i` = '1'"),0)==0?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для выполнения этого пункта просто добавьте кого-то в друзья ;)</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Найти друзей</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['file'])){
echo "<div class='main'><center>Добавить файл ".(mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '".$user['id']."' AND `osn`>'1'"),0)==0?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для выполнения этого пункта загрузите какой нибудь файл, например свое любимое видео, красивую картинку или песню которая вам нравиться.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Добавить файл</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['status'])){
echo "<div class='main'><center>Установить статус ".(mysql_result(mysql_query("SELECT COUNT(*) FROM `status` WHERE `id_user` = '".$user['id']."'"),0)==0?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для выполнения этого пункта установите статус на вашей личной странице.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Установить статус</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['online'])){
echo "<div class='mess'><center>Освоится на сайте ".($user['time']<3600?" <font color='red'>(Не выполнено)</font> ":" <font color='green'>(Выполнено)</font> ")."</center></div><div class='st_1'></div><div class='st_2'>Для того чтоб освоится на сайте проведите на сайте более часа, в это время вы можете поискать тут что-то интересное для вас. Например найти новых друзей, просмотреть фотографии, по общатся в чате или форуме.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Освоится на сайте</b></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['present'])){
if ($user['time']<3600 || mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '".$user['id']."' AND `avatar` = '1'"),0)==0 || $user['ank_name']==NULL || $user['ank_o_sebe']==NULL || $user['ank_city']==NULL || $user['ank_d_r']==0 || $user['ank_m_r']==0 || $user['ank_g_r']==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '".$user['id']."' AND `i` = '1'"),0)==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '".$user['id']."' AND `osn`>'1'"),0)==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `status` WHERE `id_user` = '".$user['id']."'"),0)==0){
echo "<div class='main'><center>Получить подарок <font color='red'>(Не выполнено)</font></center></div><div class='st_1'></div><div class='st_2'>Для выполнения этого чтобы получить подарок вы должны выполнить все пункты квеста для новичков. На данный момент выполнены не все пункты и вы не можете получить подарок</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='?$passgen'>Квест новичка</a> | <b>Получить подарок</b></div>";
} else {
mysql_query("UPDATE `user` SET `money` = '".($user['money']+10)."', `qwest` = '1' WHERE `id` ='".$user['id']."' LIMIT 1");
echo "<div class='main'><center>Получить подарок <font color='green'>(Выполнено)</font></center></div><div class='st_1'></div><div class='st_2'>Вы выполнили все пункты квеста для новичков и успешно получили подарок, на ваш счет зачислено 10 монет.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='info.php'>Моя страница</a></div>";
}
include_once H.'sys/inc/tfoot.php';
}
if (isset($_GET['next'])){
mysql_query("UPDATE `user` SET `qwest` = '1' WHERE `id` ='".$user['id']."' LIMIT 1");
echo "<div class='main'><center>Отказ от показа квеста <font color='green'>(Выполнено)</font></center></div><div class='st_1'></div><div class='st_2'>Вы успешно отказались от квеста.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt='*'> <a href='info.php'>Моя страница</a></div>";
include_once H.'sys/inc/tfoot.php';
}
echo "<div class='mess'>Привет $user[nick] , чтобы пройти квест новичка и получить подарок выполните следущие действия:</div>";
echo "<div class='main'>".(mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '".$user['id']."' AND `avatar` = '1'"),0)==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?avatar'>Установить аватар</a></div>";
echo "<div class='main'>".($user['ank_name']==NULL || $user['ank_o_sebe']==NULL || $user['ank_city']==NULL || $user['ank_d_r']==0 || $user['ank_m_r']==0 || $user['ank_g_r']==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?anketa'>Заполнить анкету</a></div>";
echo "<div class='main'>".(mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '".$user['id']."' AND `i` = '1'"),0)==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?frend'>Найти друзей</a></div>";
echo "<div class='main'>".(mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '".$user['id']."' AND `osn`>'1'"),0)==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?file'>Добавить файл</a></div>";
echo "<div class='main'>".(mysql_result(mysql_query("SELECT COUNT(*) FROM `status` WHERE `id_user` = '".$user['id']."'"),0)==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?status'>Установить статус</a></div>";
echo "<div class='main'>".($user['time']<3600?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?online'>Освоится на сайте</a></div>";
echo "<div class='main'>".($user['time']<3600 || mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '".$user['id']."' AND `avatar` = '1'"),0)==0 || $user['ank_name']==NULL || $user['ank_o_sebe']==NULL || $user['ank_city']==NULL || $user['ank_d_r']==0 || $user['ank_m_r']==0 || $user['ank_g_r']==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '".$user['id']."' AND `i` = '1'"),0)==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `user_files` WHERE `id_user` = '".$user['id']."' AND `osn`>'1'"),0)==0 || mysql_result(mysql_query("SELECT COUNT(*) FROM `status` WHERE `id_user` = '".$user['id']."'"),0)==0?"<img src='/qwest/img/no.png' alt='(-)'> ":"<img src='/qwest/img/yes.png' alt='(+)'> ")."<a href='?present'>Получить подарок (10 монет)</a></div>";
echo "<div class='main'><img src='img/no.png' alt='(-)'> <a href='?next'><font color='red'>Отказ от квеста</font></a></div>";
include_once H.'sys/inc/tfoot.php';
?>