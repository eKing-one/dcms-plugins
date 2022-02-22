/**
* Класс фильтрации глобальных переменных
* Соавтор: Кредитор
* Icq: 441460
* Email: kpegumop@yandex.ru
* Сайт: http://gix.su - Безопасные сделки, модули и дизайны для ваших сайтов, seo скрипты, оптимизация, безопасность
* Любые скрипты на заказ
*/


Описание: Очень простой и шустрый класс фильтрации глобальных переменных на предмет sql inj и xss. Прост в установке.

Установка: 
1. Создайте папку /sys/classes/
2. Загрузите в нее файл AntiHack.class.php из архива
3. Откройте файл /sys/inc/user.php и в самом конце пропишите сдедующие строки:

     if ($_SERVER['PHP_SELF'] != '/adm_panel/mysql.php')
     {     require(H.'sys/classes/AntiHack.class.php');
     $lq = new AntiHack;

       if (isset($_GET))$_GET = $lq->filter($_GET, 'get');
       if (isset($_POST))$_POST = $lq->filter($_POST, 'post');
       if (isset($_FILES))$_FILES = $lq->filter($_FILES, 'files');
       if (isset($_COOKIE))$_COOKIE = $lq->filter($_COOKIE, 'cookie');
       if (isset($_SERVER))$_SERVER = $lq->filter($_SERVER, 'server');
       if (isset($_REQUEST))$_REQUEST = $lq->filter($_REQUEST, 'request');
     unset($lq);
     }


4. По сути это все. Если в дальнейшем у вас будут появляться например в форуме, новостях или почте перед кавычками символы |, откройте файл /sys/inc/output_text.php и в самом конце функции файла измените строку(у всех она может отличаться):

return $str;

на

return stripslashes($str);

// или например return esc($str); на return stripslashes(esc($str));
// суть думаю ясна. Если что, все поясню


5. Не забывайте фильтровать данные в форме ввода. Экранирование символов не позволяет провести sql inj ну и притормозит некоторые вещи из конструкций xss. Но.... в xss можно кавычки и не использовать. обрабатывайте переменные в формах хотя бы функцией htmlspecialchars(), ну и в нашем случае stipslashes(htmlspecialchars()). Пример:
echo '<input type="text" name="msg" value="'.stripslashes(htmlspecialchars($_POST['msg'])).'" />';

//Если в чем то сомневаетесь, обращайтесь, я подскажу

 




Рекомендации: Зная любовь пользователей к сливу всего купленного в обменники, напоминаю, что хуже вы делаете не мне. открытый исходный код всегда уязвим и, рано или поздно, Вам самим придется заплатить в десятки раз больше за индивидуальную безопасность Вашего ресурса. Будьте людьми наконец. Повзрослейте