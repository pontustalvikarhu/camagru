<?php
//require_once '../libraries/Controller.class.php';

class Pages extends Controller {
	public function __construct(){
		//echo 'Pages loaded, suckas.';
		
	}

	public function index(){
			redirect('posts');

		$data = [
			'title' => 'Camagru',
			'description' => 'Simple image-sharing site built by panderss.'
		];

		$this->view('pages/index', $data);
	}

	public function about(){
		$data = [
			'title' => 'Aboot',
			'description' => 'App to post images.'
	];
		$this->view('pages/about', $data);
	}
}