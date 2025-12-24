<?php

require('./db_conn.php');

// $data = json_decode(file_get_contents("php://input"), true);

// $token_name = $data['tokenName'];
// $token_symbol = $data['tokenSymbol'];
// $initial_supply = $data['initialSupply'];
// $account_id = $data['myOperatorId'];
// $private_key = $data['myOperatorKey'];
// $public_key = $data['myPublicKey'];
// $hex_private_key = $data['hexPrivateKey'];
// $added_by = $data['user_id'];


// $file = $_FILES['selectedImage']['name'];
// $file_loc = $_FILES['selectedImage']['tmp_name'];
// $folder="uploads/".$file;

// move_uploaded_file($file_loc,$folder);



// $target_dir = "images/uploads/";
// $target_file = $target_dir . basename($_FILES["pimage"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// $check = getimagesize($_FILES["pimage"]["tmp_name"]);
//     if($check !== false) 
//     {
//         //echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//         if (file_exists($target_file)) {
//           echo "<script>alert('File Already exist..!')</script>";
//           $uploadOk = 0;
      
//       }
//       // Check file size
//       if ($_FILES["pimage"]["size"] > 500000) {
//         echo"<script>alert('File size is large..!')</script>";
//           $uploadOk = 0;
//       }
//       // Allow certain file formats
//       if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//       && $imageFileType != "gif" ) {
//         echo "<script>alert('Only JPG,PNG,JPEG allowed..!')</script>";
//           $uploadOk = 0;
//       }
//       // Check if $uploadOk is set to 0 by an error
//       if ($uploadOk == 0) {
//         //echo "<script>alert('File not uploaded..!')</script>";
//       // if everything is ok, try to upload file
//       } 
//       else {
//           if (move_uploaded_file($_FILES["pimage"]["tmp_name"], $target_file)) {



    // $token_name = $data['tokenName'];
// $token_symbol = $data['tokenSymbol'];
// $initial_supply = $data['initialSupply'];
// $account_id = $data['myOperatorId'];
// $private_key = $data['myOperatorKey'];
// $public_key = $data['myPublicKey'];
// $hex_private_key = $data['hexPrivateKey'];
// $added_by = $data['user_id'];

    $token_name = $_POST['tokenName'];
    $token_symbol = $_POST['tokenSymbol'];
    $initial_supply = $_POST['initialSupply'];
    $account_id = $_POST['myOperatorId'];
    $private_key = $_POST['myOperatorKey'];
    $public_key = $_POST['myPublicKey'];
    $hex_private_key = $_POST['hexPrivateKey'];
    $added_by = $_POST['user_id'];
    $active = 1;

    // Check if the file is uploaded successfully
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['image'];

        $newFilename = uniqid("image_") . "_" . time();

        // Get the file extension
        $extension = pathinfo($uploadedFile["name"], PATHINFO_EXTENSION);

        // Combine the new filename and extension
        $newFilenameWithExtension = $newFilename . "." . $extension;

        // Specify the upload directory
        $uploadDirectory = "uploads/";

        // Move the uploaded file to the destination directory with the new filename
        $destination = $uploadDirectory . $newFilenameWithExtension;
        

        
        // $uploadPath = 'uploads/' . basename($uploadedFile['name']);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($uploadedFile["tmp_name"], $destination)) {

            $sql = "INSERT INTO configurations (token_name, token_symbol, initial_supply, account_id, private_key, public_key, hex_private_key, added_by, token_image, active) VALUES ('$token_name','$token_symbol','$initial_supply', '$account_id', '$private_key', '$public_key', '$hex_private_key', '$added_by', '$destination', '$active')";
            if(mysqli_query($conn, $sql)){
                $lastInsertId = mysqli_insert_id($conn); // Get the last inserted ID
                $response = array("message" => "Configuration added successfully", "status" => "success", "value" => 1, "image" => $destination, "id" => $lastInsertId);
                echo json_encode($response);
            }
            else{
                $response = array("message" => "Something went wrong.", "status" => "error", "value" => 0);
                echo json_encode($response);
            }
        }
    }
    else{
        $response = array("message" => "Something went wrong.", "status" => "error", "value" => 0);
        echo json_encode($response);
    }

?>