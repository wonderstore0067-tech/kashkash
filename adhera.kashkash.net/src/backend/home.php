<?php
session_start();
require('./db_conn.php');

$data = json_decode(file_get_contents("php://input"), true);

$stmtTransactions = $conn->prepare("SELECT COUNT(*) FROM transactions");
if (!$stmtTransactions) {
  die("Error preparing transactions statement: " . $conn->error);
}
if (!$stmtTransactions->execute()) {
  die("Error executing transactions statement: " . $stmtTransactions->error);
}

$stmtTransactions->bind_result($totalTransactions);
$stmtTransactions->fetch();
$stmtTransactions->close();

$stmtUsers = $conn->prepare("SELECT COUNT(*) FROM users");
if (!$stmtUsers) {
  die("Error preparing users statement: " . $conn->error);
}
if (!$stmtUsers->execute()) {
  die("Error executing users statement: " . $stmtUsers->error);
}

$stmtUsers->bind_result($totalUsers);
$stmtUsers->fetch();
$stmtUsers->close();

$stmtUsers = $conn->prepare("SELECT SUM(amount) FROM transactions");
if (!$stmtUsers) {
  die("Error preparing users statement: " . $conn->error);
}
if (!$stmtUsers->execute()) {
  die("Error executing users statement: " . $stmtUsers->error);
}

$stmtUsers->bind_result($totalAmount);
$stmtUsers->fetch();
$stmtUsers->close();

$id = $data['data']['id'];

$stmtUsers = $conn->prepare("SELECT SUM(t.amount) AS my_debit 
            FROM transactions AS t
            JOIN users AS r ON t.receiver_account = r.account_id
            WHERE r.id = $id");

if (!$stmtUsers) {
  die("Error preparing users statement: " . $conn->error);
}
if (!$stmtUsers->execute()) {
  die("Error executing users statement: " . $stmtUsers->error);
}

$stmtUsers->bind_result($myDebit);
$stmtUsers->fetch();
$stmtUsers->close();

$stmtUsers = $conn->prepare("SELECT SUM(t.amount) AS my_debit 
            FROM transactions AS t
            JOIN users AS r ON t.sender_account = r.account_id
            WHERE r.id = $id");

if (!$stmtUsers) {
  die("Error preparing users statement: " . $conn->error);
}
if (!$stmtUsers->execute()) {
  die("Error executing users statement: " . $stmtUsers->error);
}

$stmtUsers->bind_result($myCredit);
$stmtUsers->fetch();
$stmtUsers->close();

// Close the database connection
$conn->close();

// Create an associative array to hold the total number of transactions and users
$data = array(
  'totalTransactions' => $totalTransactions,
  'totalUsers' => $totalUsers,
  'totalAmount' => $totalAmount,
  'myDebit' => $myDebit,
  'myCredit' => $myCredit
);

// Return the data as JSON response to the frontend
echo json_encode($data);

?>