


<?php require APPROOT . '/views/inc/header.php';?>
	<?php flash('post_message'); ?>
	<div class="row mb-3">
		<div class="col-md-6">
			<h1>Posts</h1>
		</div>
		<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'guest') : ?>
		<div class="col-md-6">
			<a href="<?php echo URLROOT;?>/posts/add" class="btn btn-primary pull-right">
		<i class="fa fa-pencil"></i> Add post
	</a>
	</div>
	<?php endif; ?>
	</div>
	<?php foreach($data['posts'] as $post) : ?>
	<div class="card card-body mb-3">
	<h4 class="card-title"><?php echo $post->title; ?></h4>
	<div class="img_container"><img src="<?php echo URLROOT; ?>/img/<?php echo $post->img; ?>" title="kitty" alt="talvi"></div>
	<div class="bg-light p-2 mb-3">posted by <?php echo $post->username; ?> on <?php echo $post->postCreated; ?></div>
	<p class="card-text"><?php echo $post->body; ?></p>
	<div class="comment-card">This is a comment.</div>
	<a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a></div>
	<?php endforeach; ?>
	
	<ul class="pagination">
    <li><a href="">First</a></li>
    <li class="">
        <a href="">Prev</a>
    </li>
    <li class="">
        <a href="">Next</a>
    </li>
    <li><a href="">Last</a></li>
</ul>
<?php require APPROOT . '/views/inc/footer.php';?>
