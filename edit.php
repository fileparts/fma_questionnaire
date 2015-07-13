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
      if(isset($_GET['id'])) {
        $action = $_GET['a'];
        $editID = $_GET['id'];

        if($action == "user") {
          if($_SESSION['q_userPerms'] > 3) {
            $checkID = $con->prepare("SELECT userID,userName,userFirst,userLast,userEmail FROM users WHERE userID=?");
            $checkID->bind_param("i", $editID);
            $checkID->execute();
            $checkID->store_result();
            if($checkID->num_rows > 0) {
              $checkID->bind_result($userID,$userName,$userFirst,$userLast,$userEmail);
              while($checkID->fetch()) {
    ?>
    <h1 class="mrg-btm-x-lrg">Edit <?php echo $userName; ?></h1>
    <form class="clr mrg-btm-x-lrg" method="post" action="./action.php?a=edit">
      <input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
      <input name="formType" type="hidden" value="<?php echo $action; ?>" required />
      <table>
        <tr>
          <td><p>Edit First Name</p></td>
          <td><input name="formFirst" type="text" value="<?php echo $userFirst; ?>" autocomplete="off" autofocus required /></td>
        </tr>
        <tr>
          <td><p>Edit Last Name</p></td>
          <td><input name="formLast" type="text" value="<?php echo $userLast; ?>" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td><p>Edit Email Address</p></td>
          <td><input name="formEmail" type="email" value="<?php echo $userEmail; ?>" autocomplete="off" required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="confirm btn-warning" type="submit">Submit</button></td>
        </tr>
      </table>
    </form>
    <form method="post" action="./action.php?a=edit">
      <input name="formID" type="hidden" value="<?php echo $userID; ?>" required />
      <input name="formType" type="hidden" value="userpass" required />
      <table>
        <tr>
          <td><p>Edit Password</p></td>
          <td><input name="formPass" type="password" placeholder="New Password" required /></td>
        </tr>
        <tr>
          <td></td>
          <td><button class="confirm btn-warning" type="submit">Submit</button></td>
        </tr>
      </table>
    </form>
    <?php
              };
            } else {
    ?>
    <p class="alert">a valid user id is required, redirecting...</p>
    <?php
              redirect("./admin.php");
            };
            $checkID->close();
          } else {
    ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
            redirect("./");
          };
        } else if($action == "qn") {
          $checkID = $con->prepare("SELECT * FROM categories WHERE categoryID=?");
          $checkID->bind_param("i", $editID);
          $checkID->execute();
          $checkID->store_result();
          if($checkID->num_rows > 0) {
            $checkID->bind_result($catID,$catName,$catPerms,$catAdmin);
            while($checkID->fetch()) {
              if($_SESSION['q_userID'] == $catAdmin || $_SESSION['q_userPerms'] > 3) {
    ?>
    <h1 class="mrg-btm-x-lrg">Edit <?php echo ucwords($catName); ?></h1>
    <div class="admin-controls clr mrg-btm-med">
      <table>
        <tr>
          <td>Create</td>
          <td>
            <a class="btn btn-grp">Open Question</a>
            <a class="btn btn-grp">Closed Question</a>
          </td>
          <td>View</td>
          <td>
            <a class="btn btn-grp">Answer Mode</a>
            <a class="btn btn-grp">Display Mode</a>
          </td>
        </tr>
      </table>
    </div>
    <?php
              } else {
    ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
                redirect("./");
              };
            };
          } else {
    ?>
    <p class="alert">a valid questionnaire id is required, redirecting...</p>
    <?php
            redirect("./");
          };
          $checkID->close();
        } else {
    ?>
    <p class="alert">a valid action is required, redirecting...</p>
    <?php
          redirect("./");
        };
      } else {
    ?>
    <p class="alert">an id is required, redirecting...</p>
    <?php
        redirect("./");
      };
    } else {
    ?>
    <p class="alert">an action is required, redirecting...</p>
    <?php
      redirect("./");
    };
    ?>
  </div>
</body>
</html>
