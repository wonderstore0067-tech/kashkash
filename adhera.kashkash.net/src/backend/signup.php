<?php

require('./db_conn.php');

$data = json_decode(file_get_contents("php://input"), true);

    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
    if(mysqli_query($conn, $sql)){
        $response = array("message" => "Registration successful", "status" => "success");
        echo json_encode($response);
    }
    else{
        $response = array("message" => "Something went wrong.", "status" => "error");
        echo json_encode($response);
      }

?>