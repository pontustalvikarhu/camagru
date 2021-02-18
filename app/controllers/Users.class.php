<?php
class Users extends Controller {
	public function __construct(){
		$this->userModel =  $this->model('User');
	}

	public function register(){
		// Check for POST
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'name' => trim($_POST['name']),
				'username' => trim($_POST['username']),
				'email' => trim($_POST['email']),
				'password' => trim($_POST['password']),
				'confirm_password' => trim($_POST['confirm_password']),
				'name_err' => '',
				'username_err' => '',
				'email_err' => '',
				'password_err' => '',
				'confirm_password_err' => ''
			];

			// Validate e-mail
			if (empty($data['email'])){
				$data['email_err'] = 'Please enter e-mail.';
			} else {
				// Check email
				if ($this->userModel->findUserByEmail($data['email'])){
					$data['email_err'] = 'Email is already registered.';
				}
			}
			// Validate name
			if (empty($data['name'])){
				$data['name_err'] = 'Please enter name.';
			}
			if (empty($data['username'])){
				$data['username_err'] = 'Please enter username.';
			} else {
				// Check username
				if ($this->userModel->findUserByUsername($data['username'])){
					$data['username_err'] = 'Username is already taken, please choose another.';
				}
			}
			// Validate password
			if (empty($data['password'])){
				$data['password_err'] = 'Please enter a password.';
			}
			elseif (strlen($data['password']) < 6) {
				$data['password_err'] = 'Password must be at least six characters.';
			}
			// Confirm password
			if (empty($data['confirm_password'])){
				$data['confirm_password_err'] = 'Please confirm password.';
			}
			else{
				if ($data['confirm_password'] !== $data['password']) {
					$data['confirm_password_err'] = 'Password does not match.';
				}
			}

			// Check if errors are empty
			if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && 
			empty($data['confirm_password_err'])){
				// Validated.
				
				// Hash password
				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

				// Call model to register user
				if($this->userModel->register($data)){
					flash('register_success', 'You are now registered and can log in.');
					$to = $data['email'];
					$subject = "Account verification";

					$message = "
					<html>
					<head>
					<title>Thank you for signing up!</title>
					</head>
					<body>
					<p>This e-mail contains your e-mail verification link. Please go to </p>
					<a href=\"\#\">this link</a>
					<p> to verify your account.</p>
					</body>
					</html>
					";

					// Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					// More headers
					$headers .= 'From: <info@camagru.com>' . "\r\n";
					//$headers .= 'Cc: myboss@example.com' . "\r\n";

					mail($to,$subject,$message,$headers);
					redirect('users/login');
				} else {
					die('Something went wrong.');
				}
			} else {
				// Load view containing errors.
				$this->view('users/register', $data);
			}

		}
		else {
			// Init data
			$data = [
				'name' => '',
				'username' => '',
				'email' => '',
				'password' => '',
				'confirm_password' => '',
				'name_err' => '',
				'username_err' => '',
				'email_err' => '',
				'password_err' => '',
				'confirm_password_err' => ''
			];

			$this->view('users/register', $data);
		}
	}
	public function login(){
		// Check for POST
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'username' => trim($_POST['username']),
				'password' => trim($_POST['password']),
				'username_err' => '',
				'password_err' => '',
			];
			// Validate e-mail
			if (empty($data['username'])){
				$data['username_err'] = 'Please enter username.';
			}
			// Validate password
			if (empty($data['password'])){
				$data['password_err'] = 'Please enter password.';
			}

			// Check for user/email
			if ($this->userModel->findUserByUsername($data['username'])){
				// User found
			} else {
				$data['username_err'] = 'No user with that username found.';
			}
			// Check if errors are empty
			if (empty($data['username_err']) && empty($data['password_err'])){
				// Validated.
				// Check and set logged-in user.
				$loggedInUser = $this->userModel->login($data['username'], $data['password']);
				if ($loggedInUser){
					// Create session
					$this->createUserSession($loggedInUser);
				} else {
					$data['password_err'] = 'Incorrect password.';
					$this->view('users/login', $data);
				}
			} else {
				// Load view containing errors.
				$this->view('users/login', $data);
			}
		}
		else {
			// Init data
			$data = [
				'username' => '',
				'password' => '',
				'username_err' => '',
				'password_err' => '',
			];

			$this->view('users/login', $data);
		}
	}
	public function createUserSession($user){
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_username'] = $user->username;
		$_SESSION['user_name'] = $user->name;
		redirect('posts');
	}

	public function logout(){
		unset($_SESSION['user_id']);
		unset($_SESSION['user_username']);
		unset($_SESSION['user_name']);
		session_destroy();
		redirect('users/login');

	}

	
}
?>