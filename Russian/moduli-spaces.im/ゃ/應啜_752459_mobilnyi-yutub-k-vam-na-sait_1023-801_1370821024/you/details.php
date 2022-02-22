<?php
////////////////////////////// Download stuff
$action =@ $_GET["action"];
if ($action=="download")
{
// File: 	phpyoutube.php
// Version: 2.2
// Date:	06/04/2009
// Web:		http://blog.unijimpe.net
function getContent($url) {
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;    
}
function fetch_headers($url) {
	$headers = array();
	$url = trim($url);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_NOBODY ,1);
	$data = curl_exec($ch);
	$errormsg = curl_error($ch);
	curl_close($ch);
					
	$headers = explode("\n", $data);
	return $headers;
}
function getYoutubeToken($id) {
	$path = "http://www.youtube.com/get_video_info?";
	$cont = getContent($path."&video_id=".$id);
	parse_str($cont, $opts);
	return $opts['token'];
}


$videoItem = trim($_GET['item']);
$videoType = "";
$videoPath = "http://www.youtube.com/get_video";

if ($_GET['type'] != "0") {
	$videoType = "&fmt=".$_GET['type'];
}
if ($videoItem != "") {
	$videoTokn = getYoutubeToken($videoItem);
	$videoURL = $videoPath."?video_id=".$videoItem."&t=".$videoTokn.$videoType;
	$headers = fetch_headers($videoURL);
	for ($i=0; $i<count($headers); $i++) {
		if (strstr($headers[$i], "ocation:")) {
			$str1 = explode("ocation:", $headers[$i]);
			$link = trim($str1[1]);
			break;
		}
	}
	header("Location: ".$link);
	exit;
}

}

/////////////End of download stuff

include("page_head.php");

?>


    <?php
    // function to parse a video <entry>
    function parseVideoEntry($entry) {      
      $obj= new stdClass;
      
      // get author name and feed URL
      $obj->author = $entry->author->name;
      $obj->authorURL = $entry->author->uri;
      
      // get nodes in media: namespace for media information
      $media = $entry->children('http://search.yahoo.com/mrss/');
      $obj->title = $media->group->title;
      $obj->description = $media->group->description;
      
      // get video player URL
      $attrs = $media->group->player->attributes();
      $obj->watchURL = $attrs['url']; 
      
      // get video thumbnail
      $attrs = $media->group->thumbnail[0]->attributes();
      $obj->thumbnailURL = $attrs['url']; 
            
      // get <yt:duration> node for video length
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $obj->length = $attrs['seconds']; 
      
      // get <yt:stats> node for viewer statistics
      $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
      if ($yt->statistics) {
      $attrs = $yt->statistics->attributes();
      $obj->viewCount = $attrs['viewCount']; 
      } else {
      $obj->viewCount = 0;
      }





      // get <gd:rating> node for video ratings
      $gd = $entry->children('http://schemas.google.com/g/2005'); 
      if ($gd->rating) { 
        $attrs = $gd->rating->attributes();
        $obj->rating = $attrs['average']; 
      } else {
        $obj->rating = 0;         
      }
        
      // get <gd:comments> node for video comments
      $gd = $entry->children('http://schemas.google.com/g/2005');
      if ($gd->comments->feedLink) { 
        $attrs = $gd->comments->feedLink->attributes();
        $obj->commentsURL = $attrs['href']; 
        $obj->commentsCount = $attrs['countHint']; 
      }
      
      // get feed URL for video responses
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.responses']"); 
      if (count($nodeset) > 0) {
        $obj->responsesURL = $nodeset[0]['href'];      
      }
         
      // get feed URL for related videos
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.related']"); 
      if (count($nodeset) > 0) {
        $obj->relatedURL = $nodeset[0]['href'];      
      }
    
      // return object to caller  
      return $obj;      
    }   
    
    // get video ID from $_GET 
    if (!isset($_GET['id'])) {
      die ('ERROR: Missing video ID');  
    } else {
      $vid = $_GET['id'];
    }

    // set video data feed URL
    $feedURL = 'http://gdata.youtube.com/feeds/mobile/videos/' . $vid;

    // read feed into SimpleXML object
    $entry = simplexml_load_file($feedURL);
    
    // parse video entry
    $video = parseVideoEntry($entry);





       
    // display main video title

