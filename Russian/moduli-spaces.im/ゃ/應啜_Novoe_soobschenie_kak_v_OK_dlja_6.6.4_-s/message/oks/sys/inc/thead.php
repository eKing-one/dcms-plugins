<?

$set['meta_keywords']=(isset($set['meta_keywords']))?$set['meta_keywords']:null;
$set['meta_description']=(isset($set['meta_description']))?$set['meta_description']:null;


if ($set['meta_keywords']!=NULL)
{
function meta_keywords($str)
{
global $set;
return str_replace('</head>', '<meta name="keywords" content="'.$set['meta_keywords'].'" />'."\n</head>", $str);
}
ob_start('meta_keywords');
}


if ($set['meta_description']!=NULL)
{
function meta_description($str)
{
global $set;
return str_replace('</head>', '<meta name="description" content="'.$set['meta_description'].'" />'."\n</head>", $str);
}
ob_start('meta_description');
}




if (file_exists(H."style/themes/$set[set_them]/head.php"))
include_once H."style/themes/$set[set_them]/head.php";
else
{
$set['web']=false;
//header("Content-type: application/vnd.wap.xhtml+xml");
//header("Content-type: application/xhtml+xml");
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<meta http-equiv="Refresh" content="60" />

<? 


/*
#################################                                                                          #################################
#################################Мигание текста о новом сообщение в title как в одноклассниках, автор array#################################
#################################                                                                          #################################
*/




 global $user;
$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);
 
$k_new_fav=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0); // Почта


if ($k_new!=0 || $k_new_fav!=0) {

?>


<script>
 
var newTxt="Новое сообщение!";
var oldTxt="*****************************";
 
function message_blink(){
    if(document.title==oldTxt){
        document.title=newTxt;
    }else{
        document.title=oldTxt;
    }
}
 
var timer = setInterval(message_blink,1000);
 
</script>




<? 

}


/*
#################################                                                                          #################################
#################################Мигание текста о новом сообщение в title как в одноклассниках, автор array#################################
#################################                                                                          #################################
*/


?>






<title><?echo $set['title'];?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />
<link rel="alternate" title="Новости RSS" href="/news/rss.php" type="application/rss+xml" />
</head>
<body>
<div class="body">
<?

if ($k_new!=0 || $k_new_fav!=0) {

if (empty($_SESSION['time_msg']) || @$_SESSION['time_msg']<time()-300) {

global $time;

echo vremja($_SESSION['time_msg']);

$_SESSION['time_msg'] = $time+300;

?>


<audio src="/sys/inc/signal/ton.mp3" autoplay></audio>


<?

}
}


}

?>