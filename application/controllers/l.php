<?php

class L extends Controller {
	
	function index()
	{
		$this->redirect('');
	}
	
	function process($hash)
	{
		//cleaningggg
		$hash = trim(urlencode($hash));
		
		//load our object template
		$link = $this->loadModel('Link');
		
		//wow this is easy
		if (!$link->fromHash($hash)){
			//that hash doesn't exist
			//$this->redirect('');
			var_dump($hash);
		} else {
			//the hash does exist!
			//better redirect
			$url = $link->getURL();
			header('Location: '.$url);
		}
		
	}
}

?>