<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['post']->title; ?></h1>
<div class="img_container"><img src="<?php echo $data['post']->img; ?>" title="image" alt="image">
<div class="bg-secondary text-white p-2 mb-3">posted by <?php echo $data['user']->username; ?> on <?php echo $data['post']->created_at; ?></div></div>
<?php $user_liked = 0; foreach($data['likes'] as $like) : if ($data['post']->id == $like->post_id) : if ($like->user_id === $_SESSION['user_id']) : $user_liked = 1; endif; endif; endforeach; ?>
		 <span><i class="fa fa-heart"></i> <?php $like_count = 0; foreach($data['likes'] as $like) : if ($data['post']->id == $like->post_id) : $like_count += 1; endif; endforeach; echo $like_count; ?> likes</span>
	
<?php $like_str = ''; foreach($data['likes'] as $like) :  if ($data['post']->id == $like->post_id) : $like_str = $like_str.$like->username.", "; endif; endforeach; $like_str = substr_replace($like_str, "", -2); ?>
	<p> <?php if (!empty($like_str)) : echo "<i class=\"fa fa-heart\"></i> ".$like_str; endif;?></p>
	<?php foreach($data['likes'] as $like) : ?>
		<?php if ($data['post']->id == $like->post_id) : ?>
	<?php endif; ?>
	<?php endforeach; ?>
<p><?php echo $data['post']->body; ?></p>
<?php foreach($data['comments'] as $comment) : ?>
	<div class="comment-card"><h6>comment by <?php echo $comment->username; ?></h6><p class="comment-text"><?php echo $comment->body; ?></p></div>

	<?php endforeach; ?>
<?php if($data['post']->user_id == $_SESSION['user_id']) : ?>
<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark"> Edit</a>
<form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->id; ?>" method="post">
<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
<input type="submit" value="Delete" class="btn btn-danger"></form>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>