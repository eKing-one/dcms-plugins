<?php

/* Модуль: мини-двигатель для DCMS 6.6.4*/
/* Дата создания: 20.06.2012 */
/* Автор: PiloT */

define ("H", $_SERVER["DOCUMENT_ROOT"].'/');
require H.'sys/inc/start.php';
require H.'sys/inc/compress.php';
require H.'sys/inc/sess.php';
$ps = &$_SESSION; // укоротил для удобства
require H.'sys/inc/settings.php';
require H.'sys/inc/db_connect.php';
require H.'sys/inc/ipua.php';
require H.'sys/inc/fnc.php';
require H.'sys/inc/user.php';
require H.'sys/inc/thead.php';
if (!defined ('SiteGuard'))
require H.'guard/inc/control.php';
 ?>
<style>
.section
{
background: #f6f5f5;
border: 1px #d8d8d7 solid;
}
</style>
<?php

/* Защита от взлома панели управления SiteGuard */
if (!isset ($user) || $user['group_access'] < 15)
{
header ("Location: /index.php");
exit ();
}

function textImage ($text)
{
$text = preg_replace ('/\[(.+)\]/i', "<img src='/guard/icons/$1.png' alt='$1' />", $text);	
return $text;
}

/* Для функций движка. Отмена перевода строки */
function noBr ($status = false, $yesBr = false)
{
global $noBr;
if ($status)
	{
	$noBr = true;
	return false;
	}
if ($noBr)
	{
	if (!$yesBr)
	$noBr = false;
	return true;
	}
	else
	echo '<br />';
}

/* Многофункциональный класс для работы с интерфейсом */
class doc 
{
/* Начало страницы */
function __construct ()
	{
	global $ps, $title;
	if (!isset ($title))
	$title = 'Защита и контроль - SiteGuard 2';
	title ($title); aut ();
	if (isset ($ps['msg']))
		{
		if (!isset ($ps['div']))
			$ps['div'] = 'msg';
		echo "<div class='$ps[div]'>$ps[msg]</div>";
		unset ($ps['msg'], $ps['div']);
		}
	}
	
	
/* Вывод сообщений */
function msg ($msg = null, $loc = null, $div = 'msg', $stop = null)
	{
	global $ps, $user;
	if (!$loc)
	$loc = '?'.$_SERVER['QUERY_STRING'];
	if ($mysqlError = mysql_error ())
		{
		$msg = 'Ошибка запроса. Обратитесь к адмистрации';
		$div = 'err';
		}	
	if (isset ($ps['msg']))
		$ps['msg'] .= '<br />';
		else
		{
		$ps['msg'] = null;
		$ps['div'] = null;
		}
	$ps['msg'] .= $msg;
	if (isset ($user) && $user['group_access'] == 15 && $mysqlError)
	$ps['msg'] .= '<br />Ошибка MySQL: '.$mysqlError;
	if ($div && $ps['div'] !== 'err')
	$ps['div'] = $div;
	if ($loc)
		header ('Location: '.$loc);
	if ($stop)
	exit ();

	}

/* Проверка и вывод ошибок */
function errors ()
	{
	global $error;
	if ($error)
		{
		if (is_array($error))
			{
			foreach ($error as $key=>$value) 
				{
				echo "<div class='err'>$value</div>\n";
				}
			return true;
			}
			else
			echo "<div class='err'>$error</div>\n";
		return true;	
		}
	}
	
/* Вывод иконки	 */
function icon ($icon, $link = null)
	{
	if ($link == null)
	return '<img src="/guard/icons/'.$icon.'.png" alt="'.$icon.'" />';
	else
	return '<a href="'.$link.'"><img src="/guard/icons/'.$icon.'.png" alt="" /></a>';
	}
	
/* Вывод изображения */
function image ($image, $link = null, $width = null, $div = false)
	{
	if ($link == 1)
	$link = $image;
	?>
	<style>
	.image
	{
	border: 5px;
	}
	</style>
	<?php
	if ($div == true)
	$div = 'image';
	if ($div)
		echo "<span class='$div'>";
	$img = '<img src="'.(preg_match ('/\./', $image)?"$image\"":'icons/'.$image.'.png"').' '.($width?'width="'.$width.'"':null).' alt="" />';
	if ($div)
		echo "</span>";
	if ($link == null)
	return $img;
	else
	return '<a href="'.$link.'">'.$img.'</a>';
	}

/* Вывод ссылки */
	function link ($name, $link, $div = null, $text = null)
	{
	global $sysLinks;
	/* Режим меню */
	if ($sysLinks['menu'])
		{
		$name = '[point] '.$name;
		$div = 'post';
		}
	if ($div)
	echo "<div class='$div'>";
	$name = preg_replace ('/\[(.+)\]/i', "<img src='/guard/icons/$1.png' alt='$1' />", $name);
	echo "<a href='$link'>$name</a>";
	if ($text)
	echo '<br />'.$text;
	if ($div)
	echo '</div>';
	else
	noBr ();
	}

function lineLink ($name, $link, $separator = null)
	{
	if ($separator == null)
	$separator = '<br />';
	echo "<a href='$link'>$name</a>$separator";
	}
	
function section ($name)
	{
	echo "<div class='menu_razd'>$name</div>";
	}
	
/* Конец страницы	 */
function __destruct ()
	{
	global $conf, $sysLinks, $guard;
	/* Вырубаем режим вывода меню */
	$sysLinks = null;
	echo '&copy; PiloT (Байбухтин Дмитрий)<br />
	<div class="foot">';
	noBr (1);
	$this->link ('SiteGuard2 | ', '/guard/');
	noBr (1);
	$this->link ('Защита | ', '/guard/guard/');
	noBr (1);
	$this->link ('АнтиВирус | ', '/guard/antivirus/');
	noBr (1);
	$this->link ('АнтиСпам | ', '/guard/antispam/');
	noBr (1);
	$this->link ('Слежение | ', '/guard/tracking/');
	noBr (1);
	$this->link ('Досье | ', '/guard/dossier/');
	$this->link ('Настройки', '/guard/settings/');
	if (!empty ($guard))
		$guard->__destruct ();
	echo '<a href="/index.php">На главную</a></div></body></html>';
	}
}

