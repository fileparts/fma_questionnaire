<?php include('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include("./head.php"); ?>
  <script>
    $(document).ready(function() {
      var input = $("input[name=formName]");
      var value = $("input[name=formName]").val();

      input.keyup(function() {
        value = $(this).val();

        if(value.length > 0) {
          liveSearch();
        } else {
          $("button[type=Submit]")
            .removeClass("btn-success")
            .removeClass("btn-danger")
            .addClass("btn-default")
            .attr("disabled","true");
        };
      });

      function liveSearch() {
      $.ajax({
        method: "POST",
        url: "auto_user.php",
        data: { input: value }
      })
      .done(function(msg) {
        if(msg == 1) {
          $("button[type=Submit]")
            .removeClass("btn-default")
            .removeClass("btn-danger")
            .addClass("btn-success")
            .removeAttr("disabled");
        } else if(msg == 0) {
          $("button[type=Submit]")
            .removeClass("btn-default")
            .removeClass("btn-success")
            .addClass("btn-danger")
            .attr("disabled","true");
        };
      });
      };
    });
  </script>
</head>
<body>
  <?php include("./nav.php"); ?>
  <div class="main wrp">
    <?php if(!isset($_SESSION['q_userID'])) { ?>
    <h1 class="clr mrg-btm-x-lrg">Login</h1>
    <form method="post" action="./action.php?a=login">
      <table class="fixed">
        <tr>
          <td><p>Enter Username</p></td>
          <td><input name="formName" type="text" placeholder="Username" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Enter Password</p></td>
          <td><input name="formPass" type="password" placeholder="Password" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="btn-default" type="submit">Login</button></td>
        </tr>
        <tr>
          <td></td>
          <td><a href="./register.php">Don't have an account?</a></td>
        </tr>
      </table>
    </form>
    <?php } else { ?>
    <p class="alert">you are already logged in, redirecting...</p>
    <?php
            redirect("./");
          };
    ?>
  </div>
</body>
</html>
