<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];

$sql = "update tb_shop set front_image = '".$_POST['path']."' where customer_id = '".$user_id."';";

$result = mysqli_query($connection, $sql);
if ($result)
{
	echo "대문 사진으로 적용하였습니다";
}
else
{
	echo "대문 사진 변경에 실패했습니다.";
}

?>
