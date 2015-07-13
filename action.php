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
      $okay = false;

      if($action == "register") {
//REGISTER
        $formName = strtolower($_POST['formName']);
        $formFirst = $_POST['formFirst'];
        $formLast = $_POST['formLast'];
        $formEmail = $_POST['formEmail'];
        $formPass = $_POST['formPass'];
        $formPass = better_crypt($formPass);

        $checkUser = $con->prepare("SELECT * FROM users WHERE userName=?");
        $checkUser->bind_param("s", $formName);
        $checkUser->execute();
        $checkUser->store_result();
        if($checkUser->num_rows > 0) {
    ?>
    <p class="alert">that username is already taken, try again...</p>
    <?php
          redirect("./register.php");
        } else {
          $createUser = $con->prepare("INSERT INTO users(userName,userFirst,userLast,userEmail,userPass) VALUES(?,?,?,?,?)");
            $createUser->bind_param("sssss", $formName,$formFirst,$formLast,$formEmail,$formPass);
            if($createUser->execute()) {
    ?>
      <p class="alert">user successfully created, redirecting...</p>
    <?php
              redirect("./login.php");
            } else {
    ?>
      <p class="alert">user unsuccessfully created, try again...</p>
    <?php
              redirect("./register.php");
            };
            $createUser->close();
        };
        $checkUser->close();
      } else if($action == "login") {
//lOGIN
        $formName = strtolower($_POST['formName']);
        $formPass = $_POST['formPass'];

        $checkUser = $con->prepare("SELECT userID,userPass,userPerms FROM users WHERE userName=?");
        $checkUser->bind_param("s", $formName);
        $checkUser->execute();
        $checkUser->store_result();
        if($checkUser->num_rows > 0) {
          $checkUser->bind_result($userID,$userPass,$userPerms);
          while($checkUser->fetch()) {
            if(hash_equals($userPass, crypt($formPass,$userPass))) {
              $_SESSION['q_userID'] = $userID;
              $_SESSION['q_userPerms'] = $userPerms;
    ?>
    <p class="alert">logging in...</p>
    <?php
              redirect("./");
            } else {
    ?>
    <p class="alert">incorrect password, try again...</p>
    <?php
              redirect("./login.php");
            };
          };
        } else {
    ?>
    <p class="alert">user not found, try again...</p>
    <?php
          redirect("./login.php");
        };
        $checkUser->close();
      } else if($action == "create") {
        if(isset($_POST['formType'])) {
          $formType = $_POST['formType'];

          if($formType == "user") {
//CREATE A USER
            if($_SESSION['q_userPerms'] > 3) {
              $formName = strtolower($_POST['formName']);
              $formFirst = $_POST['formFirst'];
              $formLast = $_POST['formLast'];
              $formEmail = $_POST['formEmail'];
              $formPass = $_POST['formPass'];
              $formPass = better_crypt($formPass);

              $checkUser = $con->prepare("SELECT * FROM users WHERE userName=?");
              $checkUser->bind_param("s", $formName);
              $checkUser->execute();
              $checkUser->store_result();
              if($checkUser->num_rows > 0) {
    ?>
    <p class="alert">that username is already taken, try again...</p>
    <?php
                redirect("./create.php?a=user");
              } else {
                $createUser = $con->prepare("INSERT INTO users(userName,userFirst,userLast,userEmail,userPass) VALUES(?,?,?,?,?)");
                  $createUser->bind_param("sssss", $formName,$formFirst,$formLast,$formEmail,$formPass);
                  if($createUser->execute()) {
    ?>
      <p class="alert">user successfully created, redirecting...</p>
    <?php
                    redirect("./admin.php");
                  } else {
    ?>
      <p class="alert">user unsuccessfully created, try again...</p>
    <?php
                    redirect("./create.php?a=user");
                  };
                  $createUser->close();
              };
              $checkUser->close();
            } else {
    ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
              redirect("./");
            };
          } else if($formType == "qn") {
//CREATE QUESTIONNAIRE
            $formName = strtolower($_POST['formName']);
            $formAdmin = $_POST['formAdmin'];
            $formPerms = $_POST['formPerms'];

            $checkQN = $con->prepare("SELECT * FROM categories WHERE categoryName=?");
            $checkQN->bind_param("s", $formName);
            $checkQN->execute();
            $checkQN->store_result();
            if($checkQN->num_rows > 0) {
    ?>
    <p class="alert">that questionnaire title is already taken, try again...</p>
    <?php
              redirect("./create.php?a=qn");
            } else {
              $createQN = $con->prepare("INSERT INTO categories(categoryName,categoryPerms,categoryAdmin) VALUES(?,?,?)");
                $createQN->bind_param("sii", $formName,$formAdmin,$formPerms);
                if($createQN->execute()) {
    ?>
      <p class="alert">questionnaire successfully created, redirecting...</p>
    <?php
                  redirect("./admin.php");
                } else {
    ?>
      <p class="alert">questionnaire unsuccessfully created, try again...</p>
    <?php
                  redirect("./create.php?a=qn");
                };
                $createQN->close();
            };
            $checkQN->close();
          } else {
//INVALID CREATION
    ?>
    <p class="alert">a valid creation type is required, redirecting...</p>
    <?php
            redirect("./admin.php");
          };
        } else {
    ?>
    <p class="alert">a creation type is required, redirecting...</p>
    <?php
          redirect("./");
        };
      } else if($action == "promote") {
//PROMOTE
        if($_SESSION['q_userPerms'] > 3) {
          if(isset($_GET['id'])) {
            $userID = $_GET['id'];

            $getPerms = $con->prepare("SELECT userPerms FROM users WHERE userID=? AND userPerms >= 0 AND userPerms < 4");
            $getPerms->bind_param("i", $userID);
            $getPerms->execute();
            $getPerms->store_result();
            $getPerms->bind_result($userPerms);
            while($getPerms->fetch()) {
              if($userPerms == 0) {
                $promoteQuery = "UPDATE users SET userPerms=1 WHERE userID=?";
              } else if($userPerms == 1) {
                $promoteQuery = "UPDATE users SET userPerms=4 WHERE userID=?";
              } else {
    ?>
    <p class="alert">Unable to read user Permissions, try again...</p>
    <?php
                redirect("./admin.php");
              };

              if($userPerms == 0 || $userPerms == 1) {
                $promote = $con->prepare($promoteQuery);
                $promote->bind_param("i", $userID);
                if($promote->execute()) {
    ?>
    <p class="alert">User Successfully Promoted, redirecting...</p>
    <?php
                  redirect("./admin.php");
                } else {
    ?>
    <p class="alert">User unSuccessfully Promoted, try again...</p>
    <?php
                  redirect("./admin.php");
                };
                $promote->close();
              };
            };
            $getPerms->close();
          } else {
    ?>
    <p class="alert">a user id is required, redirecting...</p>
    <?php
            redirect("./admin.php");
          };
        } else {
    ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
          redirect("./");
        };
      } else if($action == "demote") {
//DEMOTE
        if($_SESSION['q_userPerms'] > 3) {
          if(isset($_GET['id'])) {
            $userID = $_GET['id'];

            $getPerms = $con->prepare("SELECT userPerms FROM users WHERE userID=? AND userPerms > 0 AND userPerms < 5");
            $getPerms->bind_param("i", $userID);
            $getPerms->execute();
            $getPerms->store_result();
            $getPerms->bind_result($userPerms);
            while($getPerms->fetch()) {
              if($userPerms == 1) {
                $demoteQuery = "UPDATE users SET userPerms=0 WHERE userID=?";
              } else if($userPerms == 4) {
                $demoteQuery = "UPDATE users SET userPerms=1 WHERE userID=?";
              } else {
    ?>
    <p class="alert">Unable to read user Permissions, try again...</p>
    <?php
                redirect("./admin.php");
              };

              if($userPerms == 1 || $userPerms == 4) {
                $demote = $con->prepare($demoteQuery);
                $demote->bind_param("i", $userID);
                if($demote->execute()) {
    ?>
    <p class="alert">User Successfully Demoted, redirecting...</p>
    <?php
                  redirect("./admin.php");
                } else {
    ?>
    <p class="alert">User unSuccessfully Demoted, try again...</p>
    <?php
                  redirect("./admin.php");
                };
                $demote->close();
              };
            };
            $getPerms->close();
          } else {
    ?>
    <p class="alert">a user id is required, redirecting...</p>
    <?php
            redirect("./admin.php");
          };
        } else {
    ?>
    <p class="alert">you do not have permission to view this page, redirecting...</p>
    <?php
          redirect("./");
        };
    } else if($action == "edit") {
      if(isset($_POST['formType'])) {
        $formType = $_POST['formType'];

        if($formType == "user") {

        } else {
    ?>
    <p class="alert">a valid edit type is required, redirecting...</p>
    <?php
          redirect("./");
        };
      } else {
    ?>
    <p class="alert">an edit type is required, redirecting...</p>
    <?php
        redirect("./");
      };
    } else {
//INVALID ACTION
    ?>
    <p class="alert">a valid action is required, redirecting...</p>
    <?php
        redirect("./");
      };
    } else {
//NO ACTION
    ?>
    <p class="alert">an action is required, redirecting...</p>
    <?php
      redirect("./");
    };
    ?>
  </div>
</body>
</html>
