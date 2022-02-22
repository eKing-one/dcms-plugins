<?
/* Автор VoronoZ 
http://dcms-social.ru/info.php?id=627
https://vk.com/voronoz
*/
function city($id,$link=false,$flag=true,$coun=true,$reg=true,$ci=true)
{
$user= mysql_fetch_array(mysql_query("SELECT country,region,city FROM `user` WHERE `id` = '$id ' LIMIT 1"));
if($flag==true)$flag=mysql_fetch_array(mysql_query("SELECT id FROM `country` WHERE `name` = '".$user['country']." ' LIMIT 1"));
return 
($flag==true ? '<img src="/style/country/'.$flag['id'].'.png" alt="*"> ':NULL).
($coun==true && $user['country']!=NULL ?''.($link==true ? '<a href="/user/search.php?search='.output_text($user['country']).'">'.output_text($user['country']).'</a>':output_text($user['country'])).'':NULL) .' 
'.($coun==true && $user['country']!=NULL && $reg==true && $user['region']!=NULL  ? ',':NULL) .' 
'.($reg==true && $user['region']!=NULL ?''.($link==true ? '<a href="/user/search.php?search='.output_text($user['region']).'">'.output_text($user['region']).'</a>':output_text($user['region'])).'':NULL).'

'.($ci==true && $user['city']!=NULL && $reg==true && $user['region']!=NULL || $coun==true && $user['country']!=NULL  ? ',':NULL).'
 '.($ci==true && $user['city']!=NULL ?''.($link==true ? '<a href="/user/search.php?search='.output_text($user['city']).'">'.output_text($user['city']).'</a>':output_text($user['city'])).'':NULL)
 
 ;
}


?>