Установка:
Распаковать в корень
Выполнить запрос

alter table `user` add `show_vk` int(1) default '1';

В head.php вашей WEB темы после // Блок вывода смайлов (самый них почти) прописать прописать код:

if (isset($user))
{
	?><div id="content"></div>   
    <script>  
        function show()  
        {  
            $.ajax({  
                url: "/ajax/frend.ajax.php",  
                cache: false,  
                success: function(html){  
                    $("#frends").html(html);  
                }  
            });  
        }  
      
        $(document).ready(function(){  
            show();  
            setInterval('show()',10000);  
        });  
    </script>  <?
	}
if ($webbrowser == true && isset($user))
{
	echo '<div style="position:fixed; left: 10px; bottom:15px;" id="frends"></div>';
}
 
// Блок для сообщений
if (isset($user))
{
	echo '<div id="mail_vk_modal" title="Сообщения">';
	?> 
		<script src="/ajax/mail/mail.load.js"></script>
	    <div id="id_mail">  
		
	    </div>  
	 	
	    <div id="loading" style="display: none">  
	    Загрузка данных...<br />
		<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>
	    </div> 
		
		<script>
		$(function(){
		$("#mail_close").click(function(){
			$.ajax({  
				url: "/ajax/mail/mail.form.php?close",  
				cache: false,  
				success: function(html)
				{  
					$("#id_mail").html(html);  
				}  
			}); 
		});
		});
		function loading_mail(id_user)  
		{
			$.ajax({  
			url: "/ajax/mail/mail.form.php?id="+id_user,  
			cache: false,  
			success: function(html)
			{  
				$("#id_mail").html(html);  
			}
			});
		}
    	</script>
		</div>
	<?
}

Установка завершена, проверьте работу модуля и идите оставлять отзыв о товаре =)