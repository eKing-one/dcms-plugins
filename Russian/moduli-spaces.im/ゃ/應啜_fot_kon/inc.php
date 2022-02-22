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

  if ($user['id']<1) header('Location: /index.php');
  
  $a = mysql_fetch_array(mysql_query("select * from `FotoKonkurs_settings` WHERE `id` = '1'"));

  $user['id'] = $user['id'];
  $OnlineUser = $a['OnlineUser']; # Сколько пользователь будет находится онлайн после последнего клика
  $FotoStr = $a['GameStr']; # Сколько выводить элементов на страницу
  $Date = date('Y.m.d'); # Число текущего дня
  $Date2 = date('YmdHi'); # Число и время текущего дня
  $Inform = '<img src="img/inform.png" alt="*"/>'; # Иконка заголовка
  $Link = '<img src="img/Link.png" alt="*"/>'; # Иконка ссылок

  $div_name = '<div class="'.$a['div_name'].'">'; # Заголовки
  $div_link = '<div class="'.$a['div_link'].'">'; # Содержимое страниц
  
  $div_zebr1 = '<div class="'.$a['div_zebr1'].'">'; # Див зебры
  $div_zebr2 = '<div class="'.$a['div_zebr2'].'">'; # Див зебры 2
  
  $Raquo = '&raquo;';
  
  function text_out($text) { $text = stripslashes(htmlspecialchars(trim($text))); return $text; } # Обработка текста

?>