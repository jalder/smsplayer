<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Mpd extends Controller {

	public function __construct(Request $request, Response $response){
		parent::__construct($request,$response);
		require_once('Net/MPD.php');
	}
	
	
	public function action_index(){
		
		
		
	}
	
	
	public function action_stop(){
		
		require_once('Net/MPD.php');
		$MPD_PLS = Net_MPD::factory('Playback');
		if (!$MPD_PLS->connect()) {
		    die('Connection failed: '.print_r($MPD_DB->getErrors(), true));
		}
		$MPD_PLS->stop();
		
		
	}
	public function action_start(){
		
		require_once('Net/MPD.php');
		$MPD_PLS = Net_MPD::factory('Playback');
		if (!$MPD_PLS->connect()) {
		    die('Connection failed: '.print_r($MPD_DB->getErrors(), true));
		}
		$MPD_PLS->play();
		
		
	}	
	
	public function action_pause(){
		require_once('Net/MPD.php');
		$MPD_PLS = Net_MPD::factory('Playback');
		if (!$MPD_PLS->connect()) {
		    die('Connection failed: '.print_r($MPD_DB->getErrors(), true));
		}
		$MPD_PLS->pause();		
	}
	
	public function action_getplaylist(){
		$playlist = $this->getplaylist();
		$content = '';
		foreach($playlist['file'] as $song) : 
			if(isset($song['Artist'])&&isset($song['Title'])){
		 		$content .= '<li>'.$song['Artist'].' - '.$song['Title'].'</li>';
			}
			else{
				$content .= '<li>'.$song['file'].'</li>';
			}
		endforeach;
		$this->response->body($content);
	}
	public function getplaylist(){
		
		require_once('Net/MPD.php');
		$MPD_PLS = Net_MPD::factory('Playlist');
		if (!$MPD_PLS->connect()) {
		    die('Connection failed: '.print_r($MPD_DB->getErrors(), true));
		}
		$list = $MPD_PLS->getPlaylistInfoId();
		if(!$list){
			$list = array();
		}		
		return $list;		
	}
	public function getdatabase(){
		
		require_once('Net/MPD.php');

		$MPD_PLS = Net_MPD::factory('Database');
		if (!$MPD_PLS->connect()) {
		    die('Connection failed: '.print_r($MPD_DB->getErrors(), true));
		}
		$list = $MPD_PLS->getAll();
		if(!$list){
			$list = array();
		}		
		return $list;		
	}
	
	public function action_getcommands(){
		$refresh = Net_MPD::factory('Common');
		if (!$refresh->connect()) {
		    die('Connection failed: '.print_r($refresh->getErrors(), true));
		}
		$cmds = $refresh->getCommands();
		var_dump($cmds);	
		
	}
	
	public function updatedatabase(){
		$refresh = Net_MPD::factory('Admin');
		if (!$refresh->connect()) {
		    die('Connection failed: '.print_r($refresh->getErrors(), true));
		}
		$refresh->updateDatabase('');			
		
	}
	
	public function action_refreshdb(){
		$db = $this->updatedatabase();
		
	}
	
	public function action_addsong(){
		if(isset($_POST['song'])){
			$file = $_POST['song'];
			$addsong = Net_MPD::factory('Playlist');
			return $addsong->addSong($file);
		}
		
	}
	
	public function action_next(){
		$next = Net_MPD::factory('Playback');
		return $next->nextSong();
	}
	
	public function action_playId(){
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$playId = Net_MPD::factory('Playback');
			return $playId->playId($id);
		}
	}
	
	public function action_previous(){
		$prev = Net_MPD::factory('Playback');
		return $prev->previousSong();
	}
	
}