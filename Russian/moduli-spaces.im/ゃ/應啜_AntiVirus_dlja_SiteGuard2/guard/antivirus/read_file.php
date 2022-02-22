<?php

require '../inc/sys.php';
require '../inc/antivirus.php';

if (!empty ($_GET['file_path']))
{
$antivirus->readFile ($_GET['file_path']);
}