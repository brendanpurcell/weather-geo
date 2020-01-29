<?php

//login button was clicked
if (isset($_POST['login-submit'])){
  require 'dbh.inc.php';

  //pull in form information from header.php
  $mailuid = $_POST['mailuid'];
  $password = $_POST['pwd'];

  //check if any of the fields are emptyfields
  if (empty($mailuid) && empty($password)){
    header("Location: ../index.php?error=emptyfields");
    exit();
  }
  else {
    //actually log user in
    //check if the information (username/email) maches with an existing user
    $sql = "SELECT * FROM users WHERE uidUsers =? OR emailUsers=?;";
    $stmt = mysqli_stmt_init($conn);
    //check if stmt query has an error or not
    if (!mysqli_stmt_prepare($stmt,$sql)){
      header("Location: ../index.php?error=sqlerror");
      exit();
    }
    else {
      //fill in ??
      mysqli_stmt_bind_param($stmt, "ss",$mailuid,$mailuid);
      //execute order
      mysqli_stmt_execute($stmt);
      //raw data from database
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)){
        //grab password from database and check if it matches with what user entered
        $pwdCheck = password_verify($password,$row['pwdUsers']);
        if ($pwdCheck == false ){
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
        else if ($pwdCheck == true) {
          //right user name / email , and password
          //create session denoting someone is logged in by creating a global variable that represents a logged in user
          session_start();
          $_SESSION['userId'] = $row['idUsers'];
          $_SESSION['userUid'] = $row['uidUsers'];

          //return user back with success!
          header("Location: ../index.php?login=success");
          exit();
        }
        else {
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
      }
      else {
        //no user that matches username / email
        header("Location: ../index.php?error=nouser");
        exit();
      }
    }

  }
}
else {
  header("Location: ../index.php");
  exit();
}
?>
