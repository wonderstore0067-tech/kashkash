<?php
require('./db_conn.php');

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$name = $data['name'];
$email = $data['email'];
$account_id = $data['newAccountId'];
$private_key = $data['newAccountPrivateKey'];

$query = "UPDATE users SET name = '$name', email= '$email', account_id= '$account_id', private_key = '$private_key' WHERE id='$id'";

if(mysqli_query($conn, $query)){
    $response = array("message" => "Data updated successfully", "status" => "success");
    echo json_encode($response);
}
else{
    $response = array("message" => "Please ty again", "status" => "error");
    echo json_encode($response);
}


?>