<?php
require '../inc/sys.php';
require '../inc/antivirus.php';

if (!empty ($_GET['add_exception']))
{
$antivirusExceptions->addException ($_GET['add_exception']);
header ("Location: /guard/antivirus/");
exit ();
}

if (!empty ($_GET['delete_exception']))
{
$antivirusExceptions->deleteException ($_GET['delete_exception']);
}

$antivirusExceptions->showExceptions ();