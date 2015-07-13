<?php include('config.php');session_destroy(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include("./head.php"); ?>
</head>
<body>
  <?php include("./nav.php"); ?>
  <div class="main wrp">
    <?php if(!isset($_SESSION['q_userID'])) { ?>
      <p class="alert">you must be logged in to logout, redirecting...</p>
    <?php
      redirect("./login.php");
    } else {
    ?>
      <p class="alert">logging out, redirecting...</p>
    <?php
      redirect("./");
    };
    ?>
  </div>
</body>
</html>
