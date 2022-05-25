<?
//ini_set('display_errors','1');
ini_set('allow_url_fopen','1');
ini_set("display_errors", 0);

ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
ini_set("session.cache_expire", 3); // 세션 유효시간 : 분
ini_set("session.gc_maxlifetime", 180); // 세션 가비지 컬렉션(로그인시 세션지속 시간) : 초

ini_set('memory_limit', -1); 
//ini_set("url_rewriter.tags","");

if($_SERVER['SERVER_NAME']=='shop.gopet.kr'){
	@session_name('gopet');
	@ini_set("session.cookie_domain", "shop.gopet.kr");
	session_save_path($_SERVER['DOCUMENT_ROOT']."/shop/session"); // 계정의 홈디렉토리부터 시작합니다.
}else{
	session_save_path($_SERVER['DOCUMENT_ROOT']."/session"); // 계정의 홈디렉토리부터 시작합니다.	
}



session_start();
header("Content-Type: text/html; charset=UTF-8");




include($_SERVER['DOCUMENT_ROOT']."/include/configure.php");
include($document_root."include/Crypto.class.php");
include($document_root."include/session.php");
include($document_root."include/source/db.connection.php");
//include($document_root."include/lib/common.lib.php");
include($document_root."include/lib/Pagination.php");
include($document_root."include/php_function.php");
include($document_root."include/Point.class.php");
include($document_root."include/Heart.class.php");
include($document_root."include/VAT.class.php");
include($document_root."include/App.class.php");
include($document_root."include/Push.class.php");

include($document_root."include/mobile_check.php");

$cssVersion = "1";

?>