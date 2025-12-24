<?php

// Include the database connection file
require('./db_conn.php');

// Check if the request method is DELETE
// if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Retrieve the user ID from the URL query string
    $userId = $_GET['id'];

    // Prepare and execute the SQL statement to delete the user record
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // If the deletion is successful, return a success response
        $response = array("status" => "success", "message" => "User deleted successfully");
    } else {
        // If there is an error during deletion, return an error response
        $response = array("status" => "error", "message" => "Failed to delete user");
    }

    // Close the prepared statement
    $stmt->close();
// } else {
//     // If the request method is not DELETE, return an error response
//     $response = array("status" => "error", "message" => "Invalid request method");
// }

// Close the database connection
$conn->close();

// Return the response as JSON
header("Content-Type: application/json");
echo json_encode($response);
?>
