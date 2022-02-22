<?php

###################################################
#   Фотоконкурсы под dcms 6.6.4 и 6.7.7           #
#   Автор: Nort, он же Lns                        #
#   icq: 484014288, сайт: http://inwap.org        #
#                                                 #
#   Вы не имеете права продавать, распростронять, #
#   давать друзьям даный скрипт.                  #
#                                                 #
#   Даная версия являет платной, и купить         #
#   можно только у автора.                        #
###################################################

  $FotoKonkurs = mysql_num_rows(mysql_query("select * from `FotoKonkurs`"));
  $FotoKonkursUser = mysql_num_rows(mysql_query("select * from `FotoKonkursUser`"));
  echo $FotoKonkurs.'/'.$FotoKonkursUser;

?>