<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Youtube extends Controller {

	public function __construct(Request $request, Response $response){
		parent::__construct($request,$response);
		require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Uri_Http');
		
	}
	
	
	static function printVideoEntry($videoEntry) 
	{
	  // the videoEntry object contains many helper functions
	  // that access the underlying mediaGroup object
	  echo 'Video: ' . $videoEntry->getVideoTitle() . "\n";
	  echo 'Video ID: ' . $videoEntry->getVideoId() . "\n";
	  echo 'Updated: ' . $videoEntry->getUpdated() . "\n";
	  echo 'Description: ' . $videoEntry->getVideoDescription() . "\n";
	  echo 'Category: ' . $videoEntry->getVideoCategory() . "\n";
	  echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "\n";
	  echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "\n";
	  echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "\n";
	  echo 'Duration: ' . $videoEntry->getVideoDuration() . "\n";
	  echo 'View count: ' . $videoEntry->getVideoViewCount() . "\n";
	  echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "\n";
	  echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "\n";
	  echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "\n";
	  
	  // see the paragraph above this function for more information on the 
	  // 'mediaGroup' object. in the following code, we use the mediaGroup
	  // object directly to retrieve its 'Mobile RSTP link' child
	  foreach ($videoEntry->mediaGroup->content as $content) {
	    if ($content->type === "video/3gpp") {
	      echo 'Mobile RTSP link: ' . $content->url . "\n";
	    }
	  }
	  
	  echo "Thumbnails:\n";
	  $videoThumbnails = $videoEntry->getVideoThumbnails();
	
	  foreach($videoThumbnails as $videoThumbnail) {
	    echo $videoThumbnail['time'] . ' - <img src="' . $videoThumbnail['url'];
	    echo '" /> height=' . $videoThumbnail['height'];
	    echo ' width=' . $videoThumbnail['width'] . "\n";
	  }
	}
	public static function printVideoFeed($videoFeed, $search)
	{
	  $count = 1;
	  echo $search;
	  foreach ($videoFeed as $videoEntry) {
	    echo "Entry # " . $count . "\n";
	    Controller_Welcome::printVideoEntry($videoEntry);
	    echo "\n";
	    $count++;
	  }
	
	}

	static public function getvideos($m = array()){
		
		require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
		Zend_Loader::loadClass('Zend_Gdata_YouTube');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Uri_Http');	
		$yt = new Zend_Gdata_YouTube();
		$yt->setMajorProtocolVersion(2);
		$videos = array();
		$query = $yt->newVideoQuery();
		$query->setOrderBy('relevance');
		$query->setMaxResults(5);
		
		if(isset($m['message'])){
			$query->setVideoQuery(urlencode($m['message']));
			$videoFeed = $yt->getVideoFeed($query->getQueryUrl(2));		  
			foreach ($videoFeed as $video) {
				$thumbs = $video->getVideoThumbnails();
				$thumb = '<img src="'.$thumbs[0]['url'].'" alt="" />';
				$videos[] = array('url'=>$video->getVideoWatchPageUrl(),'title'=>$video->getVideoTitle(),'thumb'=>$thumb);
			}
		}
		return $videos;
	}
	
	public function action_download($url='', $file=''){
		//var_dump($_POST);
		$out = '';
		if(isset($_POST['url'])){
			$url = $_POST['url'];
		}
		if(isset($_POST['file'])){
			$file= '/home/jak/workspace/smsplayer/music/'.$_POST['file'];
			$file = $_POST['file'];
		}		
		if($url!=''&&$file!=''){
			//$cmd = '/home/jak/workspace/smsplayer/ytget.sh -o "'.$file.'" -i "'.$url.'" -q34 > /dev/null; echo $?';
			$cmd = '/usr/bin/youtube-dl "'.$url.'" -o "/home/jak/workspace/smsplayer/music/'.$file.'" >> /home/jak/workspace/smsplayer/application/logs/dlqueue/'.$file.' &';
			$command = shell_exec($cmd);	
			$this->response->body($command);	
		}
		else{
			$this->response->body('fail');
		}
	}
	
	public function action_encode(){
		if(isset($_POST['file'])){
			$file = $_POST['file'];
			$cmd = '/usr/bin/ffmpeg -i /home/jak/workspace/smsplayer/music/'.$file.' -acodec libmp3lame -ac 2 -ab 192 -vn -y /home/jak/workspace/smsplayer/music/'.$file.'.mp3 >> /home/jak/workspace/smsplayer/application/logs/encoding/'.$file.' &';
			$command = shell_exec($cmd);
			unlink('/home/jak/workspace/smsplayer/application/logs/dlqueue/'.$file.'');	
			$this->response->body($cmd);
		}
	}
}