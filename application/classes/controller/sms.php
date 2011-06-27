<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sms extends Controller {
	
	
	static public function fetchnew(){
		require_once(MODPATH.'Google-Voice-PHP-API/GoogleVoice.php');
		$gv = new GoogleVoice(Kohana::config('GoogleVoice.user'),Kohana::config('GoogleVoice.pass'));

		$sms = $gv->getNewSMS();
		$messages = array();
		$msgIDs = array();
		foreach( $sms as $s ){
	        preg_match('/\+1([0-9]{3})([0-9]{3})([0-9]{4})/', $s['phoneNumber'], $match);
	        $phoneNumber = '(' . $match[1] . ') ' . $match[2] . '-'. $match[3];
	        //echo 'Message from: ' . $phoneNumber . ' on ' . $s['date'] . ': ' . $s['message'] . "\n";
			$message = $s['message'];
			
			$messages[]= array('number'=>$match[0],'message'=>$message);
			
	        if( !in_array($s['msgID'], $msgIDs) )
	        {
	                // mark the conversation as "read" in google voice
	                //$gv->markSMSRead($s['msgID']);
	                $msgIDs[] = $s['msgID'];
	        }
		}
		
		return $messages;
		
	}
	
	static public function markread($mid = ''){
		
		require_once(MODPATH.'Google-Voice-PHP-API/GoogleVoice.php');
		$gv = new GoogleVoice(Kohana::config('GoogleVoice.user'),Kohana::config('GoogleVoice.pass'));		
		if($mid != ''){
			// mark the conversation as "read" in google voice
			$gv->markSMSRead($mid);
			return true;
		}
		
		return false;		
		
		
	}
	
	public function action_fetchnew(){
		
		$messages = $this->fetchnew();
		foreach($messages as &$m){
			$results = Controller_Youtube::getvideos($m);
			$m['youtube'] = array();
			foreach($results as $r){
				$m['youtube'][] = array('url'=>$r['url'], 'title'=>$r['title'], 'thumb'=>$r['thumb']);
			}
		}
		
		$view = View::factory('sms/requests');
		$view->requests = $messages;
		
		
		$this->response->body($view->render());		
		
		
	}
	
}