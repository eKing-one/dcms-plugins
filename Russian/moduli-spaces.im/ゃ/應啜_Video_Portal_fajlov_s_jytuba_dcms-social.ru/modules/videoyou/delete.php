<?

######################################################
#   Видео Портал файлов с ютуба dcms 6.6.4 и фиера   #         
#   Автор: Saint, JumanG.ru  & wmsait.ru  	    	 #
#   icq: 399537814, сайт: http://DCMS-FIERA.ru     	 #
#   Вы не имеете права продавать, распростронять,	 #
#   давать друзьям даный скрипт.                 	 #
#   Даная версия являет платной, и купить       	 #
#   можно только у Saint'a 							 #
#	Вы не имеете права ставить если модуль 			 #
#	даже если он слит!!  							 #
#	даже если он был вап продаан барыгой !!!  		 #
#	даже если вы его случайно где то скачали !!!	 #  
#	даже если что то еще ,							 #
#	не тратьте моё время и ваше	,					 #
#	постреченое на постройку вашего сайта ;)		 #
######################################################
#		Видео Портал файлов с ютуба by Saint 		 #
######################################################
$JumanG = $_SERVER['DOCUMENT_ROOT'];
include_once $JumanG.'/sys/inc/start.php';
include_once $JumanG.'/sys/inc/compress.php';
include_once $JumanG.'/sys/inc/sess.php';
include_once $JumanG.'/sys/inc/home.php';
include_once $JumanG.'/sys/inc/settings.php';
include_once $JumanG.'/sys/inc/db_connect.php';
include_once $JumanG.'/sys/inc/ipua.php';
include_once $JumanG.'/sys/inc/fnc.php';
include_once $JumanG.'/sys/inc/user.php';

if (isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `id` = '".intval($_GET['id'])."'"),0)==1)
{
$post=mysql_fetch_array(mysql_query("SELECT * FROM `videoyou` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));


if (isset($user) && ($user['level']>$ank['level'] || $user['level']==4))
mysql_query("DELETE FROM `videoyou` WHERE `id` = '$post[id]'");
}

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL)
header("Location: ".$_SERVER['HTTP_REFERER']);
else
header("Location: index.php?".SID);

?>