<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php include "configure.php"; ?>
<?php include "session.php"; ?>
<?php include "db_connection.php"; ?>
<?php include "php_function.php"; ?>

<?php
//echo "<pre style='color: #fff;'>"; print_r($_SESSION); echo "</pre>";
//로그인 상태 유지 체크 해서 쿠키가 사라지지 않았다면 재로그인 시킨다(2019-06-21 hue)
//세션 확인 시 값이 없을 경우 재로그인 시도
if(!$_SESSION['gobeauty_user_id']){
	if (isset($_COOKIE['user_hash']) ? $_COOKIE['user_hash'] : "") {
		include "auto_login.php";
	}
}
?>

<html style="height:100%;" lang="ko">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover" />
	<meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.ico">
    <link rel="icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.png">
	<?php
	//$url = $_SERVER['REQUEST_URI'];

	//특정 페이지에서 숫자를 전화번호로 인식하여 보이지 않는 문제 때문에 safari 내의 전화번호 검출 기능 off
	//if (strpos($url, "manage_my_reservation") !== false) {
		?>
	<!--meta name="format-detection" content="telephone=no"-->
	<?php
	//}
	?>

    <title>반짝 - 반려생활의 단짝</title>
    
    <link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/bjj.css?<?= filemtime($upload_static_directory . $css_directory . '/bjj.css') ?>" />
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/swiper.min.css?<?= filemtime($upload_static_directory . $css_directory . '/swiper.min.css') ?>" />
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/messagebox.css?<?= filemtime($upload_static_directory . $css_directory . '/messagebox.css') ?>" />
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/gobeauty.css?<?= filemtime($upload_static_directory . $css_directory . '/gobeauty.css') ?>" />
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/jquery-ui.min.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery-ui.min.css') ?>" />


	<!-- 안드로이드 ios 간 css 문제-->
	<?php
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$token_index = 0;

	$ie_index = 0;
	$trident_index = 0;
	$edge_index = 0;
	if ($user_agent) {
		$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
		$ie_index = strpos($user_agent, "MSIE");
		$trident_index = strpos($user_agent, "Trident");
		$edge_index = strpos($user_agent, "Edge");
	}

	if ($token_index > 0) {
		?>
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/common-android.css" />
	<?php
	} else if ($ie_index > 0 || $trident_index > 0 || $edge_index > 0) {
		?>
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/common-android.css" />
	<?php
	} else {
		?>
	<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/common.css?<?= filemtime($upload_static_directory . $css_directory . '/common.css') ?>" />
	<?php
	}
	?>

	<script type="text/javascript" src="<?= $root_directory ?>/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="<?= $root_directory ?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= $root_directory ?>/js/swiper.4.1.6.min.js"></script>
	<script type="text/javascript" src="<?= $root_directory ?>/js/messagebox.min.js"></script>
	<script type="text/javascript" src="<?= $root_directory ?>/js/common.js?<?= filemtime($upload_static_directory . $js_directory . '/common.js') ?>"></script>

</head>

