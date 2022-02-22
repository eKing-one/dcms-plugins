### Скрипт ###
Название: VIP статус
Автор: Killer
CMS: DCMS Social

### Описание ###
Каждый пользователь может покупать себе VIP статус.
Стоимость VIP статуса составляет 10 монет (можно легко сменить, сменив значение только одной переменной!).
Статус выдается ровно на одну неделю.
Преимущества VIP пользователей:
- Возле их ника мигает иконка, что VIP статус активирован
- Можно выбирать одну из 6-ти VIP иконок (все иконки сам рисовал :))
- Во время действия VIP статуса анкета пользователя рандомно выводится на главной, как и всех остальных VIP пользователей

### Установка ###
1) Папку vip_icons закинуть в паку style
2) Файл vip.php закинуть в папку user/money
3) Файл vip_users.php закинуть в папку sys/inc
4) В файле index.php после кода
include_once 'sys/inc/news_main.php'; // новости 
прописать код
include_once 'sys/inc/vip_users.php'; // vip пользователи
5) В файле sys/inc/user.php перед ?> прописать код
mysql_query("DELETE FROM `vip_users` WHERE `time` < '".time()."'");
6) В файле user/money/index.php после кода
echo '<div class="nav2">';
$c2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `user_set` WHERE `id_user` = '$user[id]' AND `ocenka` > '$time'"), 0);
echo "&rarr; <a href='plus5.php'>Оценка</a> <img src='/style/icons/6.png' alt='*'> " . ($c2==0?'<span class="off">[отключена]</span> ':'<span class="on">[включена]</span>')."";
echo "</div>\n";
прописать код
echo '<div class="nav1">';
$vip = mysql_result(mysql_query("SELECT COUNT(*) FROM `vip_users` WHERE `id_user` = '$user[id]'"), 0);
echo '&rarr; <a href="vip.php">VIP статус</a> ' . ($vip == 0 ? '<span class="off">[отключена]</span> ' : '<span class="on">[включена]</span>');
echo '</div>';
7) В файле sys/fnc/online.php перед кодом
return $users[$user];
прописать код
$vip = mysql_fetch_array(mysql_query("SELECT * FROM `vip_users` WHERE `id_user` = '$user'"));
if (@$vip['id'])$users[$user] .= " <img src='/style/vip_icons/{$vip['icon']}.gif' />\n";
8) В файле sys/fnc/online.php уберите строчку static $users;
9) Откройте файл user/money/vip.php. Найдите строку $money_need = 10; (примерно 16 строка). Замените значение переменной $money_need на нужное Вам значение. Это и будет цена VIP статуса.
10) Выполните запрос
CREATE TABLE `vip_users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_user` INT(11) NULL DEFAULT '0',
	`time` INT(11) NULL DEFAULT '0',
	`icon` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COMMENT='VIP пользователи'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
11) Оставляем положительный отзывчик о товаре!))
11) Это все. Пользуйтесь!)

Спасибо за то, что приобрели наш товар! Больше красивых и качественных скриптов Вы сможете купить здесь http://gix.su/shop/user.php?id=96

### Контакты ###
ICQ: 686579

### Это важно! ###
(с) Killer
Все права защищены. Если этот модуль попал к вам не из рук автора, просьба оповестить нас о этом, используя наши контакты.