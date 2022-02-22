Установка:
Распаковать в корень!
В файлах где есть форма ввода (например: /guest/index.php), изменить её так, как показано в примере:

Как выглядит до изменения:
echo "<form method='post' name='message'>";
echo "<textarea cols='20' rows='2' name='msg'></textarea><br>";
echo "<input value='Добавить' type='submit' name='add' />";
echo "</form>";

Как должна выглядеть после изменения:
echo "<form method='post' name='message'>";
// Вставить следущее
echo "<script src='/js/qbbcodes.js'></script>";
include ('../js/bbcodes.php');
// Конец
echo "<textarea cols='20' rows='2' name='msg'></textarea><br>";
// Вставить следущее
echo "<script src='/js/qsmiles.js'></script>";
include ('../js/smiles.php');
// Конец
echo "<input value='Добавить' type='submit' name='add' />";
echo "</form>";

ВНИМАНИЕ!
Если имя формы не message (<form name='message'>) и имя текстового поля не msg (<textarea name='msg'>), то работать не будет!!!

Можно изменять/добавлять/удалять список выводимых смайлов, изменив/добавив/удалив такую строку: <a href="javascript:%20x()" onclick="DoSmilie('текст для вставки');"><img src="/путь_к_картинке.png" alt=""></a> (в файле /js/qsmiles.php), также и с ББ-кодами <a href="javascript:tag('открытие тега', 'закрытие тега')"><img src="/путь_к_картинке.png" alt=""></a> (в файле /js/qbbcodes.php).

По всем вопросам или если понадобится помощь в установке, писать на e-mail: DCMS_WAP@spaces.ru
Также переделаю под любой двиг, по желанию, БЕСПЛАТНО!