<?php
include_once('../../sys/inc/home.php');
include_once(H.'sys/inc/start.php');
include_once(H.'sys/inc/compress.php');
include_once(H.'sys/inc/sess.php');
include_once(H.'sys/inc/settings.php');
include_once(H.'sys/inc/db_connect.php');
include_once(H.'sys/inc/ipua.php');
include_once(H.'sys/inc/fnc.php');
include_once(H.'sys/inc/user.php');
$set['title'] = 'Действия';
if (isset($user))$mdp = md5("Killer$user[pass]");
$inc_files = array();
$dopen = opendir("inc");
while ($inc_file = readdir($dopen)) {
	if (!preg_match("|^act\.(.*)\.php|", $inc_file, $tmp_inc_file)) continue;
	$inc_files[] = $tmp_inc_file[1];
}
closedir($dopen);
$get_inc_file = htmlspecialchars(@$_GET['act']);
if (!in_array($get_inc_file, $inc_files))
	$get_inc_file = 'index';
include_once('inc/act.'.$get_inc_file.'.php');
include_once(H.'sys/inc/tfoot.php');
?>