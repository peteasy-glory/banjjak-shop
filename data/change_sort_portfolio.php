<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_POST['user_id'];
$path = $_POST['path'];
$sort_number = $_POST['sort_number'];

$sql = "UPDATE tb_portfolio SET sort_number = '{$sort_number}' 
        WHERE customer_id = '{$user_id}' 
        AND image = '{$path}';";
        
echo $sql;
// error_log('----- $sql : '.$sql);
if($result = mysqli_query($connection, $sql)){
    $check_delete_db = "true";
    return true;
}else{
    return false;
}
?>
