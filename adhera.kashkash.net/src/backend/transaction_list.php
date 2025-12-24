<?php

require('./db_conn.php');

$query = 'SELECT t.*, r.name AS receiver_name, s.name AS sender_name
          FROM transactions AS t
          JOIN users AS r ON t.receiver_account = r.account_id
          LEFT JOIN users AS s ON t.sender_account = s.account_id';

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