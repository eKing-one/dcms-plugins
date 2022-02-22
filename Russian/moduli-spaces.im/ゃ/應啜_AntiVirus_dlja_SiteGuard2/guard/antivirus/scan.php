<?php
require '../inc/sys.php';
require '../inc/antivirus.php';

if (isset ($_GET['prohibited_file_extensions']))
{
msg ("На сервере не рекомендуется хранить файлы такого расширения");
}



if (isset ($_GET['select_dir']))
{
echo "<form method='post' action='?start_scan' />";
echo "Сканировать:<br />
<select name='directory' size='7'>
<option value='' selected='selected'>Все папки</option>";
$dir = opendir (H);
while (FALSE !== ($objectName = readdir ($dir)))
{
/* Проверяем на вшивость */
if ($objectName == '.' || $objectName == '..' || is_file (H.$objectName))
	continue;
	echo "<option value='$objectName'>$objectName</option>";
}
echo "</select><br />";
echo "<input type='submit' value='Начать сканирование' />";
echo "</form>";
}

if (isset ($_GET['start_scan'])) 
{
/* Удаляем прошлый сеанс */
$antivirus->cleanUpFiles ();
/* Определяем директорию сканирования */
if (empty ($_POST['directory']))
	$path = H;
	else
	$path = H.my_esc ($_POST['directory']).'/';	
/* Начинаем порционное считывание файлов */
header ("Location: ?search_dirs=$path");
exit ();
}

if (isset ($_GET['search_dirs']))
{
/* Проверяем путь к директории */
	if (!empty ($_GET['search_dirs']))
	$path = my_esc ($_GET['search_dirs']);
	else
	exit ();
/* Если нет массива файлов, то читаем ФС */
	if (!is_array (@$_SESSION['guard_files_array']))
	{
	$dirHandle = opendir ($path);
		while (FALSE !== ($fileName = readdir ($dirHandle))) 
		{
			if ($fileName == '.' || $fileName == '..')
			continue;
		$filePath = $path.$fileName.'/';
			if (is_dir ($filePath))
			$_SESSION['guard_files_array'][] = $filePath;
		}
	}
	if (empty ($_SESSION['guard_objects_in_array']))
	$_SESSION['guard_objects_in_array'] = count ($_SESSION['guard_files_array']);
/* Массив с файлами готов. Порционно записываем в базу.
* Спасибо array_pop () и $antivirus->saveFilesList () */
	if (!empty ($_SESSION['guard_files_array']))
	{
/* Записываем в базу последний элемент массива и удаляем его */
	$lastObjectOfFilesArray = array_pop (&$_SESSION['guard_files_array']);
	$antivirus->saveFilesList ($lastObjectOfFilesArray);
	}
	else
	{
/* Записываем все файлы, которые были вне папок */
	$antivirus->saveFilesList ($path, 0);
/* Начинаем порционную проверку файлов */
	header ("Location: ?scanning=".mysql_result (mysql_query ("SELECT COUNT(`id`) FROM `guard_antivirus_files`"), 0));
	exit ();
	}
$objectsInArrayIsLeft = $_SESSION['guard_objects_in_array'] - count ($_SESSION['guard_files_array']);
$progress = round (($objectsInArrayIsLeft / $_SESSION['guard_objects_in_array'] * 100), 1);
echo "<div class='p_m'>".(!empty ($lastObjectOfFilesArray) ? $lastObjectOfFilesArray.'<br />' : null)."
<progress max='100' value='".preg_replace ("/\..*$/", "", ($progress / 2))."'></progress> <a title='Остановить сканирование' href='/guard/antivirus/'><img src='/guard/icons/off.png' alt='' /></a><br />
<img src='/guard/icons/dir_search2_24.png' alt='' /> Считано $objectsInArrayIsLeft из $_SESSION[guard_objects_in_array] ($progress %)</div>";
header ("Refresh: 1; url=?search_dirs=$path");
}

if (isset ($_GET['scanning']))
{
/* Всего файлов */
$numberOfFiles = (int) $_GET['scanning'];
	
$antivirus->checkFilesForViruses ();

/* Осталось файлов */
$fileRemains = mysql_result (mysql_query ("SELECT COUNT(`id`) FROM `guard_antivirus_files`"), 0);

/* Файлов проверено */
$filesChecked = $numberOfFiles - $fileRemains;

if ($fileRemains > 0)
$progress = round (($filesChecked / $numberOfFiles * 100), 1);
else
$progress = 100;

$infectedFiles = mysql_result (mysql_query ("SELECT COUNT(`id`) FROM `guard_antivirus_infected_files`"), 0);
$lastFileChecked = mysql_fetch_assoc (mysql_query ("SELECT * FROM `guard_antivirus_files` LIMIT 1"));
echo "
<div class='p_m'>".(!empty ($lastFileChecked['path']) ? $lastFileChecked['path'].'<br />' : null)."
<progress max='100' value='".(round ($progress / 2) + 50)."'></progress> <a title='Остановить сканирование' href='/guard/antivirus/'><img src='/guard/icons/off.png' alt='' /></a><br />
<img src='/guard/icons/dir_search2_24.png' alt='' /> Проверено $filesChecked из $numberOfFiles ($progress %)<br />
<img src='/guard/icons/".($infectedFiles > 0 ? 'guard_24' : 'guard_24').".png' alt='' /> Подозрительные файлы: $infectedFiles</div>";

	if ($progress < 100)
	header ("Refresh: 1; url=?scanning=$numberOfFiles");
	elseif (isset ($_GET['read_file']))
	{
	$antivirus->readInfectedFile ($_GET['read_file']);
	exit ();
	}
}

$antivirus->showInfectedFiles ();