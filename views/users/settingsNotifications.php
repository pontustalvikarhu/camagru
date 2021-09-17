<?php require APPROOT . '/views/inc/header.php'; ?>
<div><a href="<?php echo URLROOT; ?>/users/settings" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>
<div class="card card-body bg-light mt-5">
	  <?php flash('update_success');?>
        <h2>Settings: Notifications</h2>
        <p>Here you can change your notification settings.</p>
		<div>
			<h3 id="comment_notification_header">Send notification when one of your posts receives a new comment</h3>
			<div id="change_comment_notification">
			<form action="<?php echo URLROOT; ?>/users/settingsNotifications" method="post">
				<input type="checkbox" id="comment_notify" name="comment_notification" value="Notify_on_comment" <?php if ($data['notification_setting'] == 1) : echo "checked"; else : echo ""; endif; ?>>
				<label for="comment_notification"> Send e-mail notification on new comment. </label>
				<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
				<input type="submit" class="btn btn-success" value="Save settings">
        	</form>
			</div>
		</div>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>