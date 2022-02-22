<?
if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_komm` WHERE `id_file` = '$file_id[id]' AND `id_user` = '$user[id]' AND `msg` = '".mysql_escape_string($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

if ($ank['id']!=$user['id']) // уведомление
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank[id]', '$user[nick] оставил комментарий к [url=/obmen$dir_id[dir]".urlencode($file_id['name']).".$file_id[ras]?komm]Вашему файлу[/url]', '$time')");


mysql_query("INSERT INTO `obmennik_komm` (`id_file`, `id_user`, `time`, `msg`) values('$file_id[id]', '$user[id]', '$time', '".my_esc($msg)."')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
}
}



err();
aut();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_komm` WHERE `id_file` = '$file_id[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет комментариев\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `obmennik_komm` WHERE `id_file` = '$file_id[id]' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[id_user]' LIMIT 1"));
$ank=get_user($post['id_user']);
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($ank['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank[pol].png' alt='' />";
echo "  </td>\n";
}



echo "  <td class='p_t'>\n";
echo "<a href='/info.php?id=$ank[id]'>\n";
echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "".online($ank['id'])."<a> (".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['msg']))))))))."<br />\n";

if (user_access('obmen_komm_del'))
echo "<a href='?komm&amp;page=$page&amp;del_post=$post[id]'>Удалить</a><br />\n";

echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?komm&amp;',$k_page,$page); // Вывод страниц


if (isset($user))
{

echo "<form method=\"post\" name='message' action=\"?komm\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";

}

?>