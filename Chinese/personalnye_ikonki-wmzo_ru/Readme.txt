安装:
安装：

В user/money/index.php 写:


echo '<div class="nav2">';
$c2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `user_icons` WHERE `id_user` = '$user[id]' AND  `time` > '".(time()-604800)."'"), 0);
echo '&rarr; <a href="icons.php">个人图标</a> ' . 
	 ($c2 == 0 ? '<span class="off">[未购买]</span> ' : '<span class="on">[' . user::avatar($user['id'], 2) . ' 购买]</span>');
echo '</div>';