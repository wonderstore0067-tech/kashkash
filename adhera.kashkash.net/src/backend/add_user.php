<?php

require('./db_conn.php');
// Retrieve the form data
$data = json_decode(file_get_contents("php://input"), true);



// Prepare and execute the SQL statement to insert the data into the database
$stmt = $conn->prepare("INSERT INTO users (account_id, private_key, name) VALUES (?, ?, ?)");
if (!$stmt) {
  die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("sss", $data['newAccountId'], $data['newAccountPrivateKey'], $data['name']);
if (!$stmt->execute()) {
  die("Error executing statement: " . $stmt->error);
}

// Close the database connection
$stmt->close();
$conn->close();

// Return a response to the frontend
$response = array("message" => "Data inserted successfully", "status" => "success");
echo json_encode($response);

?>
