<?php
class Posts extends Controller {
	public function __construct(){
		if(!isLoggedIn()){
			$_SESSION['user_id'] = 'guest';
		}
		$this->postModel = $this->model('Post');
		$this->userModel = $this->model('User');
	}
	
	// Get posts and displays in view.
	public function index(){
		$page = 1;
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			if (isset($_GET['page'])){
				$page = intval($_GET['page']);
			}
		}
		else {
			$page = 1;
		}
		$count = $this->postModel->getPostsCount();
		$posts = $this->postModel->getPosts($page);
		$comments = $this->postModel->getComments();
		$likes = $this->postModel->getLikes();
		$array = get_object_vars($count[0]);
		$count = $array['COUNT(*)'];
		$data = [
			'count' => ceil($count / 5),
			'current_page' => $page,
			'posts' => $posts,
			'comments' => $comments,
			'likes' => $likes
		];
		$this->view('posts/index', $data);
	}

	private function make_xy_arr($arr){
		$i = 0;
		$ret = [];
		$xy = [];
		foreach($arr as $coord){
			if ($i % 2 === 0){
				array_push($xy, $coord);
			}
			else{
				array_push($xy, $coord);
				array_push($ret, $xy);
				$xy = [];
			}
			++$i;
		}
		return ($ret);
	}

	private function superimpose($src, $sticker_arr, $coord_arr)
{
	$coord_i = 0;
	$base_img = imagecreatefrompng($src);
	foreach ($sticker_arr as $sticker){
		$stamp = imagecreatefrompng($sticker);
		list($width, $height) = getimagesize($src);
		list($width_small, $height_small) = getimagesize($sticker);
		imagealphablending($base_img, true);
		imagesavealpha($base_img, true);
		imagecopy($base_img, $stamp,  $coord_arr[$coord_i][0], $coord_arr[$coord_i][1], 0, 0, $width_small, $height_small);
		$coord_i += 1;
	}

	ob_start();
	imagepng($base_img);
	$image_data = ob_get_contents();
	ob_end_clean();
	return ("data:image/png;base64,".base64_encode($image_data));
}


	public function add(){
		if(!isLoggedIn()){
			redirect('users/login');
			$_SESSION['user_id'] = 'guest';
		}else{
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				// Sanitize POST array
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'title' => trim($_POST['title']),
					'body' => trim($_POST['body']),
					'user_id' => $_SESSION['user_id'],
					'token' => trim($_POST['csrf-token']),
					'title_err' => '',
					'body_err' => '',
					'image_err' => '',
					'stickers' => explode(",", $_POST['stickers']),
					'sticker-pos' => $this->make_xy_arr(explode(",", $_POST['sticker-pos'])),
				];
				if (!empty($data['stickers'][0])){
					$data['image'] = $this->superimpose(trim($_POST['image']), $data['stickers'], $data['sticker-pos']);
				}
				else{
					$data['image'] = trim($_POST['image']);
				}
				// Validate title
				if (empty($data['title'])){
					$data['title_err'] = 'Please enter a title.';
				}
				// Validate body
				if (empty($data['body'])){
					$data['body_err'] = 'Please enter body text.';
				}
				if (empty($data['image'])){
					$data['image_err'] = 'Please take photo or upload an image. Remember to click Save Photo!';
				}
				else if (substr($data['image'], 0, 21) !== 'data:image/png;base64'){
					$data['image_err'] = 'Not a base64 image.';
				}
				if (!$this->userModel->hasValidToken($data)){
					echo "Oh, no, you don't!";
					die();
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
				'body' => '',
				'title_err' => '',
				'body_err' => '',
				'image_err' => '',
			];
			$this->view('posts/add', $data);
		}
	}
		
	}

	// Comment post
	public function comment($id){
		if ($_SESSION['user_id'] === 'guest'){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// Sanitize POST array
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$post = $this->postModel->getPostById($id);
			$user = $this->userModel->getUserById($post->user_id);
			$comments = $this->postModel->getCommentsByPostId($id);
			$likes = $this->postModel->getLikes();
			$data = [
				'post' => $post->id,
				'body' => trim($_POST['body']),
				'user_id' => $_SESSION['user_id'],
				'token' => trim($_POST['csrf-token']),
				'body_err' => '',
				'postData' => $post,
				'userData' => $user,
				'comments' => $comments,
				'likes' => $likes
			];
			// Check token.
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			// Validate body
			if (empty($data['body'])){
				$data['body_err'] = 'Please enter comment text.';
			}
			// Check for errors
			if (empty($data['body_err'])){
				// Validated
				if ($this->postModel->addComment($data)){
					flash('post_message', 'Comment added!');
					if ($this->userModel->checkNotificationOnComment($post->user_id)){
						$message = "
						<html>
						<head>
						<title>Your post '".$post->title."' has received a new comment!</title>
						</head>
						<body>
						<p>Your post '".$post->title."' has received a new comment.</p>
						<p>XOXO from the Camagru team</p>
						<p>PS. You can turn off these notifications in your Settings.</p>
						</body>
						</html>
						";
						$toUser = $this->userModel->getUserById($post->user_id);
						emailAlert($toUser->email, 'Your post has received a new comment!', $message);
					}
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
			$user = $this->userModel->getUserById($post->user_id);
			$comments = $this->postModel->getCommentsByPostId($id);
			$likes = $this->postModel->getLikes();
			$data = [
				'post' => $post->id,
				'body' => '',
				'user_id' => $_SESSION['user_id'],
				'body_err' => '',
				'postData' => $post,
				'userData' => $user,
				'comments' => $comments,
				'likes' => $likes
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
			$data = [
				'post' => $post->id,
				'user_id' => $_SESSION['user_id'],
				'token' => trim($_POST['csrf-token'])
			];
			// Check token.
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
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
				'user_id' => $_SESSION['user_id'],
				'token' => trim($_POST['csrf-token'])
			];
			// Check token.
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
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
		$likes = $this->postModel->getLikes();
		$comments = $this->postModel->getCommentsByPostId($id);;
		$data = [
			'post' => $post,
			'user' => $user,
			'likes' => $likes,
			'comments' => $comments
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
				'user_id' => $_SESSION['user_id'],
				'token' => trim($_POST['csrf-token']),
				'title_err' => '',
				'body_err' => ''
			];
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
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
			if(!isLoggedIn()){
				redirect('users/login');
			}else{
				// Fetch existing post.
				$post = $this->postModel->getPostById($id);
				// Check for owner of post
				if ($post->user_id != $_SESSION['user_id']){
					redirect('posts');
				}
				$data = [
					'id' => $id,
					'title' => $post->title,
					'body' => $post->body,
					'title_err' => '',
					'body_err' => ''
				];
				$this->view('posts/edit', $data);
			}
		}	
	}

	public function delete($id){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$data = [
				'token' => trim($_POST['csrf-token']),
				'user_id' => trim($_SESSION['user_id'])
			];
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			// Fetch existing post.
			$post = $this->postModel->getPostById($id);
			// Check for owner of post
			if ($post->user_id !== $_SESSION['user_id']){
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