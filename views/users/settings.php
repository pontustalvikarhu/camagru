<?php require APPROOT . '/views/inc/header.php'; ?>
<div><a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings</h2>
        <p>Here you can change your account settings.</p>
		<div><a href="<?php echo URLROOT; ?>/users/settingsNotifications">Change notification settings</a></div>
		<div><a href="<?php echo URLROOT; ?>/users/settingsUsername">Change username</a></div>
		<div><a href="<?php echo URLROOT; ?>/users/settingsPassword">Change password</a></div>
		<div><a href="<?php echo URLROOT; ?>/users/settingsEmail">Change e-mail address</a></div>
		<div><a href="<?php echo URLROOT; ?>/users/DeleteAccount">+++DANGER+++ Delete account +++DANGER+++</a></div>
		</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>