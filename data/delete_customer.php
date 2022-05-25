<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];
$cellphone = $_POST['cellphone'];

$sql = "update tb_payment_log set data_delete = '1' where artist_id = '{$user_id}' and cellphone = '{$cellphone}'";
$result = mysqli_query($connection, $sql);
if($result) {
    echo "ok";
}
?>
