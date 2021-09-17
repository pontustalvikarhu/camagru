<?php require APPROOT . '/views/inc/header.php'; ?>
<div><a href="<?php echo URLROOT; ?>/users/settings" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings: Password</h2>
        <h3 id="change_password_header">Change password</h3>
		<div id="change_password">
			<form action="<?php echo URLROOT; ?>/users/settingsPassword" method="post">
			<input type="text" name="username" value="" autocomplete="username" style="display: none;">
			<label for="current_pass">Current password: <sup>*</sup></label>
            <input type="password" name="current_pass" class="form-control form-control-lg <?php echo (!empty($data['current_pass_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_pass']; ?>" autocomplete="current-password">
            <span class="invalid-feedback"><?php echo $data['current_pass_err']; ?></span>
			<label for="new_pass">New password: <sup>*</sup></label>
            <input type="password" name="new_pass" class="form-control form-control-lg <?php echo (!empty($data['new_pass_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_pass']; ?>" autocomplete="new-password">
            <span class="invalid-feedback"><?php echo $data['new_pass_err']; ?></span>
			<label for="new_pass_confirm">Confirm new password: <sup>*</sup></label>
            <input type="password" name="new_pass_confirm" class="form-control form-control-lg <?php echo (!empty($data['new_pass_confirm_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_pass_confirm']; ?>" autocomplete="off">
            <span class="invalid-feedback"><?php echo $data['new_pass_confirm_err']; ?></span>
			<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
			<input type="submit" class="btn btn-success" value="Change password">
        	</form>
		</div>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>