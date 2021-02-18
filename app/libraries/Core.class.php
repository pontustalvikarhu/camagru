<?php
// Core class, creates url and loads core controller.
// URL format: /controller/method/parameters

class CORE {
	protected $currentController = 'Pages';
	protected $currentMethod = 'Index';
	protected $params = [];

	public function __construct(){
		//$this->getUrl();
		//print_r($this->getUrl());
		$url = $this->getUrl();

		// Check controllers for first value in url array.
		if (file_exists('../app/controllers/'.ucwords($url[0]).'.class.php')){
			// Sets controller.
			$this->currentController = ucwords($url[0]);
			// Unset zero index.
			unset($url[0]);
		}
		require_once '../app/controllers/'.$this->currentController.'.class.php';
		// Instantiate controller
		$this->currentController = new $this->currentController;
		// Check url[1].
		if (isset($url[1])){
			if (method_exists($this->currentController, $url[1])){
				$this->currentMethod = $url[1];
				unset($url[1]);
			}
		}

		// Get parameters.
		$this->params = $url ? array_values($url) : [];

		// Callback with array of parameters.
		call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
	}

	public function getUrl(){
		if (isset($_GET['url'])){
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			return ($url);
			//echo $_GET['url'];
		}
	}
}

?>