<?php

require('./db_conn.php');

$id = $_GET['id'];

$query = "select * from users where id = '$id'";
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