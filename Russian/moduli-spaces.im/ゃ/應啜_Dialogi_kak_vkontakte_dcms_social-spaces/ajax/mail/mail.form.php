<?
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) die;
define('H', $_SERVER['DOCUMENT_ROOT'].'/');
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/home.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';

if (!isset($_GET['id']) || isset($_GET['close']))
{
	$_SESSION['id_mail'] = NULL;
	echo 'Загрузка данных...<br />
<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>';
	exit;
}

$ank = get_user(intval($_GET['id']));

if (!$ank)
{
	echo 'Загрузка данных...<br />
<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>';;
	exit;
}

$_SESSION['id_mail'] = $ank['id'];
?>
<script type="text/javascript">
function mail_post(id_user)  
{  
	$.ajax({  
		url: "/ajax/mail/mail.post.php?id=<?=$ank['id']?>",  
		cache: false,  
		success: function(html)
		{  
			$("#mail_post").html(html);  
		}  
	});   
}  


$(document).ready(function(){ 
	mail_post();  
	setInterval('mail_post()', 5000);
	
});  


$(function() {
		
	$("#send").click(function(){

		var author = $("#author").val();
		var msg = $("#msg").val();				
		$.ajax({
			type: "POST",
			url: "/ajax/mail/sendMessage.php?id=<?=$ank['id']?>",
			data: {"author": author, "msg": msg},
			cache: false,						
			success: function(response){
				var messageResp = new Array('Cообщение отправлено','Сообщение не отправлено','Нельзя отправлять пустые сообщения');
				var resultStat = messageResp[Number(response)];
				if(response == 0){
					$("#author").val("");
					$("#msg").val("");
					
   					$.ajax({  
						url: "/ajax/mail/mail.post.php?id=<?=$ank['id']?>",  
						cache: false,  
						success: function(html)
						{  
							$("#mail_post").html(html);  
						}  
					});  
				}
				$("#resp").text(resultStat).show().delay(1500).fadeOut(800);
				
			}
		});
		return false;
		
	});
});
</script>

<script language="JavaScript" type="text/javascript">
                function tag(text1, text2) {
                if ((document.selection)) {
                document.message.msg.focus();
                document.message.document.selection.createRange().text = text1+document.message.document.selection.createRange().text+text2;
                } else if(document.forms['message'].elements['msg'].selectionStart!=undefined) {
                var element = document.forms['message'].elements['msg'];
                var str = element.value;
                var start = element.selectionStart;
                var length = element.selectionEnd - element.selectionStart;
                element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
				document.forms['message'].elements['msg'].focus();
                } else document.message.msg.value += text1+text2;
				document.forms['message'].elements['msg'].focus();}</script>

<form name="message" action="sendMessage.php" method="post">
	<input name="author" type="hidden" value="<?=$user['id']?>" id="author">

	<table><tr> 
	<td style="width: 50px;">
	<?=status($ank['id'])?>
	</td>
	
	<td>
	<textarea style="height: 50px; margin:0; padding:0; border:none;" name="msg" id="msg"></textarea></td>
	</tr></table>

	<input name="js" type="hidden" value="no" id="js">
	
	<input name="button" type="submit" value="Отправить" id="send"> <a href='#' id='opener'>Смайлы</a>
</form> <span id="resp"></span><br />



<script>    
$.fx.speeds._default = 1000;    
$( "#dialog" ).dialog({     
autoOpen: false,    
show: "blind",    
hide: "explode"  
});	   
$( "#opener" ).click(function(){      
$( "#dialog" ).dialog( "open" );	  
showContent2('/ajax/php/smiles.php');      
return false;    
});  
</script>


<div class="layer" id="mail_post">
Загрузка сообщений...<br />
<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>
</div>

<script>
$(document).ready(function(){ 
show();  
});  
</script>