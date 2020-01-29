<?php
//DATABASE HANDLER PHP
//important variables
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "loginsystem";

//connects to our database
$conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBName);

//if varible conn does not connect
if (!$conn){
  //cut off connection
  die("Connection failed: ".mysqli_connect_error());
}


?>
