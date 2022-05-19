<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$user_id = $_SESSION['gobeauty_user_id'];

	$pet_type = $_POST["pet_type"];

	$sql = "select name from tb_pet_type where type = '".$pet_type."' order by type_seq asc;";
	$result = mysqli_query($connection, $sql);
	for ($index=0;$top_datas = mysqli_fetch_object($result);$index++) {
		if ($index != 0) {
			echo ",";
		}
		echo trim($top_datas->name);
	}

// closeDB();
?>

