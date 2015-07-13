<?php include('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include("./head.php"); ?>
</head>
<body>
  <?php include("./nav.php"); ?>
  <div class="main wrp">
    <?php
    if(isset($_GET['a'])) {
        $action = $_GET['a'];
        if($action == "user") {
          if($_SESSION['q_userPerms'] > 3) {
    ?>
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
              .removeClass("btn-success")
              .addClass("btn-danger")
              .attr("disabled","true");
          } else if(msg == 0) {
            $("button[type=Submit]")
              .removeClass("btn-default")
              .removeClass("btn-danger")
              .addClass("btn-success")
              .removeAttr("disabled");
          };
        });
        };
      });
    </script>
    <h1 class="clr mrg-btm-x-lrg">Create a User</h1>
    <form method="post" action="./action.php?a=create">
      <input name="formType" type="hidden" value="<?php echo $action; ?>" required />
      <table class="fixed">
        <tr>
          <td><p>Enter Username</p></td>
          <td><input name="formName" type="text" placeholder="Username" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Enter First Name(s)</p></td>
          <td><input name="formFirst" type="text" placeholder="First Name(s)" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td><p>Enter Last Name(s)</p></td>
          <td><input name="formLast" type="text" placeholder="Last Name(s)" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td><p>Enter Email Address</p></td>
          <td><input name="formEmail" type="email" placeholder="Email Address" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td><p>Enter Password</p></td>
          <td><input name="formPass" type="password" placeholder="Password" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="confirm btn-default" type="submit">Create</button></td>
        </tr>
      </table>
    </form>
    <?php
        } else {
    ?>
    <p class="alert">you do not have permssion to view this page, redirecting...</p>
    <?php
            redirect("./");
          };
    ?>
    <?php
        } else if($action == "qn") {
    ?>
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
          url: "auto_qn.php",
          data: { input: value }
        })
        .done(function(msg) {
          if(msg == 1) {
            $("button[type=Submit]")
              .removeClass("btn-default")
              .removeClass("btn-success")
              .addClass("btn-danger")
              .attr("disabled","true");
          } else if(msg == 0) {
            $("button[type=Submit]")
              .removeClass("btn-default")
              .removeClass("btn-danger")
              .addClass("btn-success")
              .removeAttr("disabled");
          };
        });
        };
      });
    </script>
    <h1 class="clr mrg-btm-x-lrg">Create a Questionnaire</h1>
    <form method="post" action="./action.php?a=create">
      <input name="formType" type="hidden" value="<?php echo $action; ?>" required />
      <table class="fixed">
        <tr>
          <td><p>Enter Questionnaire Title</p></td>
          <td><input name="formName" type="text" placeholder="Questionnaire Title" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Select Questionnaire Admin</p></td>
          <td>
            <select name="formAdmin" required>
            <?php
            $getUsers = $con->prepare("SELECT userID,userFirst,userLast FROM users");
            $getUsers->execute();
            $getUsers->store_result();
            if($getUsers->num_rows > 0) {
            ?>
              <option selected disabled>Select an Admin</option>
            <?php
              $getUsers->bind_result($userID,$userFirst,$userLast);
              while($getUsers->fetch()) {
            ?>
              <option value="<?php echo $userID; ?>"><?php echo $userFirst. ' ' .$userLast; ?></option>
            <?php
              };
            } else {
            ?>
              <option selected disabled>No Users Found</option>
            <?php
            };
            $getUsers->close();
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><p>Select Questionnaire Visibility</p></td>
          <td>
            <select name="formPerms" required>
              <option selected disabled>Select an Option</option>
              <option value="1">Visible</option>
              <option value="0">Not Visible</option>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><button class="confirm btn-default" type="submit">Create</button></td>
        </tr>
        <tr>
          <td></td>
          <td><p class="subtitle">Pro Tip: Set as Visible once all the questions are ready</p></td>
        </tr>
      </table>
    </form>
    <?php
        } else {
    ?>

    <?php
        };
    ?>

    <?php } else { ?>
    <p class="alert">an action is required, redirecting...</p>
    <?php
      redirect("./");
    };
    ?>
  </div>
</body>
</html>
