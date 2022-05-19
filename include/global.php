<?php
//ini_set('display_errors','1');
ini_set('allow_url_fopen','1');
ini_set("display_errors", 0);

ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
ini_set("session.cache_expire", 30); // 세션 유효시간 : 분
ini_set("session.gc_maxlifetime", 86400); // 세션 가비지 컬렉션(로그인시 세션지속 시간) : 초

ini_set('memory_limit', -1); 
//ini_set("url_rewriter.tags","");


$isSiteStoreOwner = false;
if($_SERVER['SERVER_NAME']=='partner.banjjakpet.com'){
	$isSiteStoreOwner = true;
//	@session_name('shop_gopet');
	@ini_set("session.cookie_domain", "partner.banjjakpet.com");
	session_save_path($_SERVER['DOCUMENT_ROOT']."/session"); // 계정의 홈디렉토리부터 시작합니다.
}else{
	session_save_path($_SERVER['DOCUMENT_ROOT']."/session"); // 계정의 홈디렉토리부터 시작합니다.	
}

/*
$isSiteStoreOwner = false;
if($_SERVER['SERVER_NAME']=='shop.gopet.kr'){
    $isSiteStoreOwner = true;
//  @session_name('shop_gopet');
    @ini_set("session.cookie_domain", "shop.gopet.kr");
    session_save_path($_SERVER['DOCUMENT_ROOT']."/session"); // 계정의 홈디렉토리부터 시작합니다.
}else{
    session_save_path($_SERVER['DOCUMENT_ROOT']."/session"); // 계정의 홈디렉토리부터 시작합니다.
} 
*/


session_start();
header("Content-Type: text/html; charset=UTF-8");



//echo $_SERVER['DOCUMENT_ROOT']."include/configure.php";


include($_SERVER['DOCUMENT_ROOT']."/include/configure.php");
include($document_root."/include/Crypto.class.php");
include($document_root."/include/session.php");
include($document_root."/include/source/db.connection.php");
//include($document_root."include/lib/common.lib.php");
include($document_root."/include/lib/Pagination.php");
include($document_root."/include/php_function.php");

include($document_root."/include/Point.class.php");
include($document_root."/include/Heart.class.php");
include($document_root."/include/VAT.class.php");
include($document_root."/include/App.class.php");
include($document_root."/include/Push.class.php");

include($document_root."/include/mobile_check.php");
include($document_root."/include/navigation.php");

include($document_root."/common/TAwsS3.php");
//include($document_root."/common/TEmoji.php");

if ($isSiteStoreOwner) {
	if(!$_SESSION['gobeauty_user_id']){
		if (isset($_COOKIE['user_hash']) ? $_COOKIE['user_hash'] : "") {
			include($_SERVER['DOCUMENT_ROOT']."/include/auto_login.php");
		}
	}
}

$cssVersion = time();
$jsVersion = time();

?>
