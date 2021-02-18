<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
      <div class="card card-body bg-light mt-5">
        <h2>Add Comment</h2>
        <p>Add comment to post.</p>
        <form action="<?php echo URLROOT; ?>/posts/comment/<?php echo $data['post']; ?>" method="post">
          <div class="form-group">
            <label for="body">Comment text: <sup>*</sup></label>
            <textarea name="body" class="form-control form-control-lg <?php echo (!empty($data['body_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['body']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['body_err']; ?></span>
          </div>
		  <input type="submit" class="btn btn-success" value="Submit">
        </form>
      </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>