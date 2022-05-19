<?php include "../include/top.php"; ?>

<?php
//로그인 유지 기능 (2019-06-21 hue)
if ($_COOKIE['user_hash'] != "") {
    echo "
			<script>
				location.href = '../shop/index_pc.php';
			</script>
		";
    exit;
}
?>

<link rel="stylesheet" href="<?= $css_directory ?>/login_pc.css?<?= filemtime($upload_static_directory . $css_directory . '/login_pc.css') ?>">
<style>
body{background:#f2f4f7;}
.wrap{margin-top:150px;}
.wrap_footer{width: 100%;height: 200px;max-width: 700px;margin: auto;margin-top: 10px;}
.login-box .input-wrap{margin:20px auto 10px;width: 100%;}
.login-box main header h1{font-size: 20px;text-align:center;}
.btn-wrap {width: 100%;margin-top: 10px;}
.logo_photo {width:auto;margin-right: 30px;}
.logo{margin:0 auto;}
footer{border:none;}
.border_set{padding:20px; border:5px solid #f5bf2d; border-radius:10px;background: #fff;}
</style>
<script>
    function on_login() {
        var id = document.getElementById('gobeauty_user_name').value;
        return window.Android.onLogin(id);
    }
</script>
<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>

<center class="wrap">
	<div class="border_set" >
		<table style="100%">
			<tbody>
				<tr>
					<td style="padding:30px;">
						<center class="logo"><img src="<?= $image_directory ?>/logo_photo.png" class="logo_photo"/></center>
						<center class="logo"><img src="<?= $image_directory ?>/logo_02.png" width="150px" /></center>
					</td>
					<td style="padding:20px 50px;">
						<div class="login-box">
							<main>
								<header>
									<h1>펫샵 회원 My Shop 로그인</h1>

								</header>
								<section>
									<form class="login-form" action="login_process.php" method="POST">
										<input type="hidden" name="is_pc" value="true" />
										<div class="input-box">
											<div class="input-wrap">
												<input type="text" placeholder="이메일 아이디입력" name="gobeauty_user_name" id="gobeauty_user_name" required>
												<input type="password" placeholder="비밀번호" name="gobeauty_user_password" id="gobeauty_user_password" required>
												<input type="checkbox" checked="checked" name="remember"><span class="keep-login">로그인 상태유지</span>
											</div>
											<div class="btn-wrap">
												<button class="go_login" type="submit">로그인</button>
												<?php
												// 네이버 로그인 접근토큰 요청 예제
												$client_id = "UJ2SBwYTjhQSTvsZF8TO";
												$redirectURI = urlencode("http://www.gopet.kr/pet/login/naver.php?pc=1");
												$state = "RAMDOM_STATE";
												$apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=" . $client_id . "&redirect_uri=" . $redirectURI . "&state=" . $state;
												?>
												<a href="<?php echo $apiURL ?>" class="ngo_login"><span>N</span>네이버 아이디로 로그인</a>
											</div>
										</div>
									</form>
								</section>
							</main>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
    <footer>
        <div class="wrap_footer">
            <p>㈜펫이지(PetEasy Co.,Ltd) / 대표자:신동찬 / <a href="http://naver.me/xmkoKHQ3" target="_blank" class="underline">서울 노원구 동일로 174길 27 서울창업디딤터 3층 301호</a></p>
            <p>사업자등록번호 : 157-86-01070 <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=&apv_perm_no=2019322023630200169" target="_blank" class="underline">사업자 정보 확인</a> / 통신판매업 : 제2019-서울노원-0540호</p>
            <p>대표메일 : <a href="mailto:help@peteasy.kr">help@peteasy.kr</a></p><br>
        </div>
    </footer>
</center>



<?php include "../include/bottom.php"; ?>