<body style="height: auto;box-sizing: border-box;padding: 0px;margin: 0px;">
	<div id="loading"></div>

	<?php
	// 안드로이앱
	include "AutoLogin.class.php";
	$autologin = new AutoLogin();
	$autologin->make_session();

	?>
	<script>
		<?php	// iOS 앱
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
				$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
				if ($token_index > 0) {
		?>
					var version = "";
					try {
						window.webkit.messageHandlers.getVersion.postMessage('a');
					} catch(e) {
						console.log(e);
					}

					function getVersion(_version){
						version = _version;
						console.log('version : ' + version);
					}


		<?php
				}
			}
		?>
			function SaveTokeniOS(userid, usertoken) {
				<?php
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				if ($user_agent) {	// agent 존재 확인
					$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
					if ($token_index > 0) {	// agent-ios 확인
						?>
				var login_id = userid;
				var token = usertoken;

				if (login_id && token) {
					$.ajax({
						url: '../login/ios.php',
						data: {
							id: login_id,
							token: token
						},
						type: 'POST',
						success: function(result) {},
						error: function(xhr, status, error) {}
					});
				}
				<?php
					}
				}
				?>
			}

			// native app 에서 인증
			function AutoLoginiOS(userid, usertoken) {
				console.log('version : ' + version);
				<?php
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				if ($user_agent) {
					$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
					// ios 
					if ($token_index > 0) {
						?>
				var login_id = userid;
				var token = usertoken;
				var session_login_id = '<?= $_SESSION['gobeauty_user_id'] ?>';

				if (!session_login_id && login_id) {	// 로그인 수행
					var post_data = 'login_id=' + login_id + '&token=' + token;
					$.ajax({
						url: '../include/autologin.php',
						data: post_data,
						type: 'POST',
						success: function(data) {
							if(data == "OK_P"){	// 펫샵ID 인 경우

								/*
								// debug
								console.log('version : ' + version);
								if(version == "1.2")  {	// 20210607 by migo - ios 1.2 경우 /pet/mainpage/index_lisa.php 강제 이동
								} else {
								}
								*/
								
								if(window.location.pathname == "/pet/mainpage/index.php"){
									location.replace("<?=$shop_directory ?>/index.php");	// 펫샵주 관리메뉴로 강제 이동
								}
							}
							//
							if(data == "OK") { // 견주 회원
							
								/* 
								// debug
								if(login_id == "kungem@hotmail.com" || login_id == "idkvuf@naver.com") {
									alert(version + ':' + window.location.pathname);
								}
								*/
								if(version >= 1.2)  {	
									location.replace("/pet/mainpage/index_lisa.php");
								}
							}
							/*
							var html = '';

							html += '<div class="quick-btn03">';
							html += '	<a href="<?= $shop_directory ?>/index.php?from=main" class="phone_button">';
							html += '		<div><img src="../images/btn_myshop_1.png"></div>';
							html += '	</a>';
							html += '</div>';
							html += '<div class="quick-btn01">';
							html += '	<a href="<?= $shop_directory ?>/manage_sell_info.php?ch=month&from=main" class="phone_button">';
							html += '		<div><img src="../images/btn_Reservation_2.png"></div>';
							html += '	</a>';
							html += '</div>';
							html += '<div class="quick-btn02">';
							html += '	<a href="<?= $shop_directory ?>/manage_customer_list.php?from=main">';
							html += '		<div><img src="../images/btn_search_3.png"></div>';
							html += '	</a>';
							html += '</div>';
							html += '<div class="quick-btn04">';
							html += '	<a href="<?= $shop_directory ?>/manage_counseling_request.php?from=main">';
							html += '		<div class="count_wrap">';
							html += '			<div class="count"><?=$wait_count?></div><img src="../images/btn_Counseling_4.png">';
							html += '		</div>';
							html += '	</a>';
							html += '</div>';

							$(".footer_btn").html(html);
							*/
						},
						error: function(xhr, status, error) {}
					});
				}

				if (login_id && token) {	// 토큰 업뎃
					$.ajax({
						url: '../login/ios.php',
						data: {
							id: login_id,
							token: token
						},
						type: 'POST',
						success: function(result) {},
						error: function(xhr, status, error) {}
					});
				}

				/*
				if(version == "1.2") {	// v1.2 체크
					alert(version);
				}
				*/
				<?php
					}	// agent-ios 확인
				}	// agent 존재 확인
				?>
			}
/*
			var loginid = "";
			var loginToken = "";
			var session_login_id = '<?=$_SESSION['gobeauty_user_id']?>';

			window.webkit.messageHandlers.onAppGetLogin.postMessage('a'); // login_id call

			function onAppGetLogin(login){ // login_id return
				//if (login && login.trim() == 'nakmsna@naver.com') {
				//	alert(login);
				//}
				loginid = login; // 전역변수 값 추가
				window.webkit.messageHandlers.onAppGetToken.postMessage('a'); // token call
			}

			function onAppGetToken(onAppToken){ // token return
				loginToken = (onAppToken != "" && onAppToken != "-")? onAppToken : "";
				if (loginid == 'nakmsna@naver.com' || loginid == 'pickmon@pickmon.com' || loginid == 'ulmo26@gmail.com') {
					//alert(loginToken);
				}
				ios_login_push();
			}

			function ios_login_push(){
				if (!session_login_id && loginid) {
					var post_data = 'login_id='+loginid+'&token='+loginToken;
					$.ajax({
						url: '/pet/include/autologin.php',
						data: post_data,
						type: 'POST',
						success: function(data){
							//alert(data);
							location.reload();
							//location.href="<?=$shop_directory ?>/index.php";
						},
						error : function(xhr, status, error) {
						}
					});
				}

				if (loginid && loginToken) {
					//alert(loginid+"//"+loginToken);
					if(loginToken.trim() != "-"){
						$.ajax({
							url: '/pet/login/android.php',
							data: {
								id : loginid,
								token : loginToken,
								is_android : 'yes'
							},
							type: 'POST',
							success: function(data){
								//alert(data);
								//location.reload();
							},
							error : function(xhr, status, error) {
							}
						});
					}
				}
			}
*/
	</script>