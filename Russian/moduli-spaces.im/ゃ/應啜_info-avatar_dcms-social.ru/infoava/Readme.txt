Автор: Novichok 
Мой сайт: v7privet.ru
Установка
Из папки infоаvа переместить в корень сайта.

urer/info/wap.php находим строку который отвечает за вывод аватара. С верху есть этой строки есть такой див класс echo "<div class='nav2'>"; Вот этот див замените на этот echo "<div class='nav2'><table class='post'><td class='p_t'>";
Код вывод аватара echo avatar($ank['id'], true, 128, false);
Закрываем td класс echo"<br /></td>";
После пишем этот класс
echo "<td class='p_t'>";
Инклуд вывод информаци в анкете
include 'info_avatar.php';
Закрываем классы
 echo "</td></tr><tr></td></tr></table/>";
Вот всё по моему. Если не поняли что и как, пишите мне dcms-social.ru-Novichok
v7privet.ru Желаю удачи!