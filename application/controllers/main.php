<?php

class Main extends Controller {
	
	function index()
	{
		$tempalte = $this->loadView('main_view');
		$tempalte->render();
	}
    
}

?>
