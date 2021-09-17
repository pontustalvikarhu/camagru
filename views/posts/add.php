<?php require APPROOT . '/views/inc/header.php'; ?>



<div><a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a></div>

	<div class="row mb-3">
		<div class="col-md-6">
		<div id="sidebar"><p>Photos taken</p></div>
    <button id="toggle">Hide / Show photos taken</button>
</div>
<div class="col-md-6">
<div class="container_img">
    <div class="image_box">
      <figure><video id="player" autoplay></video><figcaption>Camera</figcaption></figure>
    </div>
</div>
<div class="container_img">
	<div class="image_box">
      <figure><canvas id="canvas" width="400px" height="300px" title="preview" alt="preview"></canvas><figcaption>Preview</figcaption></figure>
    </div>
</div>
</div>
</div>
</div>
  <div class="photo_controls">
  <p>(Select a sticker to start.)</p>
    <button class="btn btn-disabled" id="capture-btn" disabled>Take photo!</button>
	<span>... or upload a photo!</span>
	<p>Use current photo or select one from the ones you have taken. Remember to press 'Save photo' after placing any stickers you want to save the photo!</p>
	<p>Place stickers by selecting a sticker, and then click where you want the upper left corner of the sticker to be.</p>
	<input type="file" id="upload" accept="image/*">
	<div>
		<button class="btn btn-primary" id="confirm-btn">Save photo</button>
	</div>	
  </div>

<img class="sticker" id="0" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_green.png"/>
<img class="sticker" id="1" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_red.png"/>
<img class="sticker" id="2" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/tactical_focus_bold_cyan.png"/>
<img class="sticker" id="3" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/talvi220.png"/>
<img class="sticker" id="4" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/warp_field.png"/>
<img class="sticker" id="5" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/pipu_200.png"/>
<img class="sticker" id="6" onclick="SetActive(this.id)" src="<?php echo URLROOT; ?>/stickers/pipu_100.png"/>



	  <div class="card card-body bg-light mt-5">
        <h2>Add New Post with Photo</h2>
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
			<input type="hidden" name="image" id="image" class="form-control form-control-lg <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?> value="">
            <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
          </div>
		  <div class="form-group">
			<input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']?>">
			<input type="hidden" name="stickers" id="stickers" value="">
			<input type="hidden" name="sticker-pos" id="sticker-pos" value="">
          </div>
		  <input type="submit" class="btn btn-success" id="submit-btn" value="Submit">
		  </form>
      </div>
	  <script src="../js/take_photo.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>