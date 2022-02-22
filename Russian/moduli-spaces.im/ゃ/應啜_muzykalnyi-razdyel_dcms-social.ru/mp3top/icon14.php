<?


if (is_file("inc/icon14/$ras.php"))
include "inc/icon14/$ras.php";
elseif (is_file(H.'style/themes/'.$set['set_them'].'/loads/14/'.$ras.'.png'))
echo "<img src='/style/themes/$set[set_them]/loads/14/$ras.png' alt='$ras' />\n";
else echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' />\n";



?>