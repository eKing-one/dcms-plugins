/////
////	D.C.R.T
///	 ICQ: 2711149 
//	   E-MAIL: d.c.r.t@mail.ru
/
--------------------------------------------

Установка:
1) Скопировать файлы

2) выполнить запрос:

# ALTER TABLE `user` ADD `horo` INT NOT NULL DEFAULT '1', ADD `lhoro` INT NOT NULL DEFAULT '0'

3) В файле sys/inc/user.php в конце(перед ?>) прописать:
horoskop();

4) В файле anketa.php под полем "Дата рожденья" прописать:
echo $sethoroskop;

5) Все.