<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Mpd {
	
	public function action_index(){
		
		$page = View::factory('pages/index');
		
		$page->encoding = $page->database = $page->requests = $page->controls = $page->playlist = '';

		$page->playlist = $this->getplaylist();

		
		$page->controls[] = '<a href="#" class="ajax" name="start">Start</a>';
		$page->controls[] = '<a href="#" class="ajax" name="pause">Pause</a>';
		$page->controls[] = '<a href="#" class="ajax" name="stop">Stop</a>';
		$page->controls[] = '<a href="#" class="ajax" name="next">Next</a>';
		$page->controls[] = '<a href="#" class="ajax" name="previous">Previous</a>';

		$sms = Controller_Sms::fetchnew();
		
		foreach($sms as &$m){
			$results = Controller_Youtube::getvideos($m);
			$m['youtube'] = array();
			foreach($results as $r){
				$m['youtube'][] = array('url'=>$r['url'], 'title'=>$r['title'], 'thumb'=>$r['thumb']);
			}
		}

		$page->requests = $sms;
		
		
		$page->dlqueue = $this->getdownloadqueue();
		
		$page->encoding = $this->getencodingqueue();
		
		$page->database = $this->getdatabase();
		
		
		$this->response->body($page->render());
		
	}
	
	public function getdownloadqueue(){
		
		$iterator = new DirectoryIterator('/home/jak/workspace/smsplayer/application/logs/dlqueue/');
		$files = array();
		foreach($iterator as $i){
			if($i->isFile()){
				$files[] = array('file'=>$i->getFilename(),'status'=>$this->__lastline($i->getPathName())); 
			}
		}
		return $files;
	}
	
	public function action_getdlqueue(){
		$files = $this->getdownloadqueue();
		$view = View::factory('download/queue');
		$view->dlqueue = $files;
		$this->response->body($view->render());
		
	}

	public function getencodingqueue(){
		
		$iterator = new DirectoryIterator('/home/jak/workspace/smsplayer/application/logs/encoding/');
		$files = array();
		foreach($iterator as $i){
			if($i->isFile()){
				$files[] = array('file'=>$i->getFilename(),'status'=>$this->__lastline($i->getPathName())); 
			}
		}
		return $files;
	}
	
	public function __lastline($file = ''){
		if($file!=''){
		
			$line = '';
	
			$f = fopen($file, 'r');
			$cursor = -1;
			
			fseek($f, $cursor, SEEK_END);
			$char = fgetc($f);
			
			/**
			 * Trim trailing newline chars of the file
			 */
			while ($char === "\n" || $char === "\r") {
			    fseek($f, $cursor--, SEEK_END);
			    $char = fgetc($f);
			}
			
			/**
			 * Read until the start of file or first newline char
			 */
			while ($char !== false && $char !== "\n" && $char !== "\r") {
			    /**
			     * Prepend the new char
			     */
			    $line = $char . $line;
			    fseek($f, $cursor--, SEEK_END);
			    $char = fgetc($f);
			}
			
			return $line;
		}
		
		return false;
		
	}
	
}