<?php
/*********************
*** Автор Dark_AKC ***
*** Micro-Book.ru ****
*********************/
if (isset($user)){
if((isset($user['id']) && $user['post_chat'] == 0) or (!isset($user['id']))){
}else{ 
	$q = mysql_query("SELECT * FROM `guest` ORDER BY `id` DESC LIMIT 3");
	while($post = mysql_fetch_assoc($q)){
		$avtor = mysql_fetch_assoc(mysql_query("SELECT `id`,`nick` FROM `user` WHERE `id` = '$post[id_user]'"));
		
	
                 echo '<div class="hide"><small>';
		echo group($avtor['id']);
		echo '<a href="/info.php?id='.$avtor['id'].'">'.$avtor['nick'].'</a>';
		echo online($avtor['id']).medal($avtor['id']);
		echo vremja($post['time']).'<br />';
		echo output_text($post['msg']);
		echo '</small></div>';
	}
}
}
?>