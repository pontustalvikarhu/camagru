<?php
class Posts extends Controller {
	public function __construct(){
		// Put only in what is meant to be protected; don't use for camagru.
		if(!isLoggedIn()){
			//redirect('users/login');
			$_SESSION['user_id'] = 'guest';
		}
		$this->postModel = $this->model('Post');
		$this->userModel = $this->model('User');
	}
	public function index(){
		// Get posts and displays in view.
		$page = 1;
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			if (isset($_GET['page'])){
				$page = intval($_GET['page']);
			}
			//print_r($_GET);
		}
		else {
			$page = 1;
		}
		$count = $this->postModel->getPostsCount();
		$posts = $this->postModel->getPosts($page);
		$comments = $this->postModel->getComments();
		$likes = $this->postModel->getLikes();
		//print_r($count[0]);
		$array = get_object_vars($count[0]);
		//print_r($array['COUNT(*)']);
		$count = $array['COUNT(*)'];
		$data = [
			'count' => ceil($count / 10),
			'current_page' => $page,
			'posts' => $posts,
			'comments' => $comments,
			'likes' => $likes
		];
		
		//print_r($data['count'][0]);
		$this->view('posts/index', $data);
	}

	public function add(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'title' => trim($_POST['title']),
				'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				'image' => trim($_POST['image']),
				'title_err' => '',
				'body_err' => '',
				'image_err' => ''
			];
			// Validate title
			if (empty($data['title'])){
				$data['title_err'] = 'Please enter a title.';
			}
			// Validate body
			if (empty($data['body'])){
				$data['body_err'] = 'Please enter body text.';
			}
			if (empty($data['image'])){
				$data['image_err'] = 'Please take photo or upload an image.';
			}
			// Check for errors
			if (empty($data['title_err']) && empty($data['body_err']) && empty($data['image_err'])){
				// Validated
				if ($this->postModel->addPost($data)){
					flash('post_message', 'Post added!');
					redirect('posts');
				} else {
					die('Sh*t, something went wrong.');
				}
			} else {
				// Load view with errors.
				$this->view('posts/add', $data);
			}
		} else {
			$data = [
				'title' => '',
				'body' => ''
			];
			$this->view('posts/add', $data);
		}
		
	}

	// Comment post
	public function comment($id){
		if ($_SESSION['user_id'] == 'guest'){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$post = $this->postModel->getPostById($id);
			//print_r($post);
			$data = [
				'post' => $post->id,
				'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				'body_err' => ''
			];
			// Validate body
			if (empty($data['body'])){
				$data['body_err'] = 'Please enter comment text.';
			}
			// Check for errors
			if (empty($data['body_err'])){
				// Validated
				if ($this->postModel->addComment($data)){
					flash('post_message', 'Comment added!');
					redirect('posts');
				} else {
					die('Sh*t, something went wrong.');
				}
			} else {
				// Load view with errors.
				$this->view('posts/comment', $data);
			}
		} else {
			$post = $this->postModel->getPostById($id);
			echo $post->id;
			$data = [
				'post' => $post->id,
				'title' => '',
				'body' => ''
			];
			$this->view('posts/comment', $data);
		}
		
	}

	// liking posts.
	public function like($id){
		if ($_SESSION['user_id'] == 'guest'){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$post = $this->postModel->getPostById($id);
			//print_r($post);
			$data = [
				'post' => $post->id,
				//'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				//'body_err' => ''
			];
			// Validate body
			/*if (empty($data['body'])){
				$data['body_err'] = 'Please enter comment text.';
			}*/
			// Check for errors
			// Validated
			if ($this->postModel->addLike($data)){
				flash('post_message', 'Post liked!');
				redirect('posts');
			} else {
				flash('post_message', 'Sh*t, something went wrong.');
				die('Sh*t, something went wrong.');
			}
		} else {
			redirect('posts');
		}
		
	}

	// unliking posts.
	public function unlike($id){
		if ($_SESSION['user_id'] == 'guest'){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$post = $this->postModel->getPostById($id);
			//print_r($post);
			$data = [
				'post' => $post->id,
				//'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				//'body_err' => ''
			];
			// Validate body
			/*if (empty($data['body'])){
				$data['body_err'] = 'Please enter comment text.';
			}*/
			// Check for errors
			// Validated
			if ($this->postModel->removeLike($data)){
				flash('post_message', 'Post unliked!');
				redirect('posts');
			} else {
				flash('post_message', 'Sh*t, something went wrong.');
				die('Sh*t, something went wrong.');
			}
		} else {
			redirect('posts');
		}
		
	}

	public function show($id){
		$post = $this->postModel->getPostById($id);
		$user = $this->userModel->getUserById($post->user_id);
		$data = [
			'post' => $post,
			'user' => $user
		];
		$this->view('posts/show', $data);
	}

	public function edit($id){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'id' => $id,
				'title' => trim($_POST['title']),
				'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'], // Not strictly necessary.
				'title_err' => '',
				'body_err' => ''
			];
			// Validate title
			if (empty($data['title'])){
				$data['title_err'] = 'Please enter a title.';
			}
			// Validate body
			if (empty($data['body'])){
				$data['body_err'] = 'Please enter body text.';
			}
			// Check for errors
			if (empty($data['title_err']) && empty($data['body_err'])){
				// Validated
				if ($this->postModel->updatePost($data)){
					flash('post_message', 'Post updated!');
					redirect('posts');
				} else {
					die('Sh*t, something went wrong.');
				}
			} else {
				// Load view with errors.
				$this->view('posts/edit', $data);
			}
		} else {
			// Fetch existing post.
			$post = $this->postModel->getPostById($id);
			// Check for owner of post
			if ($post->user_id != $_SESSION['user_id']){
				redirect('posts');
			}
			$data = [
				'id' => $id,
				'title' => $post->title,
				'body' => $post->body
			];
			$this->view('posts/edit', $data);
		}
		
	}

	public function delete($id){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Fetch existing post.
			$post = $this->postModel->getPostById($id);
			// Check for owner of post
			if ($post->user_id != $_SESSION['user_id']){
				redirect('posts');
			}
			if($this->postModel->deletePost($id)){
				flash('post_message', 'Post deleted!');
				redirect('posts');
			} else {
				die('F*ck, something went wrong.');
			}
		} else {
			redirect('posts');
		}
	}
}