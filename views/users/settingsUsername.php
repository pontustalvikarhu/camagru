<?php require APPROOT . '/views/inc/header.php'; ?>
<div><a href="<?php echo URLROOT; ?>/users/settings" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings: Username</h2>
        <p>Here you can change your notification settings.</p>
		<h3 id="change_username_header">Change username</h3>
		<div id="change_username">
			<form action="<?php echo URLROOT; ?>/users/settingsUsername" method="post">
			<label for="current_username">Current username: <sup>*</sup></label>
            <input type="text" name="current_username" class="form-control form-control-lg <?php echo (!empty($data['current_username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_username']?>">
            <span class="invalid-feedback"><?php echo $data['current_username_err']; ?></span>
			<label for="new_pass">New username: <sup>*</sup></label>
            <input type="text" name="new_username" class="form-control form-control-lg <?php echo (!empty($data['new_username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_username']; ?>">
            <span class="invalid-feedback"><?php echo $data['new_username_err']; ?></span>
			<label for="new_username_confirm">Confirm new username: <sup>*</sup></label>
            <input type="text" name="new_username_confirm" class="form-control form-control-lg <?php echo (!empty($data['new_username_confirm_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_username_confirm']; ?>">
            <span class="invalid-feedback"><?php echo $data['new_username_confirm_err']; ?></span>
			<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
			<input type="submit" class="btn btn-success" value="Change username">
        	</form>
		</div>
      </div>
	  <!--<script src="../public/js/settingsUsername.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>