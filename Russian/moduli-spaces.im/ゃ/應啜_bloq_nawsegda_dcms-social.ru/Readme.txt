От и очередной мой мод: написал блок навсегда, сразу обясню для чего!
Вот юзер много раз нарушает, и баны ему не помагают....так дадим ему "блок навсегда" и всех то делов!
Скрипт написан мной: тоесть Ivanovsky 
Контакты: icq: 613540059
e-mail: corp.breaking@ya.ru
Сайт: http://vineti.ru/info.php?id=1
Прозьба: у кого есть таблици к моду дуели...бросте пожалуйста...а то мод есть...а таблыци просрал!






Кароче теперь установка:
Ищем файл /info.php тоесть лычные странички))
aut(); примерно 21 строка
Вставляем код:
if ($ank['bloq']==1){
 avatar($ank['id']);
 echo "<font color='red'> Этот акаунт заблокирован за нарушение <a href='http://vineti.ru/info/all_rules.php'> правил </a> сервиса!<font>";
 echo "<div class='forum_t'>Причина: $ank[bloq_p]</div>";
 if ($user['group_access']>=7){
echo "<a href='/bloq.php?id=$ank[id]'> Розблокировать </a><br/>";
echo "Браузер $ank[ua]";}
И дальше в конце кода перед include_once 'sys/inc/tfoot.php';
Закрываем условые( если кто не знает что это ставим } )
На личной прописуем ещё!

if (isset($user) && $user['group_access']>=7){ echo "[<a href='/bloq.php?id=$ank[id]'> Заблокировать навсегда </a>]<br/>";}


Дальше выполняем запрос к БД
ALTER TABLE `user` ADD `bloq` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `bloq_p` TEXT NOT NULL default '';
Дальше бросаем файл 
bloq.php в корень своего сайта:
Файл user.php в /sys/inc/
И теперь всё будет работать на полную!
Гг это не последний мод!
Прозьба не быть барыгой!

(c) BreaKing 2011 