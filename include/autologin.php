<?php
	include "configure.php";
	include "db_connection.php";
	include "session.php";

	$login_id = $_POST['login_id'];
	//echo $login_id;
	$token = $_POST['token'];
	//echo $token;

	// 20210610 by migo - native app 에서 인증
	//1. 로그인
	$login_insert_sql = "select * from tb_customer where id = '".$login_id."' and enable_flag = 1;";
	$result = mysql_query($login_insert_sql);
	if ($result_datas = mysql_fetch_object($result)) {
		$user_id = $result_datas->id;
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;

		$_SESSION['gobeauty_user_id'] = $user_id;
		$_SESSION['gobeauty_user_nickname'] = $nickname;

		$_r = "OK";	// 견주 구분

		// 2. 펫샵 체크
		if($artist_flag == "1"){	
			$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$user_id}' AND del_yn = 'N'";
			$artist_result = mysql_query($artist_sql);

			if($artist_data = mysql_fetch_object($artist_result)){
				$_SESSION['artist_flag'] = true;
				$_SESSION['shop_user_id'] = $artist_data->customer_id;
				$_SESSION['shop_user_nickname'] = $artist_data->name;

				$_r = "OK_P";	// 펫샵 구분
			}
		}
		
		//echo "OK";
		echo $_r;
    } else {
		echo "FAIL";
	}
	//$backurl = $_POST['backurl'];
?>
