<?php

session_start();
require_once('./db_conn.php');


$data = json_decode(file_get_contents("php://input"), true);
$email = mysqli_real_escape_string($conn, $data['email']);
$password = $data['password']; 

$query = "SELECT * FROM users WHERE email = '$email'";
$run = mysqli_query($conn, $query);

if (mysqli_num_rows($run) > 0) {
  $row = mysqli_fetch_assoc($run);
  $hashedPassword = $row['password'];

  if (password_verify($password, $hashedPassword)) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['is_admin'] = $row['is_admin'];

    $response = array("message" => "Login successfully", "status" => "success", "data" => array("id" => $row['id'], "email" => $email, "is_admin" => $row['is_admin']));
    echo json_encode($response);
  } else {
    $response = array("message" => "Email or Password incorrect", "status" => "error");
    echo json_encode($response);
  }
} else {
  $response = array("message" => "Email or Password incorrect", "status" => "error");
  echo json_encode($response);
}

?>