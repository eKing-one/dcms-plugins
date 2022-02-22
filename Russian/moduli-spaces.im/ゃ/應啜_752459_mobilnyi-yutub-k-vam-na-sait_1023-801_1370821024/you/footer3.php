<div class="shout">Поиск</div>
<form method="get" action="search.php">
<div class="menu3">
      Что ищем: <br/>
      <input type="text" name="vq" /><br />
     
      Сортировать по: <br/>
      <select name="s">
        <option value="viewCount">Просмотры пользователей</option>
        <option value="rating">Рейтенгу</option>
        <option value="published">Дате</option>
      </select>
      <br/>
      <input type="submit" name="submit" value="Поиск"/> 
</div> 

<div class="shout">YOUTUBE видео</div>

<div class="menu3">
<a href="categories.php">Категории</a><br/>
<a href="most_recent.php">Новые</a><br/>
<a href="recently_featured.php">Недавно показываемые</a><br/>
<a href="most_viewed.php">Наиболее рассматриваемые</a><br/>
<a href="top_favorites.php">ГЛАВНЫЕ ФАВОРИТЫ</a><br/>
<a href="top_rated.php">Высшая оценка</a><br/>
</div>

<?php
echo $file;

include_once '../sys/inc/tfoot.php';
if (isset($site))
{
?>
<div class="footer">
<a href="<?php print $site;?>"><?php print $sitename;?></a><br/>
</div>
<?php
}
?>

</form>

</body>
</html>