<?php

require('./db_conn.php');
// Retrieve the form data

$data = json_decode(file_get_contents("php://input"), true);

$transaction_id = $data[0];
$receiver_account = $data[1];
$status = $data[2];
$sender_account = $data[3];
$amount = $data[4];
$sender_id = $data[5];

// Prepare and execute the SQL statement to insert the data into the database
$stmt = $conn->prepare("INSERT INTO transactions (sender_id, sender_account, receiver_account, transaction_id, amount, status) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
  die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ssssss", $sender_id, $sender_account, $receiver_account, $transaction_id, $amount, $status);
if (!$stmt->execute()) {
  die("Error executing statement: " . $stmt->error);
}

// Close the database connection
$stmt->close();
$conn->close();

// Return a response to the frontend
$response = array("message" => "Transaction Successful", "status" => "success");
echo json_encode($response);

?>
