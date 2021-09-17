<?php
/*
* Base controller
* Loads models and views.
*/
class Controller {
	public function model($model){
		require_once 'models/'.$model.'.class.php';
		return new $model();
	}

	public function view($view, $data = []){
		if (file_exists('views/'.$view.'.php')){
			require_once 'views/'.$view.'.php';
		}
		else {
			die('View does not exist.');
		}
	}
}