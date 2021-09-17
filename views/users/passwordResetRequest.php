<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Password reset request</h2>
		<div>
			<form action="<?php echo URLROOT; ?>/users/passwordResetRequest" method="post">
			<label for="email">E-mail address: <sup>*</sup></label>
            <input type="text" name="email" class="form-control form-control-lg" value="">
			<input type="submit" class="btn btn-success" value="Send password reset request">
        	</form>
		</div>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>