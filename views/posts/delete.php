<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['post']->title; ?></h1>
<p>Are you sure you want to delete this post?</p>
<form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" method="post">
<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
<input type="submit" value="Delete" class="btn btn-danger"></form>
<?php require APPROOT . '/views/inc/footer.php'; ?>