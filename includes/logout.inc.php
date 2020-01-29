<?php
session_start();
//takes all session variables and deletes their values
session_unset();
//destroy currently running sessions
session_destroy();
header("Location: ../index.php");
 ?>
