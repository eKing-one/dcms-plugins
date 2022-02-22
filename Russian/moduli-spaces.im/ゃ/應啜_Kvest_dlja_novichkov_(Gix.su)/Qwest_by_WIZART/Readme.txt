/*+=+=+=+=+=+=+=+=+=+=+=+>Информация<=+=+=+=+=+=+=*/
Автор: WIZART
e-mail: bi3apt@gmail.com
icq: 617878613
Сайт: WizartWM.RU
Сайт2: Krasavo.RU
/*+=+=+=+=+=+=+=+=+=+=+=>Установка<+=+=+=+=+=+=+=*/
1) Кинуть папку /qwest/ в корень сайта.
2) Выполнить запрос через админку:
ALTER TABLE `user` ADD `qwest` set('0','1') NOT NULL default '0';
3) Прописать в /sys/inc/thead.php почти в самом конце перед ?>  вот этот код:
if (isset($user) && $user['qwest']==0)echo "<center><img src='/style/icons/money.png' alt=''> <a href='/qwest'>Получить подарок</a></center>";
/*+=+=+=+=+=+=+=+=+=+=+=>Радуемся<+=+=+=+=+=+=+=*/Вы успешно установили этот модуль:) 
