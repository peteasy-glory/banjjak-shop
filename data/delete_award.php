<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];

$sql = "delete from tb_award where customer_id = '".$user_id."' and photo = '".$_GET['photo']."';";
$result = mysqli_query($connection, $sql);
$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$s3->deleteFileToS3(substr($_GET['photo'], 1));

?>

<script>
location.href = '../shop_management';
</script>
