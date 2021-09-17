<?php
class User {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	// Register function
	public function register($data){
		$this->db->query('INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)');
		$this->db->bind(':name', $data['name']);
		$this->db->bind(':username', $data['username']);
		$this->db->bind(':email', $data['email']);
		$this->db->bind(':password', $data['password']);
		if ($this->db->execute()){
			$this->db->query('INSERT INTO registration_keys (v_key, email) VALUES (:v_key, :email)');
			$salt = 'soy, shortbread, pirates, and biscuits';
			$v_key = md5(md5($salt.$data['email']));
			$this->db->bind(':v_key', $v_key);
			$this->db->bind(':email', $data['email']);
			if ($this->db->execute()){
					$to = $data['email'];
					$subject = "Account verification for Camagru";

					$message = "
					<html>
					<head>
					<title>Thank you for signing up!</title>
					</head>
					<body>
					<p>This e-mail contains your e-mail verification link. Please go to </p>
					<a href=\"".URLROOT."/users/verify/?token=".$v_key."&email=".$data['email']."\">this link</a>
					<p> to verify your account.</p>
					<p>XOXO from the Camagru team</p>
					</body>
					</html>
					";
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <info@camagru.com>' . "\r\n";
					mail($to,$subject,$message,$headers);
			} else {
				return (false);
			}
			return (true);
		} else {
			return (false);
		}
	}

	public function login($username, $password){
		$this->db->query('SELECT * FROM users WHERE username = :username');
		$this->db->bind(':username', $username);
		$row = $this->db->single();
		$hashed_password = $row->password;
		if (password_verify($password, $hashed_password)){
			return $row;
		} else {
			return (false);
		}
	}

	// Check password by user ID
	public function checkPassword($user_id, $password){
		$this->db->query('SELECT * FROM users WHERE id = :id');
		$this->db->bind(':id', $user_id);
		$row = $this->db->single();
		$hashed_password = $row->password;
		if (password_verify($password, $hashed_password)){
			return (true);
		} else {
			return (false);
		}
	}

	// Assign token to logged-in user.
	public function assignToken($userId){
		$secret = "Banji ni oite shi wa nashi.";
		$random = openssl_random_pseudo_bytes(84);
		$token = md5($secret.$random);
		$this->db->query('INSERT INTO tokens (user_id, token) values (:user_id, :token)');
		$this->db->bind(':user_id', $userId);
		$this->db->bind(':token', $token);
		if ($this->db->execute()){
			return ($token);
		}else{
			die('Sh*t, something went wrong with the token.');
		}
	}

	// Destroy a user token on logout.
	public function destroyToken ($token){
		$this->db->query('DELETE FROM tokens WHERE token = :token');
		$this->db->bind(':token', $token);
		if ($this->db->execute()){
			return ;
		}else{
			print_r($token);
		}
	}

	public function destroyResetToken($token){
		$this->db->query('DELETE FROM `password_reset_keys` WHERE reset_key = :token');
		$this->db->bind(':token', $token);
		$this->db->execute();
	}

	// Verify user with verification token.
	public function verify($token, $email){
		$this->db->query('SELECT * FROM registration_keys WHERE email = :email AND v_key = :token');
		$this->db->bind(':email', $email);
		$this->db->bind(':token', $token);
		$row = $this->db->single();
		print_r($row);
		if ($this->db->rowCount() > 0){
			$this->db->query('DELETE FROM `registration_keys` WHERE email = :email AND v_key = :token');
			$this->db->bind(':email', $email);
			$this->db->bind(':token', $token);
			if ($this->db->execute()){
				$this->db->query('UPDATE users SET verified = 1 WHERE email = :email');
				$this->db->bind(':email', $email);
				if ($this->db->execute()){
					return (true);
				} else {
					return (false);
				}
			}
			else {
				return (false);
			}
		}
		else {
			return (false);
		}
	}