/* Систменые функции */
class system
{

function not_null ($var, $text = null)
{
if (empty ($var))
	return ($text?$text:'пусто');
	else
	return $var;
}

/* Форматор времени	 */
function timer ($time, $text = false)
	{
		if ($time < 60)
		{
		$type = 'секунд(а)';
		}
		elseif ($time < 3540)
		{
		$time = $time/60;
		$type = 'минут(а)';
		}
		elseif ($time < 86340)
		{
		$time = $time / 3600;
		$type = 'час(а)';
		}
		else
		{
		$time = $time/86400;
		$type = 'день';
		}
		if ($time < 0)
		$time = 0;
		if ($time == 0 && $text == true)
		{
		return 'только что';
		$text = false;
		}
	return round ($time).' '.$type.($text?' назад':null);
	}
	
/* Автоматическая проверка существования определенных POST или GET значений */
function verify ($arg, $type = 0)
	{
	global $_GET, $_POST;
	if ($type == '0')
		{
		if (isset ($_GET["$arg"]))
		return my_esc ($_GET["$arg"]);
		}
		else
		{
		if (isset ($_POST["$arg"]))
		return my_esc ($_POST["$arg"]);
		}
	}


/* Подтверждение действий */
function confirm ($get, $loc, $curLoc = null)
	{
global $_GET, $$get, $doc;
if (isset ($_GET[$get]))
		{
	if (isset ($_GET['ok']))
			{
			$doc->msg ('Действие успешно применено', $loc);
			$$get = intval ($_GET[$get]);
			return true;
			}
			else
			echo "<div class='msg'>Вы точно хотите это сделать?<br />
			<a href='?".implode('&amp;',$_SERVER['argv'])."&amp;ok'>Подтвердить</a> | <a href='?".($curLoc?$curLoc:implode('&amp;',$_SERVER['argv']))."'>Отменить</a></div>";
		}	
	}
}

/* Функции для работы с пользователями */
class user
{
/* Отправка сообщения от системы пользователю во внутреннюю почту */
function sysMsg ($msg, $user)
	{
	global $time;
	mysql_query ("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES (0, $user, $msg, $time)");
	}	
}

