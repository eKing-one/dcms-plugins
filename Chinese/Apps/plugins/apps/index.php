<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';


if (isset($user) && !isset($_GET['func']) && dbresult(dbquery("SELECT COUNT(id_apps) FROM `user_apps`"), 0) > 0) {
	$func = 'user';
} else {
	$func = (isset($_GET['func']) ? text($_GET['func']) : 'list');
}


if (test_file('inc/' . $func . '.php')) {
	require 'inc/' . $func . '.php';
} else {
	header('Location: /index.php');
	exit;
}

include_once '../../sys/inc/tfoot.php';
?>
