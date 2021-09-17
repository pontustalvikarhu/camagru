<?php
session_start();

// Flash message helper
// EXAMPLE: flash('register_success', 'You are now registered.', 'alert alert-danger');
/* Display in view: <?php echo flash('register_success'); ?>*/
function flash($name = '', $message = '', $class = 'alert alert-success'){
	if(!empty($name)){
		if(!empty($message) && empty($_SESSION[$name])){
			if(!empty($_SESSION[$name])){
				unset($_SESSION[$name]);
			}
			if(!empty($_SESSION[$name.'_class'])){
				unset($_SESSION[$name.'_class']);
			}
			$_SESSION[$name] = $message;
			$_SESSION[$name.'_class'] = $class;
		}
		elseif (empty($message) && !empty($_SESSION[$name])) {
			$class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
			echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
			unset($_SESSION[$name]);
			unset($_SESSION[$name.'class']);
		}
	}
}

function emailAlert($toUser, $subject, $message){
	$to = $toUser;
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <info@camagru.com>' . "\r\n";
	mail($to,$subject,$message,$headers);
}

function isLoggedIn(){
	if (isset($_SESSION['user_id']) && isset($_SESSION['token'])){
		return (true);
	} else {
		return (false);
	}
}

?>