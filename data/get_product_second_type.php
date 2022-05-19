<?php
	include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
	include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$user_id = $_SESSION['gobeauty_user_id'];
	$top_region = $_POST["product_first_type"];

	$sql = "select * from tb_config_product_type where first_type = '".$top_region."' and enable_flag = '1';";
	$result = mysqli_query($connection,$sql);
	for ($index=0;$top_datas = mysqli_fetch_object($result);$index++) {
		if ($index != 0) {
			echo ",";
		}
		echo $top_datas->second_type;
	}
?>

