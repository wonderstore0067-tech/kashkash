<?php
// api/getSession.php

session_start();
$_SESSION['user_id'] = 45;

$sessionId = $_SESSION['user_id'];
// Check if the session variable exists
if (isset($_SESSION['user_id'])) {
  $sessionId = $_SESSION['user_id'];
  // Return the session value as JSON response
  echo json_encode($sessionId);
} else {
  // Return an error response if the session variable doesn't exist
  echo json_encode(null);
}
?>
