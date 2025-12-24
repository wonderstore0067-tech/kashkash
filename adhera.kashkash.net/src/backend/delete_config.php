<?php

// Include the database connection file
require('./db_conn.php');

    $coinId = $_GET['id'];
    $active = $_GET['status'];
    
    $query = "UPDATE configurations SET active = '$active'  WHERE id='$coinId'";

    if(mysqli_query($conn, $query)){
        $response = array("message" => "Coin status updated successfully", "status" => "success");
        echo json_encode($response);
    }
    else{
        $response = array("message" => "Please try again", "status" => "error");
        echo json_encode($response);
    }


    // $stmt = $conn->prepare("DELETE FROM configurations WHERE id = ?");
    // $stmt->bind_param("i", $coinId);

    // if ($stmt->execute()) {
    //     $response = array("status" => "success", "message" => "Coin deleted successfully");
    // } else {
    //     $response = array("status" => "error", "message" => "Failed to delete coin");
    // }

    // $stmt->close();

// $conn->close();

// header("Content-Type: application/json");
// echo json_encode($response);
?>
