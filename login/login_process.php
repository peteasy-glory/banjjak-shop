<?php include "../include/top.php"; ?>

<?php
	$is_pc = $_POST['is_pc'] === "true"? true : false;
	$user_name=$_POST["gobeauty_user_name"];
	$user_name = strtolower($user_name);
	$user_password=$_POST["gobeauty_user_password"];

	$login_insert_sql = "select * from tb_customer where id = '".$user_name."' and enable_flag = 1;";
	$result = mysql_query($login_insert_sql);
	if ($result_datas = mysql_fetch_object($result)) {
		$password = $result_datas->password;
		$id = $result_datas->id;
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;
		$my_shop_flag = $result_datas->my_shop_flag;

		if ($password == make_password_hash($user_password)) {
			$login_result = 1;

			$_SESSION['gobeauty_user_id'] = $id;
			$_SESSION['gobeauty_user_nickname'] = $nickname;
			$_SESSION['is_pc'] = $is_pc;
//			$_SESSION['my_shop_flag'] = $my_shop_flag;

			// 20200716 ulmo 최근 로그인 시간 기록
			$last_login_sql = "
				UPDATE tb_customer SET
					last_login_time = NOW(),
					last_applogin_time=now() 
				WHERE id = '".$user_name."'
					AND nickname = '".$nickname."'
			";
			$last_login_result = mysql_query($last_login_sql);

			if($artist_flag == "1"){
				$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$id}' AND del_yn = 'N'";
				$artist_result = mysql_query($artist_sql);

				if($artist_data = mysql_fetch_object($artist_result)){
					$_SESSION['artist_flag'] = true;
					$_SESSION['shop_user_id'] = $artist_data->customer_id;
					$_SESSION['shop_user_nickname'] = $artist_data->name;
				}
			}

			//로그인 상태 유지(2019-06-21 hue)
			if($_POST[remember] == "on"){
				cookie_save($id,$master_key_name);
			}else{
				//쿠키 삭제(2019-06-21 hue)
				$past = time() - 3600;
				setcookie("auto_login_uid", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
				setcookie("user_hash", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
			}

			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
				$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
				if ($token_index > 0) {
?>
			<script>
				window.webkit.messageHandlers.onAppLogin.postMessage('<?=$user_name?>');
			</script>
<?php
				}
			}
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
				$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
				if ($token_index > 0) {
?>
			<script>
				window.Android.onAppLogin ('<?=$user_name?>');
			</script>
<?php
				}
			}
		}
	}

	closeDB();
?>

<script>
function tmsg(msg) {
	Command: toastr["success"](msg);
}
</script>

<script language="javascript">
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

	if(getCookie("order_step") == "1"){ // 결제
		location.href="<?=$item_directory ?>/item_payment.php?no="+getCookie("order_num");
	}else if(getCookie("order_step") == "2"){ // 조회
		location.href="../chkodr/?no="+getCookie("order_num");
	}else if(getCookie("order_step") == "3"){ // 장바구니
		location.href="<?=$item_directory ?>/item_cart.php?no="+getCookie("order_num");
	}else{
	   <?php if(!$login_result){ ?>
		$.MessageBox({
			buttonDone      : "확인",
			message         : "아이디나 비밀번호를 확인 해주세요."
		}).done(function(){
			<?php if($is_pc){ ?>
				location.href="<?=$login_directory?>/index_pc.php";
			<?php }else{ ?>
				location.href="<?=$login_directory?>/index.php";
			<?php } ?>
		});
	   <?php }else{ ?>
			<?php if($is_pc){ ?>
				location.href="<?=$shop_directory?>/index_pc_new.php";
			<?php }else{ ?>
				<?php if($my_shop_flag == "1"){ ?>
					location.href="<?=$shop_directory?>/index.php";
				<?php }else{ ?>
					location.href="<?=$mainpage_directory?>/index.php";
				<?php } ?>
			<?php } ?>
	   <?php } ?>
	}
</script>

<?php include "../include/bottom.php"; ?>
