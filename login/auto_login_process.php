<?php include "../include/top.php"; ?>

<?php
	$autostr=$_REQUEST["autostr"];

	$login_insert_sql = "select * from tb_customer where token = '".$autostr."' and enable_flag = 1;";
	$result = mysql_query($login_insert_sql);
	if ($result_datas = mysql_fetch_object($result)) {
		$user_id = $result_datas->id;
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;

		session_start();
		$_SESSION['gobeauty_user_id'] = $user_id;
		$_SESSION['gobeauty_user_nickname'] = $nickname;

		if($artist_flag == "1"){
			$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$user_id}' AND del_yn = 'N'";
			$artist_result = mysql_query($artist_sql);

			if($artist_data = mysql_fetch_object($artist_result)){
				$_SESSION['artist_flag'] = true;
				$_SESSION['shop_user_id'] = $artist_data->customer_id;
				$_SESSION['shop_user_nickname'] = $artist_data->name;
			}
		}
	}

	closeDB();
?>
<?php include "../include/bottom.php"; ?>
