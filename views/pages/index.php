


<?php require APPROOT . '/views/inc/header.php';?>
	<?php flash('post_message'); ?>
	<div class="row mb-3">
		<div class="col-md-6">
			<h1><?php echo $data['title']; ?></h1>
			<p><?php echo $data['description']; ?></p>
		</div>

<?php require APPROOT . '/views/inc/footer.php';?>
