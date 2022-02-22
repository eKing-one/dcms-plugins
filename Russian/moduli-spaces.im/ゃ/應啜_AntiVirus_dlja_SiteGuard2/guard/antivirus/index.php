<?php
require '../inc/sys.php';
require '../inc/antivirus.php';

if (isset ($_GET['prohibited_file_extensions']))
{
msg ("На сервере не рекомендуется хранить файлы такого расширения");
}

echo "<div class='p_m'>
<a href='/guard/antivirus/scan.php?start_scan'><img src='/guard/icons/search3_24.png' alt='' /> Сканировать сайт</a><br />
<a href='/guard/antivirus/scan.php?select_dir'><img src='/guard/icons/dir2_24.png' alt='' /> Сканировать папку</a><br />
<a href='/guard/antivirus/get_results.php'><img src='/guard/icons/load_24.png' alt='' /> Скачать результаты сканирования</a><br />
<a href='/guard/antivirus/exceptions.php'><img src='/guard/icons/accept_24.png' alt='' /> Исключения из сканирования</a><br />";
echo "
<a href='/guard/antivirus/help.php'><img src='/guard/icons/help_24.png' alt='' /> Помощь</a></div>";
$antivirus->showInfectedFiles ();