	// Find user by email
	public function findUserByEmail($email){
		$this->db->query('SELECT * FROM users WHERE email = :email');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
			return (true);
		}
		else {
			return (false);
		}
	}

	// Find user by username
	public function findUserByUsername($username){
		$this->db->query('SELECT * FROM users WHERE username = :username');
		$this->db->bind(':username', $username);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
			return (true);
		}
		else {
			return (false);
		}
	}

	// Check user verification
	public function findUserVerificationByUsername($username){
		$this->db->query('SELECT * FROM users WHERE username = :username AND verified = 1');
		$this->db->bind(':username', $username);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
			return (true);
		}
		else {
			return (false);
		}
	}

	// Check user's token.
	public function hasValidToken($data){
		$this->db->query('SELECT * FROM tokens WHERE token = :token');
		$this->db->bind(':token', $data['token']);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
				if ($row->user_id === $data['user_id']){
				return (true);
			}else{
				return(false);
			}
		}else{
			return(false);
		}
	}

	// Get user by id.
	public function getUserById($id){
		$this->db->query('SELECT * FROM users WHERE id = :id');
		$this->db->bind(':id', $id);
		$row = $this->db->single();
		return ($row);
	}

	// Get user by email
	public function getUserByEmail($email){
		$this->db->query('SELECT * FROM users WHERE email = :email');
		$this->db->bind(':email', $email);
		$row = $this->db->single();
		return ($row);
	}

	// Get user by reset token.
	public function getUserByResetToken($token){
		// Get e-mail address associated with token.
		$this->db->query('SELECT * FROM password_reset_keys WHERE reset_key = :reset_token');
		$this->db->bind(':reset_token', $token);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
			$this->db->query('SELECT * FROM users WHERE email = :email');
			$this->db->bind(':email', $row->email);

			$row = $this->db->single();

			return ($row);
		}
		else {
			return (false);
		}
	}

	// Send password reset email.
	public function sendResetPasswordRequest($data){
		$this->db->query('SELECT * FROM password_reset_keys WHERE email = :email');
		$this->db->bind(':email', $data['email']);
		$row = $this->db->single();
		if ($this->db->rowCount() > 0){
			$to = $data['email'];
			$subject = "Password reset request for Camagru";

			$message = "
			<html>
			<head>
			<title>Password reset requested</title>
			</head>
			<body>
			<p>This e-mail contains your password reset link. Please go to </p>
			<a href=\"".URLROOT."/users/passwordReset/?reset-token=".$row->reset_key."\">this link</a>
			<p> to reset your password.</p>
			<p>If you have no knowledge of submitting a password reset request, get in touch with Camagru support immediately!</p>
			<p>XOXO from the Camagru team</p>
			</body>
			</html>
			";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <info@camagru.com>' . "\r\n";
			mail($to, $subject, $message, $headers);
		}else{
			$this->db->query('INSERT INTO password_reset_keys (reset_key, email) VALUES (:reset_key, :email)');
			$salt = 'himalayan, sea, shiokoshou';
			$reset_key = md5(md5($salt.$data['email']));
			$this->db->bind(':reset_key', $reset_key);
			$this->db->bind(':email', $data['email']);
			$this->db->execute();
			$to = $data['email'];
			$subject = "Password reset request for Camagru";

			$message = "
			<html>
			<head>
			<title>Password reset requested</title>
			</head>
			<body>
			<p>This e-mail contains your password reset link. Please go to </p>
			<a href=\"".URLROOT."/users/passwordReset/?reset-token=".$reset_key."\">this link</a>
			<p> to reset your password.</p>
			<p>If you have no knowledge of submitting a password reset request, get in touch with Camagru support immediately!</p>
			<p>XOXO from the Camagru team</p>
			</body>
			</html>
			";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <info@camagru.com>' . "\r\n";
			mail($to,$subject,$message,$headers);
		}
	}

	// Update notification settings.
	public function updateNotifications($data){
		$this->db->query('UPDATE users SET notify_on_comment = :setting WHERE id = :id');
		$this->db->bind(':id', $data['user_id']);
		$this->db->bind(':setting', $data['comment_notify']);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Check notification settings for user.
	public function checkNotificationOnComment($user_id){
		$this->db->query('SELECT * FROM users WHERE id = :id');
		$this->db->bind(':id', $user_id);

		$row = $this->db->single();
		echo "In notification checker.";
		print_r($row);
		if ($row->notify_on_comment == 1){
			return (true);
		} else {
			return (false);
		}
	}

	// Update username
	public function updateUsername($data){
		$this->db->query('UPDATE users SET username = :new_username WHERE id = :id');
		$this->db->bind(':id', $data['user_id']);
		$this->db->bind(':new_username', $data['new_username']);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Update e-mail address
	public function updateEmail($data){
		$this->db->query('UPDATE users SET email = :new_email WHERE id = :id');
		$this->db->bind(':id', $data['user_id']);
		$this->db->bind(':new_email', $data['new_email']);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}
	public function updatePassword($data){
		//print_r($data);
		$this->db->query('UPDATE users SET password = :new_password WHERE id = :id');
		$this->db->bind(':id', $data['user_id']);
		$this->db->bind(':new_password', $data['new_pass']);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// remove comments owned by user
	public function deleteCommentsByUserId($user_id){
		$this->db->query('DELETE FROM comments WHERE user_id = :user_id');
		$this->db->bind(':user_id', $user_id);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// remove likes owned by user
	public function deleteLikesByUserId($user_id){
		$this->db->query('DELETE FROM likes WHERE user_id = :user_id');
		$this->db->bind(':user_id', $user_id);
		if ($this->db->execute()){
			return (true);
		} else {
			return (false);
		}
	}

	// Delete user account, and destroy session along with session data.
	public function deleteUser($data){
		$this->destroyToken($data['token']);
		$this->db->query('DELETE FROM users WHERE id = :id');
		$this->db->bind(':id', $data['user_id']);
		if ($this->db->execute()){
			unset($_SESSION['token']);
			unset($_SESSION['user_id']);
			unset($_SESSION['user_username']);
			unset($_SESSION['user_name']);
			session_destroy();
				return (true);
		} else {
			return (false);
		}
	}
}