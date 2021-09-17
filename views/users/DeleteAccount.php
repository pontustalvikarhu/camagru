<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings: Delete account</h2>
        <div>
			<h3>+++DANGER AREA+++ Delete account +++DANGER AREA+++</h3>
			<form action="<?php echo URLROOT; ?>/users/DeleteAccount" method="post">
				<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
				<input type="submit" class="btn btn-danger" value="Delete account">
        	</form>
		</div>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>