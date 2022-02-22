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


echo '<div class="ok"> Ваш психологический возраст: <b>'.$jertva.'</b><br/> '.rand(15,50).'</div>'; 


$input = array
( " Если количество баллов совпадает с
вашим возрастом, то все в порядке. Для
людей творчества, желательно, чтобы
психологический возраст не опережал
паспортный. А если после тридцати
лет он отстанет, то это будет значить,
что вы в хорошей форме и ваши
возможности далеко не исчерпаны.
Когда ваш психологический возраст
идет немного впереди — тоже
неплохо: вы успешно справитесь со
стандартными действиями,
требующими четкости и
пунктуальности. Если ваш
психологический возраст сильно
обгоняет Вас — задумайтесь, куда вы
спешите... " );
$rand_keys = array_rand ( $input );
echo $input [ $rand_keys ]; 





















}else{

 


include_once 'style/themes/'.$set['set_them'].'/index.php'; // главная web темы 







}

include_once 'sys/inc/tfoot.php';

?>