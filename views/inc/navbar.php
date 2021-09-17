<div class="topnav" id="myTopnav">
  <a class="navbar-brand home-link" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
  <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== 'guest') : ?>
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/settings">Settings</a>
            <p class="welcome-user" href="#">Welcome <?php echo $_SESSION['user_username']; ?></p>
		<?php else : ?>
      <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
      <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
		  <?php endif; ?>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
  <script>function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}</script>
</div>