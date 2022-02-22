<?
//////Автор данного модуля: ST1NG\\\\\\
/////Связь: email: oleg.realmast@yandex.ru\\\\\

include_once 'sys/inc/start.php';

include_once 'sys/inc/compress.php';

include_once 'sys/inc/sess.php';

include_once 'sys/inc/home.php';

include_once 'sys/inc/settings.php';

include_once 'sys/inc/db_connect.php';

include_once 'sys/inc/ipua.php';

include_once 'sys/inc/fnc.php';

include_once 'sys/inc/user.php'; 

include_once 'sys/inc/icons.php'; // Иконки главного меню



include_once 'sys/inc/thead.php';

title();

err();



 if (!$set['web'])

{



echo "<label><input type='checkbox' name='aut_save' value='1' />Поставьте галочку если вы не будете (дарить,продавать, кидать в пабл  данный модуль)над модом работал ©ST1NG<label><br/>"; 
echo "<div class='foot'>Продолжить установку?<br /><a href='/smeert.php'>==»»ЗАВЕРШЕНИЕ!!««=</a><br /></div>";




















}else{

 


include_once 'style/themes/'.$set['set_them'].'/index.php'; // главная web темы 






}

include_once 'sys/inc/tfoot.php';

?> 