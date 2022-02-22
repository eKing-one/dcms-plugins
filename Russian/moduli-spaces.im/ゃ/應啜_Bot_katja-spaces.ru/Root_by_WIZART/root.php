<?php
mysql_query("UPDATE `user` SET `date_last` = '$time' WHERE `id` = '$set[roobot]'");
$root=get_user($set['roobot']);
echo "<div class='mess'><table><td>";
echo "".status($root['id'])."";
echo "</td><td>";
echo " ".group($root['id'])." <a href='/info.php?id=$root[id]' title='Анкета $root[nick]'>$root[nick]</a>";
echo medal($root['id'])." ".online($root['id']);
echo "<br/>Привет! Пообщаемся???<br/><a href='/root/'><img src='/style/icons/msg.gif' alt='W'> Написать</a></td></table></div>";
?>