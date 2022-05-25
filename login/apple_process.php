<?php
	include "../include/top.php";

	$r_email = ($_GET["email"] && $_GET["email"] != "")? $_GET["email"] : "";

	$sql = "
		SELECT *
		FROM tb_customer
		WHERE id = '".$r_email."'
	"; // one more chk
	$result = mysql_query($sql);
	$cnt = mysql_num_rows($result);
	$row = mysql_fetch_assoc($result);
	
	if($cnt > 0){
		$_SESSION['gobeauty_user_id'] = $row["id"];
		$_SESSION['gobeauty_user_nickname'] = $row["nickname"];

		// 20200716 ulmo 최근 로그인 시간 기록
		$last_login_sql = "
			UPDATE tb_customer SET
				last_login_time = NOW()
			WHERE id = '".$r_email."'
				AND nickname = '".$row["nickname"]."'
		";
		$last_login_result = mysql_query($last_login_sql);

		if($row["artist_flag"] == "1"){
			$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '".$r_email."' AND del_yn = 'N'";
			$artist_result = mysql_query($artist_sql);

			if($artist_data = mysql_fetch_assoc($artist_result)){
				$_SESSION['artist_flag'] = true;
				$_SESSION['shop_user_id'] = $artist_data["customer_id"];
				$_SESSION['shop_user_nickname'] = $artist_data["name"];
			}
		}

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ($user_agent) {
			$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
			if ($token_index > 0) {
				?><script>
					window.webkit.messageHandlers.onAppLogin.postMessage('<?=$r_email ?>');
				</script><?php
			}
			$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
			if ($token_index > 0) {
				?><script>
					window.Android.onAppLogin('<?=$r_email ?>');
				</script><?php
			}
		}

?>
		<script>
			// 쿠키 가져오기
			function getCookie(cName) {
				cName = cName + '=';
				var cookieData = document.cookie;
				var start = cookieData.indexOf(cName);
				var cValue = '';
				if(start != -1){
					start += cName.length;
					var end = cookieData.indexOf(';', start);
					if(end == -1)end = cookieData.length;
					cValue = cookieData.substring(start, end);
				}
				return unescape(cValue);
			}

			if(getCookie("order_step") == "1"){ // 결제페이지
				location.href="<?=$item_directory ?>/item_payment.php?no="+getCookie("order_num");
			}else if(getCookie("order_step") == "2"){ // 조회
				location.href="../chkodr/?no="+getCookie("order_num");
			}else if(getCookie("order_step") == "3"){ // 장바구니
				location.href="<?=$item_directory ?>/item_cart.php?no="+getCookie("order_num");
			}else{
				<?php if($my_shop_flag == "1"){ ?>
					<?php if($pc == "1"){ ?>
						location.href="<?=$shop_directory?>/index_pc_new.php";
					<?php }else{ ?>
						location.href="<?=$shop_directory?>/index.php";
					<?php } ?>
				<?php }else{ ?>
					location.href="<?=$mainpage_directory?>/index.php";
				<?php } ?>
			}
		</script>
<?php		
	}else{
?>
	<script>
		$.MessageBox({
			buttonDone  : "확인",
			message  : "<center>로그인이 되지 않았습니다.<br/>잠시 후 다시 시도해주세요.</center>"
		}).done(function(){
			location.href = "/pet/login/index.php";
		});
	</script>
<?php
	}

	include "../include/bottom.php";
?>