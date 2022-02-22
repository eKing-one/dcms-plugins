<?php
define ('H', $_SERVER['DOCUMENT_ROOT'].'/');
require H.'sys/inc/start.php';
require H.'sys/inc/compress.php';
require H.'sys/inc/sess.php';
require H.'sys/inc/home.php';
require H.'sys/inc/settings.php';
require H.'sys/inc/db_connect.php';
require H.'sys/inc/ipua.php';
require H.'sys/inc/fnc.php';
require H.'sys/inc/user.php';
require '../inc/antivirus.php';


if (!empty ($_GET['get_results']))
{
$type = htmlspecialchars ($_GET['get_results']);
$result = null;
		if ($type == 'html')
		$result .= "<html><head><title>Результаты сканирования ".vremja ($time)."</title></head>
		<body>";
$q = mysql_query ("SELECT * FROM `guard_antivirus_infected_files`");
	while ($file = mysql_fetch_assoc ($q))
	{
		if ($type == 'text')
		$result .= "<div class='p_m'>";
	$result .= str_replace (H, '', $file['path']);
		if ($type == 'text')
		$result .= "</div>";
		elseif ($type == 'html')
		$result .= "<br />";
		elseif ($type == 'txt')
		$result .= '
';
	}
	if ($type == 'html')
	$result .= "</body></html>";
	
	if (empty ($result))
	msg ('Подозрительные файлы отсутствуют');
	else
	{
	require H.'sys/inc/downloadfile.php';
		if ($type == 'text')
		{
		echo $result;
		}
		elseif ($type == 'txt')
		{
		file_put_contents (H."sys/tmp/$sess.txt", $result);
		DownloadFile (H."sys/tmp/$sess.txt", 'siteguard_scan_results_'.date ('d.m').'.txt', 'text/plain;charset=utf-8');
		unlink (H."sys/tmp/$sess.txt");
		}
		elseif ($type == 'html')
		{
		file_put_contents (H."sys/tmp/$sess.html", $result);
		DownloadFile (H."sys/tmp/$sess.html", 'siteguard_scan_results_'.date ('d.m').'.html', 'text/html;charset=utf-8');
		unlink (H."sys/tmp/$sess.html");
		}
	exit;
	}
}
require H.'sys/inc/thead.php';
title ();
aut ();
echo "<div class='p_m'>
<a href='?get_results=text'><img src='/guard/icons/point.png' alt='' /> Показать в виде текста</a><br />
<a href='?get_results=txt'><img src='/guard/icons/point.png' alt='' /> Скачать в формате TXT</a><br />
<a href='?get_results=html'><img src='/guard/icons/point.png' alt='' /> Скачать в виде HTML-страницы</a>
</div>
<div class='p_m'><a href='/guard/antivirus/'><img src='/guard/icons/search3_24.png' alt='' /> SiteGuard 2 - антивирус</a></div>";

require H.'sys/inc/tfoot.php';