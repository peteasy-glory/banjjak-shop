<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];

$sql = "delete from tb_working_region where customer_id = '".$user_id."' and region_id = '".$_POST['id']."';";
//echo $sql;
$result = mysqli_query($connection, $sql);

?>
