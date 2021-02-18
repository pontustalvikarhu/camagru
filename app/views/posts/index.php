


<?php require APPROOT . '/views/inc/header.php';?>
	<?php flash('post_message'); ?>
	<div class="row mb-3">
		<div class="col-md-6">
			<h1>Posts</h1>
		</div>
		<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'guest') : ?>
		<div class="col-md-6">
			<a href="<?php echo URLROOT;?>/posts/add" class="btn btn-primary pull-right">
		<i class="fa fa-camera"></i> Add post
	</a>
	</div>
	<?php endif; ?>
	</div>
	<?php foreach($data['posts'] as $post) : ?>
	<div class="card card-body mb-3">
	<h4 class="card-title"><?php echo $post->title; ?></h4>
	<div class="img_container"><img src="<?php echo URLROOT; ?>/img/<?php echo $post->img; ?>" title="image" alt="image"></div>
	<div class="bg-light p-2 mb-3">posted by <?php echo $post->username; ?> on <?php echo $post->postCreated; ?></div>
	<p class="card-text"><?php echo $post->body; ?></p><?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != 'guest') : ?>
		<div>
			<a href="<?php echo URLROOT;?>/posts/comment/<?php echo $post->postId; ?>" class="btn btn-primary pull-right">
		<i class="fa fa-pencil"></i> Comment post</a>
		<?php $user_liked = 0; foreach($data['likes'] as $like) : if ($post->postId == $like->post_id) : if ($like->user_id === $_SESSION['user_id']) : $user_liked = 1; endif; endif; endforeach; ?>
		<form action="<?php echo URLROOT; ?>/posts/<?php if ($user_liked === 0) : echo "like"; endif; if ($user_liked === 1) : echo "unlike"; endif; ?>/<?php echo $post->postId; ?>" method="post"><input type="submit" class="btn btn-success" value="<?php if ($user_liked === 0) : echo "Like"; endif; if ($user_liked === 1) : echo "Unlike"; endif; ?>"></form>
		<i class="fa fa-heart"></i> <span><?php $like_count = 0; foreach($data['likes'] as $like) : if ($post->postId == $like->post_id) : $like_count += 1; endif; endforeach; echo $like_count; ?> likes</span>
	</div>
	<?php endif; ?>
	<!--<h5>Likes:</h5>-->
	<?php $like_str = ''; foreach($data['likes'] as $like) :  if ($post->postId == $like->post_id) : $like_str = $like_str.$like->username.", "; endif; endforeach; $like_str = substr_replace($like_str, "", -2); ?>
	<p> <?php if (!empty($like_str)) : echo "<i class=\"fa fa-heart\"></i> ".$like_str; endif;?></p>
	<!--<ul>
	<?php foreach($data['likes'] as $like) : ?>
		<?php if ($post->postId == $like->post_id) : ?>
	<li class="like-line"><h6><i class="fa fa-heart"></i><?php echo $like->username; ?></h6></li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>-->
	<?php foreach($data['comments'] as $comment) : ?>
		<?php if ($post->postId == $comment->post_id) : ?>
	<div class="comment-card"><h6>comment by <?php echo $comment->username; ?></h6><p class="comment-text"><?php echo $comment->body; ?></p></div>
	<?php endif; ?>
	<?php endforeach; ?>
	<a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a></div>
	<?php endforeach; ?>
	

	<?php
	// Pagination links.
	$number_of_pages = $data['count'];
	if($data['current_page'] !== 1) {
		echo "<a href = \"".URLROOT."/posts/?page=" . ($data['current_page'] - 1) . '">'.'&laquo;</a> ';
	}
    for($page = 1; $page <= $number_of_pages; $page++) {
		if ($page == $data['current_page']) {
			echo "<a class=\"current_page\" href = \"".URLROOT."/posts/?page=" . $page . '">' . $page . '</a> ';
		}
		else {
        	echo "<a href = \"".URLROOT."/posts/?page=" . $page . '">' . $page . ' </a>';
		}
    } 
	if($data['current_page'] != $number_of_pages) {
		echo "<a href = \"".URLROOT."/posts/?page=" . ($data['current_page'] + 1) . '">'.'&raquo;</a> ';
	}
	?>

<?php require APPROOT . '/views/inc/footer.php';?>
