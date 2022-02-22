В sys/inc/tfoot.php вставить код:
echo '<br /><a href="/zh.php?url=http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">Жалоба</a><br />';