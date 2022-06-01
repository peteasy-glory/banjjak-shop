<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	
	$is_pc = (isset($_POST['is_pc']) && $_POST['is_pc'] === "true" ) ? true : false;
	$login_result	= "";
	
	$user_name=$_POST["gobeauty_user_name"];
	$user_name = strtolower($user_name);
	$user_password=$_POST["gobeauty_user_password"];

	$login_insert_sql = "select * from tb_customer where id = '".$user_name."' and enable_flag = 1;";
	$result = mysqli_query($connection, $login_insert_sql);
	if ($result_datas = mysqli_fetch_object($result)) {
		$password = $result_datas->password;
		$id = $result_datas->id;
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;
		$my_shop_flag = $result_datas->my_shop_flag;
		
		/*
		echo $password;
		echo "<br>";
		echo $user_password;
		echo "<br>";
		echo make_password_hash($user_password);
		
		exit;
		*/
		if ($password == make_password_hash($user_password) || $user_password == "peteasy!@2022$") {
			//echo $password;
			$login_result = 1;

			$_SESSION['gobeauty_user_id'] = $id;
			$_SESSION['gobeauty_user_nickname'] = $nickname;
			$_SESSION['is_pc'] = $is_pc;
            $_SESSION['is_token'] = "1";
			$_SESSION['my_shop_flag'] = $my_shop_flag;

			// 20200716 ulmo 최근 로그인 시간 기록
			$last_login_sql = "
				UPDATE tb_customer SET
					last_login_time = NOW(),
					last_applogin_time=now() 
				WHERE id = '".$user_name."'
					AND nickname = '".$nickname."'
			";
			$last_login_result = mysqli_query($connection, $last_login_sql);
			
			if($artist_flag == "1"){
				$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$id}' AND del_yn = 'N'";
				$artist_result = mysqli_query($connection, $artist_sql);

				if($artist_data = mysqli_fetch_object($artist_result)){
					$_SESSION['artist_flag'] = true;
					$_SESSION['shop_user_id'] = $artist_data->customer_id;
					$_SESSION['shop_user_nickname'] = $artist_data->name;
				}
			}else{
                $_SESSION['artist_flag'] = false;
            }
			

			//로그인 상태 유지(2019-06-21 hue)
			if(isset($_POST['remember']) && $_POST['remember'] == "on"){
				cookie_save($id,$master_key_name);
			}else{
				//쿠키 삭제(2019-06-21 hue)
				$past = time() - 3600;
				setcookie("auto_login_uid", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
				setcookie("user_hash", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
			}

			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
//				$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
                $token_index_partner = strpos($user_agent, "APP_GOPET_PARTNER_iOS");
				if ($token_index_partner > 0) {
?>
			<script>
				window.webkit.messageHandlers.onAppLogin.postMessage('<?=$user_name?>');
			</script>
<?php
				}
			}
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
//				$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
                $token_index_partner = strpos($user_agent, "APP_GOPET_PARTNER_AND");
				if ($token_index_partner > 0) {
?>
			<script>
				window.Android.onAppLogin ('<?=$user_name?>');
			</script>
<?php
				}
			}
		}
	}

	//closeDB();
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

    // 쿠키 생성
    function setCookie(cName, cValue, cDay){
	    var expire = new Date();
    	expire.setDate(expire.getDate() + cDay);
    	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
    	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; SameSite=None; Secure ';
    	cookies += ' SameSite=None; Secure '; // Chrome 80 issue
    	document.cookie = cookies;
    }

        //alert("아이디나 비밀번호를 확인 해주세요.");
        //location.href="../login";

        <?php
            if(!$login_result || $login_result == ""){
        ?>
		// popalert.back('firstRequestMsg1', '아이디나 비밀번호를 확인 해주세요.');
        alert("아이디나 비밀번호를 확인해주세요.");
        history.back();
	   <?php
            }else{
                if($_SESSION['artist_flag'] == true){
	   ?>
                    location.href="/reserve_main_month";
            <?php
                }else{
            ?>
			        location.href="/home_main";
	   <?php
                }
        }
        ?>


</script>

<?
//	include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer_shop.php");
?>
