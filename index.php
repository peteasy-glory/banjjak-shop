<?
header("Access-Control-Allow-Origin: *");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // 과거 아무 때나 잡으면 됨.
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");





// if (  strpos($mystring, "naver_process.php") !== false &&
// 	strpos($mystring, "/login/naver.php") !== false && 
// 	strpos($mystring, "/mainpage/apple_callback.php") !== false &&
// 	(empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'off') || $_SERVER['SERVER_PORT'] != 443) {	
// 	$link = "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];	
// 	header('Location: '.$link);
// 	exit;
// }


//$param_arr	= explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
//
//foreach($param_arr as $key => $val){
//	$tmp	= explode("=", $val);
//	$_GET[$tmp[0]]	= urldecode($tmp[1]);
//
//}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //호출한 uri을 가져옴
$uri = explode('/', $uri);
//echo $_SERVER['DOCUMENT_ROOT'];

$share = (isset($_GET["share"]) && $_GET["share"] != '')? $_GET["share"] : ""; // 사진 공유하기

	if($share != ""){
		include($_SERVER['DOCUMENT_ROOT']."/share_photo.html");
	}else{
		if($uri[1]=="login"){
			include($_SERVER['DOCUMENT_ROOT']."/login_shop.html");

		}else if(is_file($_SERVER['DOCUMENT_ROOT']."/".$uri[1].".html")){
			include($_SERVER['DOCUMENT_ROOT']."/".$uri[1].".html");

		}else if(isset($uri[2]) && is_file($_SERVER['DOCUMENT_ROOT']."/".$uri[1]."/".$uri[2].".php")){

			include($_SERVER['DOCUMENT_ROOT']."/".$uri[1]."/".$uri[2].".php");

		}else{
			if($uri[0]=="" && $uri[1]==""){
				include($_SERVER['DOCUMENT_ROOT']."/home_main.html");
			}else{
				echo "페이지 에러";
			}
		}
	}


?>
