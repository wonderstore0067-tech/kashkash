<?php

require('./db_conn.php');

$query = "SELECT c.*, u.name AS name
            FROM configurations c
            LEFT JOIN users u ON c.added_by = u.id";

$run = mysqli_query($conn,$query);

  while($row=mysqli_fetch_assoc($run))
  {
      $data[] = $row;
  }

  if(isset($data))
  {
    echo json_encode($data);  
  }
  else{
    $response = array("message" => "Something went wrong.", "status" => "error");
    echo json_encode($response);
  }


?>