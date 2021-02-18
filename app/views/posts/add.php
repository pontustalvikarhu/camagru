<?php require APPROOT . '/views/inc/header.php'; ?>

<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<div class="container_img">
    <div class="image_box">
      <figure><video id="player" autoplay></video><figcaption>Camera</figcaption></figure>
    </div>
    <div class="image_box"></div>
      <figure><canvas id="canvas" width="400px" height="300px" title="preview" alt="preview"></canvas><figcaption>Preview</figcaption></figure>
    </div>
  </div>
  <div class="center">
    <button class="btn btn-primary" id="capture-btn">Take photo!</button>
	<button class="btn btn-primary" id="confirm-btn">Save photo</button>
  </div>
  <div id="pick-image">
    <label>Video is not supported. Pick an image instead</label>
    <input type="file" accept="image/*" id="image-picker">
  </div>
</div>
<img class="sticker" id="0" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_green.png"/>
<img class="sticker" id="1" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_red.png"/>
<img class="sticker" id="2" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_cyan.png"/>
<img class="sticker" id="3" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/warp_field.png"/>


	  <div class="card card-body bg-light mt-5">
        <h2>Add Post</h2>
        <p>Create a new post.</p>
        <form action="<?php echo URLROOT; ?>/posts/add" method="post">
          <div class="form-group">
            <label for="title">Title: <sup>*</sup></label>
            <input type="text" name="title" class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
            <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
          </div>
          <div class="form-group">
            <label for="body">Body: <sup>*</sup></label>
            <textarea name="body" class="form-control form-control-lg <?php echo (!empty($data['body_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['body']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['body_err']; ?></span>
          </div>
		  <div class="form-group">
			<input type="hidden" name="image" id="image" class="form-control form-control-lg <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
          </div>
		  <input type="submit" class="btn btn-success" value="Submit">
        </form>
      </div>
	  <script src="../public/js/take_photo.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>