<?php

class Pages extends Controller {
	public function __construct(){
	}

	public function about(){
		$data = [
			'title' => 'Aboot',
			'description' => 'App to post images.'
	];
		$this->view('pages/about', $data);
	}
}