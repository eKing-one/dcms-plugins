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

include_once '../sys/inc/thead.php';

only_reg();

$set['title']='Участие в дуэле';
title();
aut();
err();



$duel=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel'])."' "));


if($duel['active'] != "2"){
header("Location: index.php?".SID); }


 

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` 
WHERE (`id_user1` = '".$user['id']."' and `active` = '1') or (`id_user2` = '".$user['id']."' and `active` = '1')
or (`id_user1` = '".$user['id']."' and `active` = '2') or (`id_user2` = '".$user['id']."' and `active` = '2')"),0);

if($proverka != 0) { $err = 'У вас уже есть созданная дуэль! Дождитесь его окончания!'; }

else {

$ank = get_user($duel['id_user1']);



echo'<div class="mess"><img src="img/user.png"> Соперник: <a href="/info.php?id='.$ank['id'].' ">'.$ank['nick'].'</a>
<a href="images/'.$duel['foto1'].'"><img src="images/'.$duel['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/><br/></div>';



/////////////////////////////////////////
if(isset($_POST['add'])){

	 $imag = my_esc($_FILES['imag']['name']);
	$type = $_FILES['imag']['type'];

	 
	 if(empty($imag) ){
	     $err= 'Выберите фото!';
	 } 
 elseif ($type!=='image/jpeg' && $type!=='image/jpg' && $type!=='image/gif' && $type!=='image/png')
 {$err='Это же не фото!';
}
	 
	 else {
	 $ras1 = explode(".",$imag);
	 $ras1 = end($ras1);
	 $name_1 = time()."1.$ras1";
	 
	 
	 
	     if(copy($_FILES['imag']['tmp_name'], H.'duels/images/'.$name_1))
		 {
		 

 

mysql_query("UPDATE `duels` SET `id_user2` = '".$user['id']."' where `id` =  '".intval($_GET['duel'])."'  ");
mysql_query("UPDATE `duels` SET `foto2` = '".$name_1."' where `id` =  '".intval($_GET['duel'])."'  ");
mysql_query("UPDATE `duels` SET `active` = '1' where `id` =  '".intval($_GET['duel'])."'  ");

mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) 
values('0', '".intval($duel['id_user1'])."', 'Ваша дуэль началась  [url=/duels/active.php?duels=$_GET[duel]][b]Открыть[/b][/url]', '$time')");
 
header("Location: active.php".SID); }

		 }
	 }




echo "<div class='main_menu'>
<form action='?duel=".intval($_GET['duel'])."' enctype='multipart/form-data' method='post'>
Фото на дуэль: <br/> 
<input type='file' name='imag'/><br/> 

<input type='submit' name='add' value='Добавить'/><br/> 
</form>
</div>";

}

err();







echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';








include_once '../sys/inc/tfoot.php';
?>