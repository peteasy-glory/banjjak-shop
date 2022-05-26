<?php
	$document_root	= $_SERVER['DOCUMENT_ROOT'];
	$document_root2	= "/var/www/html";
	$root_directory = "/user";
	$upload_directory = "upload";
	$upload_directory2 = "/upload";
//	$upload_static_directory = "/var/www/html/subdomain/banjjak_sol/shop";
//	$upload_static_directory2 = "/var/www/html/subdomain/banjjak_sol/shop";
	$upload_static_directory = $document_root;
	$upload_static_directory2 = $document_root;

	$mainpage_directory = $root_directory."/mainpage";
	$image_directory = $root_directory."/images";
	$include_directory = $root_directory."/include";
	$js_directory = $root_directory."/js";
	$css_directory = $root_directory."/css";
	$login_directory = $root_directory."/login";
	$admin_directory = $root_directory."/admin";
	$start_notice_directory = $root_directory."/start_notice";
	$shop_directory = "";
	$artist_directory = $root_directory."/artist";
	$sign_directory = $upload_directory."/sign";
	$sign_directory2 = $upload_directory2."/sign";
	$item_directory = $root_directory."/item";
	$partner_directory = $root_directory."/partner";

	$access_key = "20110f71-b2e2-41f5-aa1b-bb1111381161";
	$secret_key = "MrSyBBMOW1KXPU24m4+3r0q84i7gW56Ebx6kOwp79z8=";

    $rest_application_id = '5acc2185b6d49c7b637d9c12';
	$rest_private_key = 'oZq//0OpaSulB2uzNU8l7mQGgQpePEmpihnUb5TuAvA=';
	
	$naver_client_id = "UJ2SBwYTjhQSTvsZF8TO";
	$naver_client_secret_key = "3gFya4za76";

	$master_key_name = "peteasy";	//2019-06-21 hue(로그인 상태 유지)

	$company_name = "반짝";
	$company_help_number = "1661-9956";
	$company_help_email = "help@peteasy.kr";
	$company_address = "서울시 종로구 종로6 5층 서울창조경제혁신센터";
	$company_registration_number = "157-86-01070";


	// peteasy 임직원 - 테스트용
	$_arr_peteasy_workers = array(
		'itseokbeom@gmail.com',
		'pickmon@pickmon.com',
		'saychanjin@naver.com',
		'sally@peteasy.kr',
		'chansworld@pickmon.com',
		'sunny@peteasy.kr',
		'kungem@hotmail.com',	// [migo] applelogin + 샵+관리
		"migo@kakao.com",	// [migo] 일반로그인 + 샵
		//"migo_0@naver.com",	// [migo] 네이버로그인 + 견주
		"psj990324@naver.com"
	);
?>
