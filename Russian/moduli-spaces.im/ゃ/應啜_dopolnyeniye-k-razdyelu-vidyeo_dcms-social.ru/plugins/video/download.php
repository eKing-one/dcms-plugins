<?
// Класс для работы с видео

class YT {

    /**
     * @var array $info - Video data
     */
    private static $info;

    /**
     * @var string $id - Video id
     */
    private static $id;

    /**
     * @var array $links - Links array
     */
    private static $links = array();

    /**
     * @var array $data - Media information about video
     */
    private static $data = array();

    /**
     * @var string $user_agent - useragent for getting data
     * Can be edited
     */
    private static $user_agent = 'Youtube Tools v.1';

    /**
     * @var bool $proxy - Use proxy
     * Can be edited (true, false)
     */
    private static $proxy = true;

    /**
     * @var array $proxy_list - List of the proxy servers
     */
    private static $proxy_list = array();

    /**
     * @var int $proxy_attempts - Number of attempts to use a proxy (Determined automatically)
     */
    private static $proxy_attempts = 0;

    /**
     * @var array $formats - Formats of youtube video
     */
    private static $formats = array(
        '5'=>'flv',
        '6'=>'flv',
        '34'=>'flv',
        '35'=>'flv',
        '18'=>'mp4',
        '22'=>'mp4',
        '37'=>'mp4',
        '38'=>'mp4',
        '83'=>'mp4',
        '82'=>'mp4',
        '85'=>'mp4',
        '84'=>'mp4',
        '43'=>'webm',
        '44'=>'webm',
        '45'=>'webm',
        '46'=>'webm',
        '100'=>'webm',
        '101'=>'webm',
        '102'=>'webm',
        '13'=>'3gp',
        '17'=>'3gp',
        '36'=>'3gp'
    );

    public static function init($id = null){
        self::$data = self::$links = self::$info = null;
        if(self::$proxy){
            $dir = realpath(dirname(__FILE__));
            self::$proxy_list = is_file($dir.'/proxy.txt') ? file($dir.'/proxy.txt') : array();
            if(empty(self::$proxy_list)) self::$proxy = false;
            self::$proxy_attempts = sizeof(self::$proxy_list);
        }
        self::$id = $id;
    }

    /**
     * Method for processing getting information about video
     * @param bool $proxy
     * @param int $i
     * @return array|null
     */
    public static function get_info($proxy = false, $i = 0){

        if(empty(self::$id)) die('Enter video id');
        if(!empty(self::$info)) return self::$info;
        # Get video data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://www.youtube.com/get_video_info?video_id='. self::$id);
        # Use proxy
        if($proxy && self::$proxy){
            $proxy = self::$proxy_list[($i-1)];
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_PROXY, trim($proxy));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        curl_close ($ch);

        # Parsing data
        parse_str($data, $info);

        # Check the returned status and, if necessary, use a proxy
        if(@$info['status'] == 'ok') {
            self::$info = $info;
            return $info;
        }
        elseif($i<self::$proxy_attempts && self::$proxy)
            return self::get_info(true, ++$i);
        else
            return false;
    }

    /**
     * Method for getting direct links to video
     * @return array
     */
    public static function get_links(){
        if(!empty(self::$links)) return self::$links;
        if(empty(self::$info)) self::get_info();
        $links_map = explode(',',self::$info['url_encoded_fmt_stream_map']);
        $fmt_list = explode(',',self::$info['fmt_list']);
        if(empty($links_map) || (sizeof($links_map) == 1 && empty($links_map[0]))) return false;
        foreach($links_map as $key => $link){
            parse_str($link,$parts);
            $link = $parts['url'].='&signature='.$parts['sig'];
            $fmt_parts = explode('/', $fmt_list[$key],3);
            # Create array of information of video
            self::$links[self::$formats[$parts['itag']] .'-'. $fmt_parts[1]] = array(self::$formats[$parts['itag']], $fmt_parts[1], $link);
        }
        return self::$links;
    }