/* Функции для работы с MySQL */
class sql
{
/* Вывод информации из MySQL */
function output ($tbl, $pars = null, $order = '`id` DESC', $list_page = null, $listoff = 0)
	{
	global $while, $set, $nullres, $list, $text, $k_post, $doc;
	if ($pars)
		{
		if (is_array ($pars))
			{
			$pars = 'WHERE '.implode (' AND ', $pars);
			$pars = str_replace ('AND OR', 'OR', str_replace ('WHERE OR', 'WHERE', $pars)); // если хотим не AND, а OR
			}
			else
			$pars = 'WHERE '.$pars;
		}
		else
		$pars = null;
	if ($order !== null)
		$order = 'ORDER BY '.$order;
	$k_post = mysql_num_rows (mysql_query ("SELECT `id` FROM `$tbl`$pars".($listoff?' LIMIT '.$listoff:null)));
	$k_page = k_page ($k_post, $set['p_str']);
	$page = page ($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	if ($k_post == 0)	
	{
	$nullres = 1;
	echo '<div class="post">'.($text['nores']?$text['nores']:'Нет результатов').'</div>';
	}
	$limit = " LIMIT $start, $set[p_str]";
	if ($listoff > 1)
	$limit = ' LIMIT '.$listoff;
	elseif ($listoff == 1)
	$limit = null;
	$while = mysql_query ("SELECT * FROM `$tbl` $pars $order{$limit}");
	if ($listoff == false)
	$list = ($k_page > 1 ? str ('?'.$list_page, $k_page, $page):null);
	$text = null;
	if ($k_post)
		return true;
		else
		return false;
	}
	
function assocArray ($tbl, $pars = null, $order = null)
	{
	global $doc;
	if (is_numeric ($pars))
		$pars = '`id` = '.intval ($pars);
	if ($pars !== null)
		$pars = 'WHERE '.$pars;
	if ($order !== null)
		$order = 'ORDER BY '.$order;
	if (($mass = mysql_fetch_assoc (mysql_query ("SELECT * FROM `$tbl` $pars $order LIMIT 1"))))
		return $mass;
		else
		{
		return false;
		}
	}
}

/* Функции для вывода форм ввода */
class form
{
/* Начало формы ввода */
function __construct ($action='?', $file = false, $method = 'post')
	{
	echo '<form method="'.$method.'" action="'.$action.'"'.($file?' enctype="multipart/form-data"':null).'>';
	}
	

/* Текстовое полее ввода */
function input ($name, $fname, $value = null, $size = null)
	{
	global $noBr;
	echo $name;
	noBr (null, true);
	/* Большое текстовое поле */
	if ($size == 'big')
	echo "<textarea name='$fname'>$value</textarea>";
	else
	/* Маленькое текстовое поле */
	echo '<input type="text" name="'.$fname.'" value="'.$value.'"'.($size?' size="'.$size.'"':null).' />';
	noBr ();
	}
	
	/* Текстовое поле без перевода строки */
function brInput ($name, $fname, $value = null, $size = null)
	{
	/* Маленькое текстовое поле */
	echo '<input type="text" name="'.$fname.'" value="'.$value.'"'.($size?' size="'.$size.'"':null).' /> '.$name;
	noBr ();
	}
	
/* Начало списка	 */
function select ($name)
	{
	echo '<select name="'.$name.'">';
	}

/* Элемент списка	 */
function option ($name, $fname, $value, $stop = null)
	{
	echo '<option name="'.$fname.'" value="'.$value.'">'.$name.'</option>';
	if ($stop)
		echo '</select>';
	}

/* Поле ввода "метка" */
function checkbox ($name, $fname, $checked = false)
	{
	echo "<label><input type='checkbox' name='$fname' value='1' ".($checked?'checked="checked"':null)." /> $name</label><br />";
	}

/* Заголовок перед зависимыми "метками" */
function section ($name)
	{
	$name = textImage ($name);
	echo "<b>$name</b>:<br />";
	}
	
/* Зависимая "метка" */
function radio ($name, $fname, $value, $checked = null)
	{
	if ($checked)
		{
		if ($checked == $value)
		$checked = true;
		elseif (is_numeric ($checked))
		$checked = true;
		else
		$checked = false;
		}
	echo "<label><input type='radio' name='$fname' value='$value' ".($checked?'checked="checked"':null)." /> $name</label><br />";
	}
	
/* Скрытое поле */
function hidden ($value, $fname)
	{
	echo "<input type='hidden' value='$value' name='$fname' />";
	}
	
/* Конец формы ввода	 */
function end ($name, $fname = 'ok', $refresh = false)
	{
	echo "<input type='submit' value='$name' ".(isset ($fname)?"name='$fname'":null)
.' />';
	if ($refresh)
	echo "<input type='submit' name='refresh' value='Обновить' />";
	echo '</form>';
	}
}

/* Фильтрация и проверка входящих данных */
class filters
{
/* Фильтр выводимой информации */
function out ($text)
	{
	return htmlspecialchars (stripslashes ($text));
	}
	
/* Фильтр входящей информации */
function in ($text)
{
return mysql_real_escape_string (strip_tags ($text)); 
}	
	
/* Проверка существования POST-запроса */
function post ($varName, $showError = false)
	{
	global $error, $$varName;
	if ($_POST[$varName])
		$$varName = $this->in ($_POST[$varName]);
		elseif ($showError)
		$error = 'Заполнены не все поля';
		else
		$$varName = null;
	}
	
/* Проверка существования GET-запроса */
function get ($varName, $showError = true)
	{
	global $error, $$varName;
	if (isset ($_GET[$varName]))
		{
		$$varName = $this->in ($_GET[$varName]);
		return $$varName;
		}
		elseif ($showError)
		{
		echo '<div class="err">Ошибка перехода (не правильный GET-запрос)</div>';
		exit();
		}
		else
		$$varName = null;
	}
}

$doc = new doc;
$sys = new system;
$usr = new user;
$sql = new sql;
$filt = new filters;
if (isset ($_GET['page']))
$page = $filt->in ($_GET['page']);
else
$page = null;

?>