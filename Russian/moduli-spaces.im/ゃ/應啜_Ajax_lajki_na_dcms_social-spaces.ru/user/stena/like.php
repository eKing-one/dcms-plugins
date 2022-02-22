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

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_POST['id_post'])&& !empty($_POST['us']) && isset($user)){
 
$id = intval($_POST['id_post']);
$us= intval($_POST['us']);   
$sql =mysql_result(mysql_query("SELECT COUNT(*) FROM `stena_like` WHERE `id_stena` = '$id' AND `id_user` = '$us'"),0); 

if($sql=='0')
{
    mysql_query("INSERT INTO `stena_like` (`id_user`, `id_stena`) values('$us', '$id')");
    echo json_encode(array('result' => 'like'));
}
else
{
     mysql_query("DELETE FROM `stena_like` WHERE `id_user`='$us' AND `id_stena`='$id'");
	echo json_encode(array('result' => 'dislike'));    
}

	

}
  else
  {
  header("location:/"); 
  }
    
 
?>