    /**
     * Method to save video to local path
     * @param $video - Video type
     * @param $path - Dir to save video
     * @param null|string $name - Name of video (without extension)
     */
    public static function save($video, $path, $name = null){
        if(empty(self::$links)) self::get_links();
        if(!isset(self::$links[$video])) die('Video `'. $video .'` not found');

        # Define name of video
        $name = empty($name) ? self::$info['title'] : $name;
        if($path[mb_strlen($path, 'utf-8')-1] != '/') $path .= '/';
        $url = self::$links[$video][2] . '&title='. urlencode($name);
        $ch = curl_init($url);
        # Handle for copy video
        $fo = fopen($path . $name . '.' . self::$links[$video][0], 'w');
        curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FILE, $fo);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        curl_close($ch);
        fclose($fo);
    }

    /**
     * Method for getting data about video
     * @return array
     */
    public static function get_data(){
        if(!empty(self::$data)) return self::$data;
        if(empty(self::$info)) self::get_info();
        $entry = simplexml_load_file('http://gdata.youtube.com/feeds/mobile/videos/' . self::$id);
        $media = $entry->children('http://search.yahoo.com/mrss/');
        $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
        $related = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.related']");
        $related = (string)$related[0]['href'];
        $data = array(
            'keywords' => self::$info ['keywords'],
            'title' => (string)$entry->title,
            'description' => (string)$media->group->description,
            'category' => (string)$media->group->category,
            'duration' => self::$info ['length_seconds'],
            'views' => self::$info ['view_count'],
            'rate' => self::$info ['avg_rating'],
            'thumbnails' => array(
                'big' => self::$info ['iurlmaxres'],
                'small' => self::$info ['iurlsd'],
                'default' => (string)$media->group->thumbnail[0]->attributes()->url,
                1 => (string)$media->group->thumbnail[1]->attributes()->url,
                2 => (string)$media->group->thumbnail[2]->attributes()->url,
                3 => (string)$media->group->thumbnail[3]->attributes()->url
            ),
            # Link for get related videos
            'related' => $related
        );
        self::$data = $data;
        return $data;
    }

    /**
     * Method for search video
     * @param string $query - Query string
     * @param string $order - Type of order video (relevance, published, viewCount, rating)
     * @param int $start - Number of first element
     * @param int $count - Count of return results
     * @param array $need - Needed fields to return, may be id, title, description, author, thumbnails, keywords, player
     * @return array
     */
    public static function search($query, $need = array('id', 'title', 'description',  'author', 'thumbnails', 'keywords', 'player', 'duration'), $order = 'published', $start = 1, $count = 10) {
        # Orders type
        $allowOrder = array('relevance', 'published', 'viewCount', 'rating');
        if(!in_array($order, $allowOrder)) exit('Wrong type of order');

        # Make url
        $url = 'http://gdata.youtube.com/feeds/api/videos?vq='. urlencode($query) .'&orderby='. $order .'&start-index='.
            intval($start) .'&format=1&max-results='. intval($count);

        # Get data
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        curl_close ($ch);
        if(strpos($data, '<?xml') === false) exit('Can\'t processing video data');
        $data = simplexml_load_string($data);

        # Total results
        $return = array('count' => (int)$data->children('http://a9.com/-/spec/opensearchrss/1.0/')->totalResults);

        # Parsing data
        if(sizeof($data->entry)>0){
            foreach($data->entry as $entry){
                $now = array();
                if(in_array('id', $need))
                    $now += array('id' => basename($entry->id));
                if(in_array('title', $need))
                    $now += array('title' => (string)$entry->title);
                if(in_array('description', $need))
                    $now += array('description' => (string)$entry->content);
                if(in_array('author', $need))
                    $now += array('author' => (string)$entry->author->name);
                if(in_array('thumbnails', $need) || in_array('keywords', $need) || in_array('player', $need)){
                    $media = $entry->children('http://search.yahoo.com/mrss/');
                }
                if(in_array('keywords', $need))
                    $now += array('keywords' => (string)$media->group->keywords);
                if(in_array('player', $need))
                    $now += array('player' => (string)$media->group->player->attributes()->url);
                if(in_array('thumbnails', $need))
                    $now += array('thumbnails' => array(
                        'default' => (string)$media->group->thumbnail[0]->attributes()->url,
                        1 => (string)$media->group->thumbnail[1]->attributes()->url,
                        2 => (string)$media->group->thumbnail[2]->attributes()->url,
                        3 => (string)$media->group->thumbnail[3]->attributes()->url
                    ));
                if(in_array('duration', $need))
                    $now += array('duration' => (int)$media->children('http://gdata.youtube.com/schemas/2007')
                        ->duration->attributes());
                $return[] = $now;
            }
            return $return;
        }  else return array();
    }

    /**
     * Method for getting video and output to browser (test method)
     * @param string $video - Type video for getting
     */
    public static function get($video) {
        if(empty(self::$links)) self::get_links();
        if(!isset(self::$links[$video])) die('Video `'. $video .'` not found');

        $url = self::$links[$video][2];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        $res = curl_getinfo($ch);
        curl_close($ch);
        if($res['content_type'] == 'video/webm') $ext = 'webm';
        elseif($res['content_type'] == 'video/x-flv') $ext = 'flv';
        elseif($res['content_type'] == 'video/mp4') $ext = 'mp4';
        elseif($res['content_type'] == 'video/3gpp') $ext = '3gp';
        header('Content-Type: '. $res['content_type']);
        header('Content-Length: '. $res['download_content_length']);
        header('Content-Disposition: attachment; filename='. urlencode(self::$info['title']).'.'.$ext);

        $ch = curl_init($res['url']);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_exec($ch);
        curl_close($ch);
    }
}


if (isset($_GET['id']) && isset($_GET['type']) && isset($_GET['ok']))
{
	$ID = htmlspecialchars($_GET['id']);
	$type = htmlspecialchars($_GET['type']);
	YT::init($ID);
	YT::get($type);	
	exit;
}

if (isset($_GET['id']) && isset($_GET['type']))
{
	$ID = htmlspecialchars($_GET['id']);
	$type = htmlspecialchars($_GET['type']);
	YT::init($ID);
	YT::get($type);	
	header('Location: ?id=' . $ID . '&type=' . $type . '&ok');
	exit; 
}
?>
