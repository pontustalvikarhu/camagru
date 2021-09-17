<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
      <h2>Add Comment</h2>
        <p>Add comment to post.</p>
		<div class="card card-body bg-light mt-5">
	  <h1><?php echo $data['postData']->title; ?></h1>
<div class="img_container"><img src="<?php echo $data['postData']->img; ?>" title="image" alt="image">
<div class="bg-secondary text-white p-2 mb-3">posted by <?php echo $data['userData']->username; ?> on <?php echo $data['postData']->created_at; ?></div></div>
<?php $user_liked = 0; foreach($data['likes'] as $like) : if ($data['postData']->id == $like->post_id) : if ($like->user_id === $_SESSION['user_id']) : $user_liked = 1; endif; endif; endforeach; ?>
		 <span><i class="fa fa-heart"></i> <?php $like_count = 0; foreach($data['likes'] as $like) : if ($data['postData']->id == $like->post_id) : $like_count += 1; endif; endforeach; echo $like_count; ?> likes</span>
	
<?php $like_str = ''; foreach($data['likes'] as $like) :  if ($data['postData']->id == $like->post_id) : $like_str = $like_str.$like->username.", "; endif; endforeach; $like_str = substr_replace($like_str, "", -2); ?>
	<p> <?php if (!empty($like_str)) : echo "<i class=\"fa fa-heart\"></i> ".$like_str; endif;?></p>
	<?php foreach($data['likes'] as $like) : ?>
		<?php if ($data['postData']->id == $like->post_id) : ?>
	<?php endif; ?>
	<?php endforeach; ?>
<p><?php echo $data['postData']->body; ?></p>
</div>
<?php foreach($data['comments'] as $comment) : ?>
	<div class="comment-card"><h6>comment by <?php echo $comment->username; ?></h6><p class="comment-text"><?php echo $comment->body; ?></p></div>

	<?php endforeach; ?>
	<div>
        <form action="<?php echo URLROOT; ?>/posts/comment/<?php echo $data['post']; ?>" method="post">
          <div class="form-group">
            <label for="body">Comment text: <sup>*</sup></label>
            <textarea name="body" class="form-control form-control-lg <?php echo (!empty($data['body_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['body']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['body_err']; ?></span>
			<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
          </div>
		  <input type="submit" class="btn btn-success" value="Submit">
        </form>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>