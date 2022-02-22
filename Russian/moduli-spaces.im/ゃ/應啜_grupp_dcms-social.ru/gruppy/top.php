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
$set['title']='Топ сообществ'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="err">';
echo 'Нет сообществ';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy` ORDER BY `users` DESC LIMIT $start, $set[p_str]");
while ($comm = mysql_fetch_assoc($q))
{
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_cat` WHERE `id` = '$comm[id_cat]' LIMIT 1"));
echo '<tr>';
echo '<div class="mess">';
if($comm['konf_gruppy']==0 || $comm['konf_gruppy']==1)echo'<img src="img/open.png" alt="open"/> '; elseif($comm['konf_gruppy']==2)echo'<img src="img/money.png" alt="money"/> '; else echo'<img src="img/close.png" alt="close"/> ';
//echo '</td>';
echo '<b><a href="/gruppy/'.$comm['id'].'">'.$comm['name'].'</a></b>';
if($comm['ban']!=NULL && $comm['ban']>$time)echo'[BAN]';
$count = $comm['users']+1;
echo' - Участников в группе: '.$count.'';
echo '</div>';
echo '</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';


echo '<span class="ank_n">Описание:</span> <span class="ank_d">'.output_text($comm['desc']).'</span><br />';
echo '<span class="ank_n">Раздел:</span> <u><a href="index.php?r='.$razdel['id'].'">'.$razdel['name'].'</a></u><br/>';
$admid=get_user($comm['admid']);
if(isset($user) && $user['level']>=3 && $user['id']!=$comm['admid'] && $user['level']>$admid['level'])
{
echo'[<a href="ban.php?s='.$comm['id'].'">Нарушения</a>]';
}
if(isset($user) && $user['level']>3 && ($user['id']!=$comm['admid'] && $user['level']>$admid['level'] || $user['id']==$comm['admid']))
{
echo'[<a href="?r='.$r.'&del='.$comm['id'].'"><img src="img/action_delete.png" alt=""/></a>]';
}

echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt=""/> <a href="/gruppy/">Сообщества</a><br/>';
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>
