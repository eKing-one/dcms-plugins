Фотоальбомы одноклассников
Автор alex-borisi (Искатель)
ICQ 587863132

Установка:

Распаковать в корень

Залить таблицы (Перейти по адресу ваш сайт/foto/install.php либо в ручную..)

В info.php прописать

if ($user['id']==$ank['id']){
echo '<div class="p_t">';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `avtor` = '$ank[id]' AND `ready` = '1'"),0)!=0){
echo "<a href='/foto/ocenky.php?id=$ank[id]'><font color='red'>Оценки +" . mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `avtor` = '$ank[id]' AND `ready` = '1'"),0) . "</font></a><br />\n";
}else{
echo "<a href='/foto/ocenky.php?id=$ank[id]'>Оценки</a><br />\n";
}
echo '</div>';
} // ссылка на оценки



//это туда где у вас услуги за баллы)

$c2=mysql_result(mysql_query("SELECT COUNT(*) FROM `ocenky` WHERE `id_user` = '$user[id]' AND `time` > '$time'"), 0);
echo "&rarr; <a href='/user/money/plus5.php'>Оценка</a> <img src='/style/icons/6.png' alt='*'> " . ($c2==0?'<span class="off">[отключена]</span> ':'<span class="on">[включена]</span>')."";
