<?php
require 'inc/sys.php';

echo "
<table>
<tr>
	<td>
		".$doc->icon ('guard')."
	</td>
	<td>
	<a href='/guard/guard/'>
		<b>Защита</b><br />
		Обеспечивает безопасность сайта
	</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('antivirus_32')."
	</td>
	<td>
	<a href='/guard/antivirus/'>
		<b>АнтиВирус</b><br />
		Защищает сайт от скрытого взлома
	</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('spam')."
	</td>
	<td>
		<a href='/guard/antispam/'>
		<b>АнтиСпам</b><br />
		Предотвращает распространение рекламы
	</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('flood')."
	</td>
	<td>
		<a href='/guard/settings/antiflood/'>
		<b>АнтиФлуд</b><br />
		Контролирует частоту сообщений
	</a>
	</td>
</tr>";
echo "
<tr>
	<td>
		".$doc->icon ('tracking')."
	</td>
	<td>
		<a href='/guard/tracking/'>
		<b>Слежение</b><br />
		Знает все о переходах пользователях
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('dossier')."
	</td>
	<td>
		<a href='/guard/dossier/'>
		<b>Досье</b><br />
		Хранит историю нарушений
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('system')."
	</td>
	<td>
		<a href='/guard/system/'>
		<b>Система</b><br />
		Проверяет работоспособность системы
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('settings')."
	</td>
	<td>
		<a href='/guard/settings/'>
		<b>Настройки</b><br />
		Установка параметров модуля
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('contacts')."
	</td>
	<td>
		<a href='/guard/contacts/'>
		<b>Контакты</b><br />
		Информация для связи с автором
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('install')."
	</td>
	<td>
		<a href='/guard/install/'>
		<b>Установка</b><br />
		Восстановливает работу SiteGuard
		</a>
	</td>
</tr>
<tr>
	<td>
		".$doc->icon ('scripts')."
	</td>
	<td>
		<a title='Экслюзивные скрипты от PiloT' href='http://kopeyka.biz/shop/user.php?id=39'>
		<b>Скрипты</b><br />
		Эксклюзивные модули для Вашего сайта
		</a>
	</td>
</tr>
</table>";
?>