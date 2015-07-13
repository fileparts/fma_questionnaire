<?php include('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <?php include("./head.php"); ?>
</head>
<body>
  <?php include("./nav.php"); ?>
  <div class="main wrp">
    <?php if($_SESSION['q_userPerms'] > 3) { ?>
      <h1 class="mrg-btm-x-lrg">Admin Control Panel</h1>
      <div class="admin-controls clr mrg-btm-med">
        <table>
          <tr>
            <td><p>Create</p></td>
            <td>
              <a class="btn btn-grp" href="./create.php?a=qn">Questionnaire</a>
              <a class="btn btn-grp" href="./create.php?a=user">User</a>
            </td>
            <td><p>View</p></td>
            <td>
              <a class="btn btn-grp" href="./admin.php">Default</a>
              <a class="btn btn-grp" href="./admin.php?v=qn">Questionnaires</a>
              <a class="btn btn-grp" href="./admin.php?v=users">Users</a>
            </td>
          </tr>
        </table>
      </div>
      <?php if(!isset($_GET['v']) || $_GET['v'] == "qn") { ?>
      <table class="full fixed outline <?php if(!isset($_GET['v'])) { echo "mrg-btm-med"; } ?>">
        <tr class="head">
          <td colspan="3"><p>Questionnaires</p></td>
        </tr>
        <?php
        $getqn = $con->prepare("SELECT categoryID,categoryName,categoryAdmin FROM categories");
        $getqn->execute();
        $getqn->store_result();
        if($getqn->num_rows > 0) {
          $getqn->bind_result($catID,$catName,$catAdmin);
          while($getqn->fetch()) {
        ?>
        <tr>
          <td>
          <?php
          if($_SESSION['q_userID'] == $catAdmin || $_SESSION['q_userPerms'] > 3) {
          ?>
            <a href="./display.php?id=<?php echo $catID; ?>"><?php echo ucwords($catName); ?></a>
          <?php
          } else {
          ?>
            <a href="./view.php?id=<?php echo $catID; ?>"><?php echo ucwords($catName); ?></a>
          <?php
          };
          ?>
          </td>
          <td>
          <?php
          $getAdmin = $con->prepare("SELECT userFirst,userLast,userEmail FROM users WHERE userID=?");
          $getAdmin->bind_param("i", $catAdmin);
          $getAdmin->execute();
          $getAdmin->store_result();
          $getAdmin->bind_result($userFirst,$userLast,$userEmail);
          while($getAdmin->fetch()) {
          ?>
            <a href="mailto:<?php echo $userEmail; ?>"><?php echo $userFirst. ' ' .$userLast; ?></a>
          <?php
          };
          $getAdmin->close();
          ?>
          </td>
          <td class="fixed-100 options">
          <a href="./view.php?id=<?php echo $catID; ?>" title="Answer Questionnaire"><i class="fa fa-fw fa-pencil"></i></a>
          <?php
          if($_SESSION['q_userID'] == $catAdmin || $_SESSION['q_userPerms'] > 3) {
          ?>
          <a href="./display.php?id=<?php echo $catID; ?>" title="View Questionnaire"><i class="fa fa-fw fa-eye"></i></a>
          <a href="./edit.php?a=qn&id=<?php echo $catID; ?>" title="Edit Questionnaire"><i class="fa fa-fw fa-cog"></i></a>
          <?php
          };
          ?>
          </td>
        </tr>
        <?php
          };
        } else {
        ?>
        <tr>
          <td><p class="alert">no questionnaires found</p></td>
        </tr>
        <?php
        };
        $getqn->close();
        ?>
      </table>
      <?php }; ?>
      <?php if(!isset($_GET['v']) || $_GET['v'] == "users") { ?>
      <table class="full fixed outline <?php if(!isset($_GET['v'])) { echo "mrg-btm-med"; } ?>">
        <tr class="head">
          <td colspan="4"><p>Users</p></td>
        </tr>
        <?php
        $getUsers = $con->prepare("SELECT userID,userName,userFirst,userLast,userEmail,userPerms FROM users");
        $getUsers->execute();
        $getUsers->store_result();
        $getUsers->bind_result($userID,$userName,$userFirst,$userLast,$userEmail,$userPerms);
        while($getUsers->fetch()) {
        ?>
        <tr>
          <td><a href="mailto:<?php echo $userEmail; ?>"><?php echo $userName; ?></a></td>
          <td><a href="mailto:<?php echo $userEmail; ?>"><?php echo $userFirst. ' ' .$userLast; ?></a></td>
          <td><a href="mailto:<?php echo $userEmail; ?>"><?php echo $userEmail; ?></a></td>
          <td class="fixed-100 options">
            <?php if($userPerms > 0 && $userPerms < 5) { ?>
            <a class="confirm" href="./action.php?a=demote&id=<?php echo $userID; ?>" title="
            <?php
            if($userPerms == 1) { echo "Demote to Banned"; }
            else if($userPerms == 4) { echo "Demote to User"; };
            ?>
            "><i class="fa fa-fw fa-angle-double-down"></i></a>
            <?php }; ?>
            <?php if($userPerms >= 0 && $userPerms < 4) { ?>
            <a class="confirm" href="./action.php?a=promote&id=<?php echo $userID; ?>" title="
            <?php
            if($userPerms == 0) { echo "Promote to User"; }
            else if($userPerms == 1) { echo "Promote to Admin"; };
            ?>
            "><i class="fa fa-fw fa-angle-double-up"></i></a>
            <?php
            };
            if($userPerms < 5) {
            ?>
            <a href="./edit.php?a=user&id=<?php echo $userID; ?>"><i class="fa fa-fw fa-cog"></i></a>
            <?php }; ?>
          </td>
        </tr>
        <?php
        };
        $getUsers->close();
        ?>
      </table>
      <?php }; ?>
    <?php } else { ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
      redirect("./");
    };
    ?>
  </div>
</body>
</html>
