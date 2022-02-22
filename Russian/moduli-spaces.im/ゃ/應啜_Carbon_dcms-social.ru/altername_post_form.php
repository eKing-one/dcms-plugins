<?
echo "<table width='100%'>\n";
echo "<tr>\n";
echo "<td>\n";
echo "Сообщение:<br />\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width='100%'>\n";
if (!isset($msg2))$msg2=NULL;
echo '<textarea name="msg" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">'.$msg2.'</textarea><br />';
echo "</td>\n";

echo "<td>\n";

echo "<div id='smiles_0'>\n";
echo "<table height='150'>\n";
echo "<tr>\n";

echo "<td><a href=\"javascript:smiles('show');\"><img src=\"/style/themes/".$set['set_them']."/smiles_0.png\" border=\"0\" alt='' title='Показать панель смайлов' /></a></td>\n";

echo "</tr>\n";
echo "</table>\n";
echo "</div>\n";

echo "<div id='smiles_1'>\n";
echo "<table width='200' height='150'>\n";
echo "<tr>\n";
echo "<td><a href=\"javascript:emoticon(':smile:')\"><img src=\"/style/smiles/smile.gif\" border=\"0\" alt=\"\" title=\"Улыбка\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':sad:')\"><img src=\"/style/smiles/sad.gif\" border=\"0\" alt=\"\" title=\"Грусть\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':COOL:')\"><img src=\"/style/smiles/dirol.gif\" border=\"0\" alt=\"\" title=\"Крут\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':wink:')\"><img src=\"/style/smiles/wink.gif\" border=\"0\" alt=\"\" title=\"Подмигивание\" /></a></td>";
echo "<td rowspan='4'><a href=\"javascript:smiles('hide');\"><img src=\"/style/themes/".$set['set_them']."/smiles_1.png\" border=\"0\" alt=\"\" title=\"Скрыть панель смайлов\" /></a></td>";
echo "</tr>\n";

echo "<tr>\n";
echo "<td><a href=\"javascript:emoticon('O_O')\"><img src=\"/style/smiles/shok.gif\" border=\"0\" alt=\"\" title=\"Шок\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':BRAVO:')\"><img src=\"/style/smiles/clapping.gif\" border=\"0\" alt=\"\" title=\"Апплодисменты\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':-D')\"><img src=\"/style/smiles/biggrin.gif\" border=\"0\" alt=\"\" title=\"Ха-ха\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':\'-(\ ')\"><img src=\"/style/smiles/cray.gif\" border=\"0\" alt=\"\" title=\"Слезы\" /></a></td>";
echo "</tr>\n";

echo "<tr>\n";
echo "<td><a href=\"javascript:emoticon(':angry:')\"><img src=\"/style/smiles/aggressive.gif\" border=\"0\" alt=\"\" title=\"Агрессия\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':-|')\"><img src=\"/style/smiles/fool.gif\" border=\"0\" alt=\"\" title=\"Дурак\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':-/')\"><img src=\"/style/smiles/beee.gif\" border=\"0\" alt=\"\" title=\"Беее\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':diablo:')\"><img src=\"/style/smiles/diablo.gif\" border=\"0\" alt=\"\" title=\"Дьявол\" /></a></td>";
echo "</tr>\n";

echo "<tr>\n";
echo "<td><a href=\"javascript:emoticon(':crazy:')\"><img src=\"/style/smiles/crazy.gif\" border=\"0\" alt=\"\" title=\"Чекнутый\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':HZ:')\"><img src=\"/style/smiles/dntknw.gif\" border=\"0\" alt=\"\" title=\"Х.з.\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':sorry:')\"><img src=\"/style/smiles/sorry.gif\" border=\"0\" alt=\"\" title=\"Извини\" /></a></td>";
echo "<td><a href=\"javascript:emoticon(':YAHOO:')\"><img src=\"/style/smiles/yahoo.gif\" border=\"0\" alt=\"\" title=\"\" /></a></td>";
echo "</tr>\n";
echo "</table>\n";
echo "</div>\n";
echo "<td>\n";
echo "</tr>\n";
echo "</table>\n";

?>