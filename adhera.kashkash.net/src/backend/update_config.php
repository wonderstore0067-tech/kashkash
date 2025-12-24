<?php

require('./db_conn.php');

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$token_name = $data['tokenName'];
$token_symbol = $data['tokenSymbol'];
$initial_supply = $data['initialSupply'];
$token_no = $data['tokenNo'];
$account_id = $data['myOperatorId'];
$private_key = $data['myOperatorKey'];
$public_key = $data['myPublicKey'];
$hex_private_key = $data['hexPrivateKey'];

$token_type = $data['tokenType'];
$decimal = $data['decimal'];
$admin_key = $data['adminKey'];
$freeze_key = $data['freezeKey'];
$default_frauzen = $data['defaultFrauzen'];
$wipe_key = $data['wipeKey'];
$supply_manage = $data['supplyManage'];
$active = 1;

$query = "UPDATE configurations SET token_name = '$token_name', token_symbol= '$token_symbol', initial_supply = '$initial_supply', token_no = '$token_no', account_id = '$account_id', private_key = '$private_key', public_key = '$public_key', hex_private_key = '$hex_private_key', token_type = '$token_type', decimals = '$decimal', admin_key = '$admin_key', freeze_key = '$freeze_key', default_frauzen = '$default_frauzen', wipe_key = '$wipe_key', supply_manage = '$supply_manage', active = '$active'  WHERE id='$id'";

if(mysqli_query($conn, $query)){
    $response = array("message" => "Data updated successfully", "status" => "success");
    echo json_encode($response);
}
else{
    $response = array("message" => "Please try again", "status" => "error");
    echo json_encode($response);
}

?>