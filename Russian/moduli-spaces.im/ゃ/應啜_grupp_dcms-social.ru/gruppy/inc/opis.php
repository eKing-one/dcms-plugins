<?php
{

if($num==1){
echo "<div class='mess'>\n";
$num=0;
}else{
echo "<div class='mess'>\n";
$num=1;}
if(is_file("inc/opis/$ras.php")){	include "inc/opis/$ras.php";
	}else{		echo '<img src="img/statistics_16.png" alt="" class="icon"/> Размер: '.size_file($size).'<br/>';
		echo '<img src="img/clock.png" alt="" class="icon"/> Загружен: '.vremja($post['time']).'<br/>';
		}

$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[id_user]' LIMIT 1"));
echo '<img src="img/tick_16.png" alt="" class="icon"/> Выгрузил: <a href="/info.php?id='.$ank['id'].'"><span style="color:'.$ank['ncolor'].'">'.$ank['nick'].'</span></a><br/>';
echo '</div>';


}
?>


