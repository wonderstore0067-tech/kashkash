<?php
// Connect to the database
$servername = "localhost";
$username = "hadera";
$password = "Faisal@321";
$dbname = "hadera_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed");
}


?>