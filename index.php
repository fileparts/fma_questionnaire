<?php include('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include("./head.php"); ?>
</head>
<body>
  <?php include("./nav.php"); ?>
  <div class="main wrp">
    <h1 class="clr mrg-btm-x-lrg">Questionnaires</h1>
    <p class="clr mrg-btm-x-lrg">Praesent eget urna sed dolor ullamcorper convallis ut egestas magna. Integer purus ipsum, viverra et purus sed, blandit feugiat massa. Aenean finibus iaculis ipsum, et efficitur risus vulputate ac. Nulla facilisi. Phasellus odio arcu, dignissim et mi sed.</p>
    <?php if(!isset($_SESSION['q_userID'])) { ?>
      <a class="btn btn-med btn-success" href="./login.php"><i class="fa fa-fw fa-user"></i> Login</a>
      <a class="btn btn-med btn-info" href="./register.php"><i class="fa fa-fw fa-user-plus"></i> Register</a>
    <?php } else {
      if($_SESSION['q_userPerms'] > 3) { ?>
      <a class="btn btn-med btn-warning" href="./admin.php"><i class="fa fa-fw fa-cog"></i> Admin</a>
      <?php }; ?>
      <a class="btn btn-med btn-danger" href="./logout.php"><i class="fa fa-fw fa-unlock-alt"></i> Logout</a>
    <?php }; ?>
  </div>
</body>
</html>
