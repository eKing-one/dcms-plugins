 ######## Модуль: "Мигание title при новом сообщении как в ОК" ########
	  
	  ######## Автор: array ########
	  
	  ######## Сайт: http://byCode.ru ########
	  
Для dcms 6.6.4
	  Если пользователь находится на другом сайте но не закрыл вкладку на ваш сайт, то при получении нового сообщения он увидит мигающий заголовок, о том что пришло новое сообщение.
	  Аналог odnoklassniki.ru
	  
	  Установка: Распаковать в корень с заменой файлов, заменяются файлы /sys/inc/thead.php и /sis/fnc/title.php
	  
	  
	  Затем добавить след. код в style/themes/ваша_тема/head.php
	  
	  
	  Находим строку <head> после неё добавляем след. код
	  
	  
	  
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







Ниже находим строки 

<body>

<?

 после них добавляем след. код

 if ($k_new!=0 || $k_new_fav!=0) {
 
 
 
if (empty($_SESSION['time_msg']) || @$_SESSION['time_msg']<time()-300) {

global $time;

echo vremja($_SESSION['time_msg']);

$_SESSION['time_msg'] = $time+300;

?>


<audio src="/sys/inc/signal/ton.mp3" autoplay></audio>


<?

)
}




