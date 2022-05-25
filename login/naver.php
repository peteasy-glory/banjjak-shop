<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$r_pc = (isset($_GET["pc"]) && $_GET["pc"] != "")? $_GET["pc"] : "";
$pc_url = ($r_pc != "")? "?pc=".$r_pc : "";
?>

<?php
	$client_id = "UJ2SBwYTjhQSTvsZF8TO";
	$client_secret = "3gFya4za76";
	$code = $_GET["code"];;
	$state = $_GET["state"];;
	$redirectURI = urlencode("https://partner.banjjakpet.com/login/naver");
	$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
	$is_post = false;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = array();
	$response = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//	echo "status_code:".$status_code."";
	curl_close ($ch);
	if($status_code == 200) {
//		echo $response;
		$json_response = json_decode($response);
//		echo $json_response->access_token;
//		echo $json_response->token_type;

		$turl = "https://openapi.naver.com/v1/nid/me";
		$cht = curl_init();
		curl_setopt($cht, CURLOPT_URL, $turl);
		curl_setopt($cht, CURLOPT_POST, $is_post);
		curl_setopt($cht, CURLOPT_HTTPHEADER, array(
			'User-Agent: curl/7.12.1 (i686-redhat-linux-gnu) libcurl/7.12.1 OpenSSL/0.9.7a zlib/1.2.1.2 libidn/0.5.6',
			'Host: openapi.naver.com',
			'Pragma: no-cache',
			'Accept: */*',
			'Authorization: '.$json_response->token_type.' '.$json_response->access_token
		));
		curl_setopt($cht, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec ($cht);
		$status_codet = curl_getinfo($cht, CURLINFO_HTTP_CODE);
//		echo "status_code:".$status_codet."";
		curl_close ($cht);
		if($status_codet == 200) {
//			echo $response;
			$json_info = json_decode($response);
			$age= $json_info->response->age;
			$email= $json_info->response->email;
            //naver
//            $email = "pettester@peteasy.kr";
			$gender= $json_info->response->gender;
?>
			<script>
      			location.href='naver_process.php?email=<?=$email?>&age=<?=$age?>&gender=<?=$gender?>&pc=<?=$r_pc?>';
                // naver
                //location.href='naver_process.php?email=pettester@peteasy.kr&age=<?=$age?>&gender=<?=$gender?>&pc=<?=$r_pc?>';

			</script>
<?php
			return;
		}
	} else {
//		echo "Error 내용:".$response;
	}


?>
<script>
                $.MessageBox({
                        buttonDone      : "확인",
                        message         : "로그인에 실패하였습니다. 다시 확인해주세요."
                }).done(function(){
			location.href='login_shop';
                });
</script>

	<!--script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script-->


	<!-- (2) LoginWithNaverId Javscript 설정 정보 및 초기화 -->
	<script>
/*		var naverLogin = new naver.LoginWithNaverId(
			{
                        	clientId: "UJ2SBwYTjhQSTvsZF8TO",
                	        callbackUrl: "http://www.gobeauty.kr/login/naver.php",
        	                isPopup: false,
	                        callbackHandle: true
			}
		);

		naverLogin.init();

		window.addEventListener('load', function () {
			naverLogin.getLoginStatus(function (status) {
				if (status) {
					var email = naverLogin.user.getEmail();
					if( email == undefined || email == null) {
						alert("이메일은 필수정보입니다. 정보제공을 동의해주세요.");
						naverLogin.reprompt();
						return;
					}
                                        var age = naverLogin.user.getAge();
                                        if( age == undefined || age == null) {
                                                alert("나이는 필수정보입니다. 정보제공을 동의해주세요.");
                                                naverLogin.reprompt();
                                                return;
                                        }
                                        var sex = naverLogin.user.getGender();
                                        if( sex == undefined || sex == null) {
                                                alert("성별은 필수정보입니다. 정보제공을 동의해주세요.");
                                                naverLogin.reprompt();
                                                return;
                                        }

					location.href='naver_process.php?email='+email+'&age='+age+'&gender='+sex;
					//window.location.replace("http://" + window.location.hostname + ( (location.port==""||location.port==undefined)?"":":" + location.port) + "/sample/main.html");
				} else {
					console.log("callback 처리에 실패하였습니다.");
					alert("callback 처리에 실패하였습니다.");
				}
			});
		});
*/
	</script>


<?php
include "../include/bottom.php";
?>
