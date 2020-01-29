<?php

//prevent user from accessing this page unless if they signed up and submitted
if (isset($_POST['signup-submit'])) {
  //import database handler
  require 'dbh.inc.php';

  //fetch information from the form (see signup.php)
  $username = $_POST['uid'];
  $email = $_POST['mail'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];

  //ERROR HANDLING (can add soooo many)
  //check if fields are empty
  if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)){
    //create error message, send them back to signup sheet, with previous info typed in
    header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
    exit();//stop signup.inc.php to continue on
  }
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$username)) {
    //invalid username AND email
    header("Location: ../signup.php?error=invalidmailuid");
    exit();
  }
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //invalid email
    header("Location: ../signup.php?error=invalidmail&uid=".$username);
    exit();
  }
  else if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
    //check for valid Username
    header("Location: ../signup.php?error=invaliduid&mail=".$email);
    exit();
  }
  else if ($password !== $passwordRepeat) {
    //passwords dont match
    header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
    exit();
  }
  else {
    //username is already taken
    //query sql db, we use ? as a place holder
    //some input can destroy db, thus must parse first before querying
    $sql = "SELECT uidUsers FROM users WHERE uidUsers=?";

    //check if input is not malicious to destroy data base
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../signup.php?error=sqlerror");
      exit();
    }
    else {
      //if no error triggered, query if username already exists
      //fillin place holder ?
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      //when we get our query result back, we count how many rows show up
      $resultCheck = mysqli_stmt_num_rows($stmt);
      if ($resultCheck > 0) {
        //user already exists
        header("Location: ../signup.php?error=usertaken&mail=".$email);
        exit();
      }
      else {
        //FINALLY IF NO ERRORS, REGISTER USER
        $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        //prep and parse info to make sure it doesnt break db
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup.php?error=sqlerror");
          exit();
        }
        else {
          //fill in place holders ???
          //hash pasword for safety
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
          mysqli_stmt_bind_param($stmt, "sss", $username,$email,$hashedPwd);
          mysqli_stmt_execute($stmt);


          //user has signed up SUCCESS!
          //automatically log in the user and start a new session
          $sql = "SELECT * FROM users WHERE uidUsers =?";
          $stmt = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt,$sql);
          mysqli_stmt_bind_param($stmt, "s",$username);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          session_start();
          $_SESSION['userId'] = $row['idUsers'];
          $_SESSION['userUid'] = $row['uidUsers'];

          header("Location: ../index.php?signup=success");
          exit();
        }
      }
    }
  }
}
else {
  //illegal access to this php page w/o sign up
  header("Location: ../signup.php");
  exit();
}
?>
