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


echo "<b>На вопросы отвечайте точно,можно даже несколько вариантов ответов</b><br/>";

echo "Ваш возраст(например 18 лет):<br /><input type='text' name='nick' maxlength='32' /><br />\n";
echo "<b>1)Вы находитесь в компании друзей.Что для вас важно:</b><br/><label><input type='checkbox' name='aut_save' value='1' />Вести себя с нормами этой компании</label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /></label>Зделать так,чтобы о вас не забыли<br />";
echo "<label><input type='checkbox' name='aut_save' value='1' />Соблюдать приличия</label><br />";
echo "<b>2)Какая работа вам нравится?</b><br/><label><input type='checkbox' name='aut_save' value='1' />Не трудная</label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' />Физическая</label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' />Не люблю работать</label><br />";
echo "<b>3)Когда вы слушаете музыку,вы щитаете:</b><br/><label><input type='checkbox' name='aut_save' value='1' />Что на вкус и цвет товарищей нет</label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' />Вы не признаете этот шум и грохот</label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' />Что вы рады</label><br />";
echo "<b> 4)На ваших глазах совершается явная
несправедливость. Ваши действия: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> вмешаюсь в обсуждение, не
высказывая своего мнения </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> постараюсь восстановить
справедливость в рамках закона </label><br />";
 
echo "<label><input type='checkbox' name='aut_save' value='1' /> встану на сторону пострадавшего и
постараюсь ему помочь </label><br />";

echo "<b> 5)В свободное время вы охотнее всего: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> занимаетесь чем угодно </label><br />";

echo "<label><input type='checkbox' name='aut_save' value='1' /> проводите время с приятелями </label><br />";
 
echo "<label><input type='checkbox' name='aut_save' value='1' /> читаете художественную
литературу </label><br />";


echo "<b>6) Ваше отношение к моде: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> признаю и отвергаю, смотря по
настроению </label><br />";
 
echo "<label><input type='checkbox' name='aut_save' value='1' /> признаю то, что мне подходит </label><br />";

echo "<label><input type='checkbox' name='aut_save' value='1' /> признаю и действую, чтобы
соответствовать ей </label><br />";

echo "<b> 7)Вы опаздываете и находитесь
неподалеку от автобусной остановки.
Подходит автобус. Что вы сделаете: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> побегу, чтобы успеть </label><br />";
 
echo "<label><input type='checkbox' name='aut_save' value='1' /> посмотрю, не идет ли следующий
автобус, а потом решу, что делать </label><br />";
 
echo "<label><input type='checkbox' name='aut_save' value='1' /> буду идти как можно быстрее </label><br />";


echo "<b>8) Насколько вы предусмотрительны: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> предпочитаю сначала
действовать, а уже потом рассуждать </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> предпочитаю участвовать только в
таких делах, где успех гарантирован </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> отношусь к делам в зависимости
от ситуации </label><br />";


echo "<b>9) Доверчивы ли вы: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> все зависит от того, с кем я имею
дело </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> доверяю некоторым людям </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> не доверяю никому </label><br />";


echo "<b>10) Ваше настроение: </b><br/><label><input type='checkbox' name='aut_save' value='1' /> часто оптимистическое </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> зависит от обстоятельств </label><br />";
echo "<label><input type='checkbox' name='aut_save' value='1' /> преобладает пессимистическое </label><br />";

	 
echo "<a href='/otvet.php'>УЗНАТЬ РЕЗУЛЬТАТ<a/>";


















}else{

 


include_once 'style/themes/'.$set['set_them'].'/index.php'; // главная web темы 


echo '©St1NG';




}

include_once 'sys/inc/tfoot.php';

?>