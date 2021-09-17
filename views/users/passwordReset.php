<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Password reset</h2>
        <h3 id="change_password_header">Change password</h3>
		<div id="change_password">
			<form action="<?php echo URLROOT; ?>/users/passwordReset" method="post">
			<input type="text" name="username" value="" autocomplete="username" style="display: none;">
			<label for="new_pass">New password: <sup>*</sup></label>
            <input type="password" name="new_pass" class="form-control form-control-lg <?php echo (!empty($data['new_pass_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_pass']; ?>" autocomplete="new-password">
            <span class="invalid-feedback"><?php echo $data['new_pass_err']; ?></span>
			<label for="new_pass_confirm">Confirm new password: <sup>*</sup></label>
            <input type="password" name="new_pass_confirm" class="form-control form-control-lg <?php echo (!empty($data['new_pass_confirm_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_pass_confirm']; ?>" autocomplete="new-password">
            <span class="invalid-feedback"><?php echo $data['new_pass_confirm_err']; ?></span>
			<input type="hidden" name="reset-token" id="reset-token" value="<?php echo (!isset($_POST['reset-token'])) ? $_GET['reset-token'] : $_POST['reset-token'] ?>">
			<input type="submit" class="btn btn-success" value="Change password">
        	</form>
		</div>
      </div>
	  <!--<script src="../public/js/settingsPassword.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>