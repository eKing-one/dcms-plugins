<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/adm_check.php';
include_once '../../sys/inc/user.php';

if (!isset($user))header("location: /index.php?");

if (isset($user) && isset($_GET['ok']))
{
	$icon = mysql_fetch_assoc(mysql_query("SELECT * FROM `user_icons` WHERE `id` = '".intval($_GET['ok'])."' LIMIT 1"));
	
	if ($user['money'] < 10)
	$err[] = '你没有足够的资金。';
	
	if ($icon['time'] > time()-604800)
	$err[] = '这个图标已经买了。';
	
	if (!$icon['id'])
	$err[] = '此图标不存在';
	
	if ($icon['time'] < time()-604800 && $icon['id_user'] == $user['id'])
	$err[] = '你已经买了这个图标。';
	
	if (!isset($err))
	{
		mysql_query("UPDATE `user_icons` SET `id_user` = '0', `time` = '0' WHERE `id_user` = '$user[id]' LIMIT 1");	
		mysql_query("UPDATE `user_icons` SET `id_user` = '$user[id]', `time` = '$time' WHERE `id` = '$icon[id]' LIMIT 1");
		mysql_query("UPDATE `user` SET `money` = '".($user['money'] - 10)."' WHERE `id` = '$user[id]' LIMIT 1");	
		
		$_SESSION['message'] = '图标已成功购买';
		header('Location: ?page=' . intval($_GET['page']));
		exit;
	}
}

$set['title'] = '个人图标';
include_once '../../sys/inc/thead.php';
title();

err();
aut();

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> ' . user::nick($user['id']) . ' | 个人图标<br />';
echo '</div>';

echo '<div class="mess">';
echo '个人图标的价值为10枚硬币，购买后，您的图标将保留7天。<br />';
echo '我们的数据库只有50个图标，请立即选择您的图标！<br />';
echo '当前图标 ' . user::avatar($user['id'], 2) . user::nick($user['id'], 1,1,1);
echo '</div>';

$set['p_str'] = 10;

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `user_icons`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

echo '<table class="post">';

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo '数据库中没有可用的图标';
	echo '</div>';
}


$q = mysql_query("SELECT * FROM `user_icons` ORDER BY id DESC LIMIT  $start, $set[p_str]");

while ($post = mysql_fetch_assoc($q))
{
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;
	
	echo '<img src="/style/personal_icons/i (' . $post['id'] . ').png" alt="*" /> ' . user::nick($user['id']);
	
	if ($post['time'] < time()-604800)
	echo ' [<a href="?ok=' . $post['id'] . '&page=' . $page . '">购买</a>]';
	elseif ($post['time'] > time()-604800 && $user['id'] == $post['id_user'])
	echo ' [<img src="/style/icons/ok.gif" /> 购买]';
	else
	echo ' [<img src="/style/icons/delete.gif" /> 已经有人买了]';
	
	echo '</div>';
}

echo '</table>';

if ($k_page>1)str('?',$k_page,$page); // Вывод страниц

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> ' . user::nick($user['id']) . ' | 个人图标<br />';
echo '</div>';

include_once '../../sys/inc/tfoot.php';
?>