<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
include_once 'inc/configs.php';
$set['title'] = 'Гостевая';
if (isset($user))$mdp = md5($user['pass']);
else $mdp = md5(0);
$act = htmlspecialchars(@$_GET['act']);
if (is_file("inc/act.".$act.".php"))include("inc/act.".$act.".php");
else include("inc/act.index.php");
?>