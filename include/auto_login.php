<?php

		if($_COOKIE["user_hash"] != "" && $_COOKIE["auto_login_uid"]){
			$login_sql = "select * from tb_customer where id = '".$_COOKIE["auto_login_uid"]."' and enable_flag = 1";
			$res = mysqli_query($connection, $login_sql);
			$row = mysqli_fetch_array($res);
			
            //탈퇴 회원 일때
			if($row["enable_flag"] != 1){
				//쿠키 전체 삭제
				$past = time() - 3600;
				foreach ($_COOKIE as $key => $value)
				{
					setcookie($key, '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
				}

				session_destroy();
			}else{
				$is_pc = (isset($_POST['is_pc']) && $_POST['is_pc'] === "true" ) ? true : false;

				$id = $row["id"];
				$artist_flag = $row["artist_flag"];
				$my_shop_flag = $row["my_shop_flag"];

				//정상 회원
				$_SESSION['gobeauty_user_id'] = $id;
				$_SESSION['gobeauty_user_nickname'] = $row["nickname"];
				$_SESSION['is_pc'] = $is_pc;
				$_SESSION['my_shop_flag'] = $my_shop_flag;
//                if(!$_SESSION['is_token'] || $_SESSION['is_token'] != "0"){ 
                    $_SESSION['is_token'] = "1";
//                }
//				$_SESSION['my_shop_flag'] = $row[my_shop_flag];

				if($artist_flag == "1"){
					$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$id}' AND del_yn = 'N'";
					$artist_result = mysqli_query($connection, $artist_sql);
 
					if($artist_data = mysqli_fetch_object($artist_result)){
						$_SESSION['artist_flag'] = true;
						$_SESSION['shop_user_id'] = $artist_data->customer_id;
						$_SESSION['shop_user_nickname'] = $artist_data->name;
					}
				}

				cookie_save($id,$master_key_name);

			}
		}
?>
