<?php
class AutoLogin {
	public $token = "";

	function make_session () {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ($user_agent) {
			// AND
			$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
	        if ($token_index > 0) {
?>
<script>
				var login_id = window.Android.onAppGetLogin();
				var token = window.Android.onAppGetToken();
				//console.log('autologin.class.php' + login_id + '--' + token );

/*
				if (login_id && login_id.trim() == 'sheepoi@naver.com') {
					if (login_id) {
						alert(login_id);
					}
					if (token) {
						alert(token);
					}
				}
*/

				var session_login_id = '<?=$_SESSION['gobeauty_user_id']?>';
				if (!session_login_id && login_id) {
					var post_data = 'login_id='+login_id+'&token='+token;
					$.ajax({
						url: '/pet/include/autologin.php',
						data: post_data,
						type: 'POST',
						success: function(data){
							//alert(data+"1");
							//location.reload();
							if(data.trim() == "OK"){
								location.replace("<?=$shop_directory ?>/index.php");
							}
						},
						error : function(xhr, status, error) {
						}
					});
				}

				if (login_id && token) {
					$.ajax({
						url: '/pet/login/android.php',	// 알림발송용 토근 저장용
						data: {
							id : login_id,
							token : token,
							is_android : 'yes'
						},
						type: 'POST',
						success: function(data){
							//if (login_id && login_id.trim() == 'ulmo26@gmail.com') {
								//alert(data.trim()+"2");
							//}
							//location.reload();
							//location.replace("/pet/shop/index.php");
							if(data.trim() == "OK_P"){	// 펫샵ID 인 경우
								if(window.location.pathname == "/pet/mainpage/"){
									location.replace("/pet/shop/index.php");
								} 
							}

							//
							if(data == "OK") { // 견주 회원
								location.replace("/pet/mainpage/index_lisa.php");
							}
						},
						error : function(xhr, status, error) {
						}
			        });
				}
</script>
<?php
/*				$this->token = substr($user_agent, $token_index+strlen("APP_GOBEAUTY_AND:"), strlen($user_agent));
				echo "<br><br>APP<br><br>";
				echo $this->token."<br>";

				if (strlen($this->token) > 0 && ($_SESSION['gobeauty_user_id'] == "" || $_SESSION['gobeauty_user_id'] == null) ) {
					//echo "<br><br>auto login<br>";
					//echo $this->token."<br>";
					$autostr=$this->token;

				        $login_insert_sql = "select * from tb_customer where token = '".$autostr."' and enable_flag = 1;";
					//echo $login_insert_sql."<br>";
				        $result = mysql_query($login_insert_sql);
				        if ($result_datas = mysql_fetch_object($result)) {
				                $user_id = $result_datas->id;
				                $nickname = $result_datas->nickname;
				                session_start();
						//echo $user_id."<br>";
				                $_SESSION['gobeauty_user_id'] = $user_id;
				                $_SESSION['gobeauty_user_nickname'] = $nickname;
						return 1;
				        }
				}
*/
        	}

		}
		return 0;
	}
}
		//if(strpos($user_agent, "APP_GOBEAUTY_iOS") !== false){
?>
<script>
			//if(typeof window.webkit !== 'undefined') { //  && typeof window.Android.getVersion !== 'undefined'
				//var appVersion = window.webkit.messageHandlers.getVersion.postMessage('a'); //window.Android.getVersion();
				//if(appVersion != ""){
					//$.MessageBox(appVersion);
				//}else{
					//$.MessageBox({
					//	buttonDone: "변경",
					//	message: "앱 업데이트가 필요합니다."
					//}).done(function() {
					//	location.href='https://apps.apple.com/kr/app/id1436568194';
					//});
				//}
			//}
</script>
<?php
		//}
?>