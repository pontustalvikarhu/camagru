<?php require APPROOT . '/views/inc/header.php'; ?>
<div><a href="<?php echo URLROOT; ?>/users/settings" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings: E-mail address</h2>
		<h3 id="change_email_header">Change email address</h3>
		<div id="change_email">
			<form action="<?php echo URLROOT; ?>/users/settingsEmail" method="post">
				<label for="current_email">Current email: <sup>*</sup></label>
				<input type="text" name="current_email" class="form-control form-control-lg <?php echo (!empty($data['current_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_email']; ?>">
				<span class="invalid-feedback"><?php echo $data['current_email_err']; ?></span>
				<label for="new_email">New email address: <sup>*</sup></label>
				<input type="text" name="new_email" class="form-control form-control-lg <?php echo (!empty($data['new_email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_email']; ?>">
				<span class="invalid-feedback"><?php echo $data['new_email_err']; ?></span>
				<label for="new_pass_confirm">Confirm new e-mail address: <sup>*</sup></label>
				<input type="text" name="new_email_confirm" class="form-control form-control-lg <?php echo (!empty($data['new_email_confirm_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_email_confirm']; ?>">
				<span class="invalid-feedback"><?php echo $data['new_email_confirm_err']; ?></span>
				<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
				<input type="submit" class="btn btn-success" value="Change e-mail address">
        	</form>
		</div>
      </div>
	  <!--<script src="../public/js/settingsEmail.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>