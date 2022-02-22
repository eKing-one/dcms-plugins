<?php
include("page_head.php");
?>

<div class="header">Мобильный YouTube</div>

    <?php
    // set URL for XML feed containing category list
    $catURL = 'http://gdata.youtube.com/schemas/2007/categories.cat';
    
    // retrieve category list using atom: namespace
    // note: you can cache this list to improve performance, 
    // as it doesn't change very often!
    $cxml = simplexml_load_file($catURL);
    $cxml->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
    $categories = $cxml->xpath('//atom:category');
    


    // iterate over category list
    foreach ($categories as $c) {
if(($c['term']=="Autos") || ($c['term']=="Comedy") || ($c['term']=="Entertainment") || ($c['term']=="Sports") || ($c['term']=="News"))
{
      // for each category    
      // set feed URL
      $feedURL = "http://gdata.youtube.com/feeds/mobile/videos/-/{$c['term']}?max-results=1&orderby=viewCount&format=1";
      
      // read feed into SimpleXML object
      $sxml = simplexml_load_file($feedURL);
      
      // get summary counts from opensearch: namespace
      $counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
      $total = $counts->totalResults; 

     
      // iterate over entries in category
      // print each entry's details
      foreach ($sxml->entry as $entry) {
        // get nodes in media: namespace for media information
        $media = $entry->children('http://search.yahoo.com/mrss/');
        
        // get video player URL
        $attrs = $media->group->player->attributes();
        $watch = $attrs['url']; 


        // get 3GP STREAM URL 2
        $attrs = $media->group->content[0]->attributes();
        $mobilestream = $attrs['url']; 

// get video thumbnail
        $attrs = $media->group->thumbnail[0]->attributes();
        $thumbnail = $attrs['url']; 
        
        // get <yt:duration> node for video length
        $yt = $media->children('http://gdata.youtube.com/schemas/2007');
        $attrs = $yt->duration->attributes();
        $length = $attrs['seconds']; 
        
        // get <gd:rating> node for video ratings
        $gd = $entry->children('http://schemas.google.com/g/2005'); 
        if ($gd->rating) {
          $attrs = $gd->rating->attributes();
          $rating = $attrs['average']; 
        } else {
          $rating = 0; 
        }


// get video ID
        $arr = explode('/',$entry->id);
        $id = $arr[count($arr)-1];


?>
       <div class="menu3">
<table>
<tr valign="middle">
<td>

<?php

///////// PREVIEW IMAGE AS A LINK TO MOBILE STREAM

echo "<a href=\"{$mobilestream}\">";
echo "<img class=\"avatar\" src=\"$thumbnail\" width=\"60\" height=\"45\" alt=\"\" />";
echo "</a>\n";
?>

</td>
<td style="padding-left:2px;">
<div style="padding-bottom:1px;">

<?php

///////// VIDEO TITLE AS A LINK TO DETAILS PAGE

echo "<a href=\"details.php?id=$id&amp;mobstream={$mobilestream}\">{$media->group->title}</a>\n";
?>

</div>

<?php

///////// VIDEO LENGHT AS MM:SS // VIDEO RATING OUT OF 5



if (($rating=="0") || (($rating>0.0) && ($rating<0.5)))
{
$rating = "<img src=\"stars/0.0.gif\" alt=\"\"/>";
}else
if (($rating=="0.5") || (($rating>0.5) && ($rating<1.0)))
{
$rating = "<img src=\"stars/0.5.gif\" alt=\"\"/>";
}else
if (($rating=="1.0") || (($rating>1.0) && ($rating<1.5)))
{
$rating = "<img src=\"stars/1.0.gif\" alt=\"\"/>";
}else
if (($rating=="1.5") || (($rating>1.5) && ($rating<2.0)))
{
$rating = "<img src=\"stars/1.5.gif\" alt=\"\"/>";
}else
if (($rating=="2.0") || (($rating>2.0) && ($rating<2.5)))
{
$rating = "<img src=\"stars/2.0.gif\" alt=\"\"/>";
}else
if (($rating=="2.5") || (($rating>2.5) && ($rating<3.0)))
{
$rating = "<img src=\"stars/2.5.gif\" alt=\"\"/>";
}else
if (($rating=="3.0") || (($rating>3.0) && ($rating<3.5)))
{
$rating = "<img src=\"stars/3.0.gif\" alt=\"\"/>";
}else
if (($rating=="3.5") || (($rating>3.5) && ($rating<4.0)))
{
$rating = "<img src=\"stars/3.5.gif\" alt=\"\"/>";
}else
if (($rating=="4.0") || (($rating>4.0) && ($rating<4.5)))
{
$rating = "<img src=\"stars/4.0.gif\" alt=\"\"/>";
}else
if (($rating=="4.5") || (($rating>4.5) && ($rating<5.0)))
{
$rating = "<img src=\"stars/4.5.gif\" alt=\"\"/>";
}else
if (($rating=="5.0") || ($rating>5.0))
{
$rating = "<img src=\"stars/5.0.gif\" alt=\"\"/>";
}



echo sprintf("%0.2f", $length/60) . "&nbsp;&nbsp;&nbsp; {$rating} <br/>";

echo "</td></tr></table></div>";


}    
}}
include("footer3.php");
?>