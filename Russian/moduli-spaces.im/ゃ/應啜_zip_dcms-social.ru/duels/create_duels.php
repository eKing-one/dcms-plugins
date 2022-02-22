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

$set['title']='Создание дуэли';
title();
aut();
err();




 

$proverka = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` 
WHERE (`id_user1` = '".$user['id']."' and `active` = '1') or (`id_user2` = '".$user['id']."' and `active` = '1')
or (`id_user1` = '".$user['id']."' and `active` = '2') or (`id_user2` = '".$user['id']."' and `active` = '2')"),0);

if($proverka != 0) { $err = 'У вас уже есть созданная дуэль! Дождитесь его окончания!'; }

else {




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
		 
		
		 
mysql_query("INSERT INTO `duels` (`id_user1`, `foto1`, `active`, `time` ) 
values('".$user['id']."',  '".$name_1."' , '2', '".$time."' )");


 

 header("Location: waiting.php".SID); 

		 }
	 }
}



echo "<div class='main_menu'>
<form action='?' enctype='multipart/form-data' method='post'>
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