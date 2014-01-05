<?php

class Add extends Controller {
	
	function index()
	{
		if (!isset($_POST['url']) || $_POST['url'] == '') {
			//We didn't get anything
			echo json_encode(array(
				'error' => true,
				'message' => "You've sent us a blank URL."
			));
		} else {
			//we have a url... we should clean it
			$url = trim(urldecode($_POST['url']));
			
			//check to see if we're referencing ourself
			global $config;
			if (strpos($url, $config['url']) !== false) {
				echo json_encode(array(
					'error' => true,
					'message' => "Don't link back to us... poser."
				));
				return;
			}
			
			//add a new hash form our URL
			$link = $this->loadModel('Link');
			if (!$link->fromURL($url)){
				//so we had an error making our hash?
				echo json_encode(array(
					'error' => true,
					'message' => "There was an error shortening your link."
				));
			} else {
				//no errors, let's send the user back our hash
				echo json_encode(array(
					'hash' => $link->getHash()
				));
			}
		}
	}
	
	function process($arg)
	{
		echo json_encode(array(
			'error' => true,
			'message' => "You've sent us a blank URL."
		));
		$this->redirect('');
	}
}

?>