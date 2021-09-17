<?php
class Users extends Controller {
	public function __construct(){
		$this->postModel = $this->model('Post');
		$this->userModel =  $this->model('User');
	}
	public function index(){
		$data = [
			// stuff
		];
		$this->view('posts/index', $data);
	}


	public function register(){
		if(isLoggedIn()){
			redirect('posts');
		}
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
			elseif (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?]/', $data['password']) || 
				!preg_match('/[0123456789]/', $data['password']) ||
				!preg_match('/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/', $data['password'])){
				$data['password_err'] = 'Password must contain least one special character, one uppercase letter, and one digit.';
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
			if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['username_err']) && 
			empty($data['confirm_password_err'])){
				// Validated.
				
				// Hash password
				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

				// Call model to register user
				if($this->userModel->register($data)){
					flash('register_success', 'You are now registered and can log in after verifying your account.');
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
		if(isLoggedIn()){
			redirect('posts');
		}
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
				'verification_err' => ''
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
			// Check verification status
			if ($this->userModel->findUserVerificationByUsername($data['username'])){
				// User has verified account.
			} else {
				$data['verification_err'] = 'Please verify your account with the link originally e-mailed to you (check your spam folder).';
			}
			// Check if errors are empty
			if (empty($data['username_err']) && empty($data['password_err']) && empty($data['verification_err'])){
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
				'verification_err' => ''
			];

			$this->view('users/login', $data);
		}
	}
	
	public function verify(){
		if(isLoggedIn()){
			redirect('posts');
		}
		// Check for GET
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			// Process GET
			// Validate e-mail
			if (empty($_GET['email'])){
				// Email error.
				echo "No e-mail.";
				die();
			}
			// Check if errors are empty
			if (empty($_GET['token'])){
				// Token error.
				echo "No token.";
				die();
			}
			if ($this->userModel->verify( $_GET['token'], $_GET['email'])){
				// User verified.
				flash('verify_success', 'You are now verified and can log in.');
				redirect('users/login');
			} else {
				print_r($_GET);
				die();
			}
		}
	}

	public function passwordReset(){
		if(isLoggedIn()){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			if ($userData = $this->userModel->getUserByResetToken($_POST['reset-token']))
			{
				//print_r($userData);
				$data = [
					'new_pass' => $_POST['new_pass'],
					'new_pass_confirm' => $_POST['new_pass_confirm'],
					'new_pass_err' => '',
					'new_pass_confirm_err' => '',
					'token' => $_POST['reset-token'],
					'user_id' => $userData->id
				];
				// Validate new password
				if (empty($data['new_pass'])){
					$data['new_pass_err'] = 'Please enter new password.';
				}
				elseif (strlen($data['new_pass']) < 6) {
					$data['new_pass_err'] = 'Password must be at least six characters.';
				}
				elseif (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?]/', $data['new_pass']) || 
					!preg_match('/[0123456789]/', $data['new_pass']) ||
					!preg_match('/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/', $data['new_pass'])){
					$data['new_pass_err'] = 'Password must contain least one special character, one uppercase letter, and one digit.';
				}
				// Validate new password confirm
				if (empty($data['new_pass_confirm'])){
					$data['new_pass_confirm_err'] = 'Please enter new password.';
				}else{
					if ($data['new_pass_confirm'] !== $data['new_pass']) {
						$data['new_pass_confirm_err'] = 'Password does not match.';
					}
				}
				// Check if errors are empty
				if (empty($data['new_pass_err']) && empty($data['new_pass_confirm_err'])){
					// Validated.
					// Update password.
					$data['new_pass'] = password_hash($data['new_pass'], PASSWORD_DEFAULT);
					if ($this->userModel->updatePassword($data)){
						// Delete token.
						$this->userModel->destroyResetToken($data['token']);
						redirect('posts');
					} else {
						echo "Shit, something went wrong.";
						die();
					}
				} else {
					// Load view containing errors.
					$this->view('users/passwordReset', $data);
				}
			} else {
				redirect('posts');
			}
		}
		else {
			// Init data
			$data = [
				'new_pass' => '',
				'new_pass_confirm' => '',
				'new_pass_err' => '',
				'new_pass_confirm_err' => '',
				'token' => $_GET['reset-token']
			];
			$this->view('users/passwordReset', $data);
		}
	}

	public function passwordResetRequest(){
		if(isLoggedIn()){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'email' => $_POST['email']
			];
			// Validate email.
			if (empty($data['email'])){
				redirect('posts');
			}
			// If e-mail is not empty, try to send reset request.
			else {
				if ($this->userModel->findUserByEmail($data['email'])){
					$this->userModel->sendResetPasswordRequest($data);
				}
				redirect('posts');
			}
		}
		else {
			// Init data
			$data = [
				'email' => ''
			];
			$this->view('users/passwordResetRequest', $data);
		}
	}

	public function settingsNotifications(){
		if(!isLoggedIn()){
			redirect('post');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			if (isset($_POST['comment_notification'])){
				$notify_setting = 1;
			}else{
				$notify_setting = 0;
			}
			$data = [
				'comment_notify' => $notify_setting,
				'user_id' => $_SESSION['user_id'],
				'token' => trim($_POST['csrf-token']),
			];
			print_r($data);
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			if ($this->userModel->updateNotifications($data)){
				flash('post_message', 'Notification settings updated!');
				redirect('posts');
			}
		}
		else {
			// Init data
			$userData = $this->userModel->getUserById($_SESSION['user_id']);
			$data = [
				'notification_setting' => $userData->notify_on_comment,
				'new_username' => $userData->username,
				'new_username_confirm' => $userData->username,
				'current_password' => '',
				'new_password' => '',
				'new_password_confirm' => '',
				'current_email' => $userData->email,
				'new_email' => '',
				'new_email_confirm' => '',
				'username_err' => '',
				'password_err' => '',
			];
			$this->view('users/settingsNotifications', $data);
		}
	}

	public function settingsUsername(){
		if(!isLoggedIn()){
			redirect('post');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'current_username' => $_POST['current_username'],
				'new_username' => $_POST['new_username'],
				'new_username_confirm' => $_POST['new_username_confirm'],
				'current_username_err' => '',
				'new_username_err' => '',
				'new_username_confirm_err' => '',
				'token' => $_POST['csrf-token'],
				'user_id' => $_SESSION['user_id'],
			];
			$userData = $this->userModel->getUserById($_SESSION['user_id']);
			// Validate current username
			if (empty($data['current_username'])){
				$data['current_username_err'] = 'Please enter username.';
			} else {
				if ($data['current_username'] !== $userData->username){
					$data['current_username_err'] = 'Username does not match current username.';
				}
			}
			// Validate new username
			if (empty($data['new_username'])){
				$data['new_username_err'] = 'Please enter desired username.';
			}
			// Validate new username confirm
			if (empty($data['new_username_confirm'])){
				$data['new_username_confirm_err'] = 'Please enter desired username.';
			}else{
				if ($data['new_username_confirm'] !== $data['new_username']) {
					$data['new_username_confirm_err'] = 'Username does not match.';
				}
			}
			// Check for user
			if ($userData->id !== $_SESSION['user_id']){
				//print_r($userData);
				echo "Oh, no, you can't modify someone else's username!";
				die();
			}
			// Check new username is not taken
			if ($this->userModel->findUserByUsername($data['new_username'])){
				// User found
				$data['new_username_err'] = 'Username is already taken; please choose another username.';
			}
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			// Check if errors are empty
			if (empty($data['current_username_err']) && empty($data['new_username_err']) && empty($data['new_username_confirm_err'])){
				// Validated.
				// Update username.
				if ($this->userModel->updateUsername($data)){
					$_SESSION['user_username'] = $data['new_username'];
					flash('post_message', 'Username updated!');
					redirect('posts');
			}
			} else {
				// Load view containing errors.
				$this->view('users/settingsUsername', $data);
			}
		}
		else {
			// Init data
			$userData = $this->userModel->getUserById($_SESSION['user_id']);
			$data = [
				'current_username' => $userData->username,
				'new_username' => '',
				'new_username_confirm' => '',
				'current_username_err' => '',
				'new_username_err' => '',
				'new_username_confirm_err' => '',
			];
			$this->view('users/settingsUsername', $data);
		}
	}

	public function settingsPassword(){
		if(!isLoggedIn()){
			redirect('posts');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'current_pass' => $_POST['current_pass'],
				'new_pass' => $_POST['new_pass'],
				'new_pass_confirm' => $_POST['new_pass_confirm'],
				'current_pass_err' => '',
				'new_pass_err' => '',
				'new_pass_confirm_err' => '',
				'token' => $_POST['csrf-token'],
				'user_id' => $_SESSION['user_id'],
			];
			// Validate current e-mail address
			if (empty($data['current_pass'])){
				$data['current_pass_err'] = 'Please enter current password.';
			} else {
				if (!$this->userModel->checkPassword($_SESSION['user_id'], $data['current_pass'])){
					$data['current_pass_err'] = 'Incorrect password.';
				}
			}
			// Validate new email
			if (empty($data['new_pass'])){
				$data['new_pass_err'] = 'Please enter new password.';
			}
			elseif (strlen($data['new_pass']) < 6) {
				$data['new_pass_err'] = 'Password must be at least six characters.';
			}
			elseif (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?]/', $data['new_pass']) || 
					!preg_match('/[0123456789]/', $data['new_pass']) ||
					!preg_match('/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/', $data['new_pass'])){
					$data['new_pass_err'] = 'Password must contain least one special character, one uppercase letter, and one digit.';
				}
			// Validate e-mail confirm
			if (empty($data['new_pass_confirm'])){
				$data['new_pass_confirm_err'] = 'Please enter new password.';
			}else{
				if ($data['new_pass_confirm'] !== $data['new_pass']) {
					$data['new_pass_confirm_err'] = 'Password does not match.';
				}
			}
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			// Check if errors are empty
			if (empty($data['current_pass_err']) && empty($data['new_pass_err']) && empty($data['new_pass_confirm_err'])){
				// Validated.
				// Update password.
				$data['new_pass'] = password_hash($data['new_pass'], PASSWORD_DEFAULT);
				if ($this->userModel->updatePassword($data)){
					flash('post_message', 'Password updated!');
					redirect('posts');
				} else {
					echo "Shit, something went wrong.";
					die();
				}
			} else {
				// Load view containing errors.
				$this->view('users/settingsPassword', $data);
			}
		}
		else {
			// Init data
			$data = [
				'current_pass' => '',
				'new_pass' => '',
				'new_pass_confirm' => '',
				'current_pass_err' => '',
				'new_pass_err' => '',
				'new_pass_confirm_err' => '',
			];
			$this->view('users/settingsPassword', $data);
		}
	}

	public function settingsEmail(){
		if(!isLoggedIn()){
			redirect('post');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'current_email' => $_POST['current_email'],
				'new_email' => $_POST['new_email'],
				'new_email_confirm' => $_POST['new_email_confirm'],
				'current_email_err' => '',
				'new_email_err' => '',
				'new_email_confirm_err' => '',
				'token' => $_POST['csrf-token'],
				'user_id' => $_SESSION['user_id'],
			];
			// Validate current e-mail address
			if (empty($data['current_email'])){
				$data['current_email_err'] = 'Please enter e-mail address.';
			}
			// Validate new email
			if (empty($data['new_email'])){
				$data['new_email_err'] = 'Please enter e-mail address.';
			}
			// Validate e-mail confirm
			if (empty($data['new_email_confirm'])){
				$data['new_email_confirm_err'] = 'Please enter e-mail address.';
			}else{
				if ($data['new_email_confirm'] !== $data['new_email']) {
					$data['new_email_confirm_err'] = 'E-mail address does not match.';
				}
			}
			// Check for user
			if ($this->userModel->findUserByEmail($data['current_email'])){
				// User found
				$userData = $this->userModel->getUserById($_SESSION['user_id']);
				if ($userData->id !== $_SESSION['user_id']){
					echo "Oh, no, you can't modify someone else's e-mail address!";
					die();
				}
			} else {
				$data['current_email_err'] = 'No account with this e-mail address found.';
			}
			// Check new e-mail address is not in use
			if ($this->userModel->findUserByEmail($data['new_email'])){
				// User found
				$data['new_email_err'] = 'E-mail already in use.';
			}
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't!";
				die();
			}
			// Check if errors are empty
			if (empty($data['current_email_err']) && empty($data['new_email_err']) && empty($data['new_email_confirm_err'])){
				// Validated.
				// Update e-mail address.
				if ($this->userModel->updateEmail($data)){
					flash('post_message', 'E-mail address updated!');
					redirect('posts');
				} else {
					echo "Shit, something went wrong.";
					die();
				}
			} else {
				// Load view containing errors.
				$this->view('users/settingsEmail', $data);
			}
		}
		else {
			// Init data
			$userData = $this->userModel->getUserById($_SESSION['user_id']);
			$data = [
				'current_email' => $userData->email,
				'new_email' => '',
				'new_email_confirm' => '',
				'current_email_err' => '',
				'new_email_err' => '',
				'new_email_confirm_err' => '',
			];
			$this->view('users/settingsEmail', $data);
		}
	}

	public function DeleteAccount(){
		if(!isLoggedIn()){
			redirect('post');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Process form
			// Sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			// Init data
			$data = [
				'token' => $_POST['csrf-token'],
				'user_id' => $_SESSION['user_id']
			];
			if (!$this->userModel->hasValidToken($data)){
				echo "Oh, no, you don't! No valid token!";
				die();
			}else{
				$this->postModel->deletePostsAndCommentsbyUserId($data['user_id']);
				$this->userModel->deleteUser($data);
				$this->userModel->deleteLikesByUserId($data['user_id']);
				flash('post_message', 'Account deleted!');
				redirect('posts');
			}
		}
		else {
			// Init data
			$userData = $this->userModel->getUserById($_SESSION['user_id']);
			$data = [
			];

			$this->view('users/DeleteAccount', $data);
		}
	}

	public function settings(){
		if(!isLoggedIn()){
			redirect('post');
		}
		$this->view('users/settings');
	}

	public function createUserSession($user){
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_username'] = $user->username;
		$_SESSION['user_name'] = $user->name;
		$_SESSION['token'] = $this->userModel->assignToken($user->id);
		redirect('posts');
	}

	public function logout(){
		print_r($_SESSION['token']);
		$this->userModel->destroyToken($_SESSION['token']);
		unset($_SESSION['token']);
		unset($_SESSION['user_id']);
		unset($_SESSION['user_username']);
		unset($_SESSION['user_name']);
		session_destroy();
		redirect('users/login');

	}
}
?>