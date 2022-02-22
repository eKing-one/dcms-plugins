<?
/* Автор VoronoZ 
http://dcms-social.ru/info.php?id=627
https://vk.com/voronoz
*/

include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

only_reg();

/////////////

$select=(!empty($_GET['act'])? trim($_GET['act']):NULL);
switch($select)
{
case 'country': 
$set['title']='Выбор страны';
include_once '../../sys/inc/thead.php';
title();
aut();
$k_post = mysql_result(mysql_query("SELECT * FROM `country`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];
$sql = mysql_query("SELECT * FROM `country` LIMIT $start, $set[p_str]");
while($country = mysql_fetch_assoc($sql)) {
    	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">
	
    	<img src="/style/country/'.$country['id'].'.png" alt="*"> <a href="?act=region&id='.output_text($country['id']).'">'.output_text($country['name']).'</a>
    	</div>';
    	$num++;
    	}

if ($k_page > 1)str('?act=country&amp;', $k_page, $page); // Вывод страниц   break;
echo '<div class="foot">';
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='edit.php'>Редактировать анкету</a><br />\n";
echo '</div>';
 break;
 /////////////////////////////////  
   case 'region': 
 $set['title']='Выбор региона';
include_once '../../sys/inc/thead.php';
title();
aut();
 $id =(!empty($_GET['id'])? intval($_GET['id']):NULL);
 
 if($id!=NULL && mysql_result(mysql_query("SELECT * FROM `region` WHERE `id_country`=$id"), 0)!='0') {
$_SESSION['region']=$id;
$k_post = mysql_num_rows(mysql_query("SELECT * FROM `region` WHERE `id_country`='$id'"));
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];

$sql = mysql_query("SELECT * FROM `region` WHERE `id_country`=$id LIMIT $start, $set[p_str] ");
while($region = mysql_fetch_assoc($sql)) {
    	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">
    	<a href="?act=city&id='.output_text($region['id']).'">'.output_text($region['name']).'</a>
    	</div>';
    	$num++;
    	}
if ($k_page > 1)str('?act=region&id='.$id.'&amp;', $k_page, $page); // Вывод страниц   break;

echo '<div class="nav1">';
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=country'>Выбор страны</a><br />\n";
echo '</div>';
}
 else
 header("Location: /user/info/edit.php".SID); 
   break;
 /////////////////////////////////  
   case 'city': 
  $set['title']='Выбор города';
include_once '../../sys/inc/thead.php';
title();
aut();
  $id =(!empty($_GET['id'])? intval($_GET['id']):NULL);
  
 if($id !=NULL && mysql_result(mysql_query("SELECT * FROM `city` WHERE `id_region`='$id'"),0)!='0') {
 $_SESSION['city']=$id;
 $k_post = mysql_num_rows(mysql_query("SELECT * FROM `city` WHERE `id_region`=$id "));
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];

$sql = mysql_query("SELECT * FROM `city` WHERE `id_region`='$id' LIMIT $start, $set[p_str] ");
while($city = mysql_fetch_assoc($sql)){
    	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">
    	<a href="?act=select&id='.output_text($city['id']).'">'.output_text($city['name']).'</a>
    	</div>';
    	$num++;
    	}
if ($k_page > 1)str('?act=city&id='.$id.'&amp;', $k_page, $page); // Вывод страниц   break;

echo '<div class="foot">';
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=country'>Выбор страны</a><br />\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=region&id=".$_SESSION['region']."'>Выбор региона</a><br />\n";
echo '</div>';
}
 else
 header("Location: /user/info/edit.php".SID);   
   break;
   /////////////
case 'select':
  $id =(!empty($_GET['id'])? intval($_GET['id']):NULL);
$set['title']='Выбор страны';
include_once '../../sys/inc/thead.php';
title();
aut();
 if($id !=NULL && mysql_result(mysql_query("SELECT * FROM `city` WHERE `id`=$id "),0!=0)) {
 
 $city = mysql_fetch_assoc(mysql_query("SELECT * FROM `city` WHERE `id`=$id LIMIT 1"));
$country = mysql_fetch_array(mysql_query("SELECT * FROM `country` WHERE `id`='".$city['id_country']."' LIMIT 1"));
$region = mysql_fetch_array(mysql_query("SELECT * FROM `region` WHERE `id`= ".$city['id_region']." LIMIT 1"));
$city = mysql_fetch_array(mysql_query("SELECT * FROM `city` WHERE `id`=".$city['id']." LIMIT 1"));

if(isset($_POST['save']) && isset($user)){
 if (!isset($err))
{
mysql_query("UPDATE `user` SET  `city` = '".htmlspecialchars($city['name'])."', `country` = '".htmlspecialchars($country['name'])."', `region` = '".htmlspecialchars($region['name'])."' WHERE `id` = '".$user['id'] ."'");
$_SESSION['message'] = 'Изменения успешно приняты';
header("Location: reg.php");
 }
 }

echo "<div class='mess'>
<form method='post' action=''>
 <font color='green'>Ваша страна :</font> <b>".output_text($country['name'])."</b></br> <font color='green'>Ваш регион:</font> <b>".output_text($region['name'])."</b> </br> <font color='green'>Ваш город : </font><b>".output_text($city['name'])."</b></br>
</br><input type='submit' name='save' value='Принять изменения' />
</form>
 </div>";
 
 
 echo '<div class="foot">';
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='edit.php'>Редактировать анкету</a><br />\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=country'>Выбор страны</a><br />\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=region&id=".$_SESSION['region']."'>Выбор региона</a><br />\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?act=city&id=".$_SESSION['city']."'>Выбор города</a><br />\n";
echo '</div>';
   } 
 
 break;  
   default:
   header("Location: /user/info/edit.php".SID);
   }
   

	
include_once '../../sys/inc/tfoot.php';		 
?>