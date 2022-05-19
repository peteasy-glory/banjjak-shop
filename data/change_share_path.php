<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
//include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$path = $_POST["path"];

$code = $_POST["code"];
$data = array();

if($code == "encode"){
    $sql = "CALL procSimpleShortUrl_Insert('".$path."') ";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $data[] = $row;
}else{
    $sql = "select * from tb_short_url where idx = '".$path."'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>