echo "<div class=\"header\">";
echo "{$video->title}";
echo "</div>";


    // get mobile stream url

$mobstream = $_GET["mobstream"];


    // display video thumbnail/stream/download 
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
<?php
    echo "<div class=\"shoutmenu\">\n";
    echo "<a href=\"$mobstream\"><img class=\"avatar\" src=\"$video->thumbnailURL\" alt=\"\" /></a><br/>\n";
    //echo "<a href=\"$mobstream\">Stream Video</a><br/>\n";

?>


                
                <input name="item" id="item" type="hidden" value="<?php echo "$vid";?>" />
                <input name="action" type="hidden" value="download" />
                <select id="type" name="type">
                  <option value="13">3GP (LQ)&nbsp;</option>
                  <option value="17">3GP (HQ)&nbsp;</option>
                  <option value="18">MP4 &nbsp;</option>
                  <option value="0">FLV &nbsp;</option>
                </select>
                
                
                <input name="btget" id="btget" type="submit" value="Download" />
                
             
<?php

 echo "</div></form>";

          // display Full description
if(isset($_GET["fulldscr"]))
{
echo "<div class=\"shout\">VIDEO DETAILS</div>\n";

echo "<div class=\"menu3\">\n";
    echo "<b>Duration:</b> ";
    echo sprintf("%0.2f", $video->length/60) . " min<br/> 
    <b>Rating:</b> {$video->rating}<br/> <b>Views:</b> {$video->viewCount}<br/>\n";
echo "</div>";


 echo "<div class=\"shout\">DESCRIPTION</div>\n";

echo "<div class=\"menu3\">";
$viddescrpt = $video->description;
$viddescrpt = str_replace('"', '',$viddescrpt);
 echo "$viddescrpt </div>\n";


$authorFeed = simplexml_load_file($video->authorURL);    
    echo "<div class=\"shout\">UPLOADED BY</div>\n";
    $authorData = $authorFeed->children('http://gdata.youtube.com/schemas/2007');

echo "<div class=\"menu3\">";
    echo "<b>Username:</b> " . $video->author . 
    "<br/>\n";
    echo "<b>Age:</b> " . $authorData->age . 
    "<br/>\n";
    echo "<b>Gender:</b> " . 
    strtoupper($authorData->gender) . "<br/>\n";
    echo "<b>Location:</b> " . $authorData->location . "</div>\n";    
   


include("footer3.php");    
    ?>
  </body>
</html>    
<?php
exit();
}



echo "<div class=\"shout\">VIDEO DETAILS</div>\n";

echo "<div class=\"menu3\">\n";
    echo "<b>Duration:</b> ";
    echo sprintf("%0.2f", $video->length/60) . " min<br/> 
    <b>Rating:</b> {$video->rating}<br/> <b>Views:</b> {$video->viewCount}<br/>\n";
echo "</div>";

    echo "<div class=\"shout\">DESCRIPTION</div>\n";

echo "<div class=\"menu3\">";

$stringlength = strlen("{$video->description}");

if ($stringlength<300)
{
$viddescrpt = $video->description;
$viddescrpt = str_replace('"', '',$viddescrpt);
}else
{
$viddescrpt = substr("{$video->description}",0,300);
$viddescrpt = str_replace('"', '',$viddescrpt);
$viddescrpt = "$viddescrpt <a href=\"?id=$vid&amp;mobstream=$mobstream&amp;fulldscr\">[...]</a>";
}


    echo "$viddescrpt </div>\n";
    
    // read 'author profile feed' into SimpleXML object
    // parse and display author bio
    $authorFeed = simplexml_load_file($video->authorURL);    
    echo "<div class=\"shout\">UPLOADED BY</div>\n";
    $authorData = $authorFeed->children('http://gdata.youtube.com/schemas/2007');

echo "<div class=\"menu3\">";
    echo "<b>Username:</b> " . $video->author . 
    "<br/>\n";
    echo "<b>Age:</b> " . $authorData->age . 
    "<br/>\n";
    echo "<b>Gender:</b> " . 
    strtoupper($authorData->gender) . "<br/>\n";
    echo "<b>Location:</b> " . $authorData->location . "</div>\n";    
   
    echo "\n";
include("footer3.php");    
    ?>  