<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$user_id = $_SESSION['gobeauty_user_id'];

	$top_region = $_POST["top_region"];
	$middle_region = $_POST["middle_region"];

	//$sql = "select id,bottom from tb_region where top = '".trim($top_region)."' and middle = '".trim($middle_region)."' and open_flag = true;";
	$sql = "select id,bottom from tb_region where top = '".trim($top_region)."' and middle = '".trim($middle_region)."';";
//	echo $sql;
	$result = mysqli_query($connection, $sql);
	while ($top_datas = mysqli_fetch_object($result)) {
		$id = $top_datas->id;
		$bottom = $top_datas->bottom;
		


		$n_sql = "select * from tb_working_region where customer_id = '".$user_id."' and region_id = $id;";
		$n_result = mysqli_query($connection, $n_sql);
		$checked	= "";
		if ($n_datas = mysqli_fetch_object($n_result)) {
			$checked = " checked ";
		}

		echo '<div class="grid-layout-cell grid-3"><label class="form-toggle-box middle"><input type="checkbox" name="chk" value="'.$id.'" '.$checked.'><em>'.$bottom.'</em></label></div>';
	}


