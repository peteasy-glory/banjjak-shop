<?php
//include "../include/top.php";
include "../include/configure.php";
include "../include/Crypto.class.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/php_function.php";

$id = $_REQUEST['id'];
$token = $_REQUEST['token'];
$is_android = $_REQUEST['is_android'];
$is_android_app = 0;
if ($is_android == "yes") {
	$is_android_app = 1;
}
$sql = "update tb_customer set token = '".$token."', is_android = ".$is_android_app.", last_applogin_time=now() where id = '".$id."';";	// 20210701 by migo - last_applogin_time 최신 앱로그인 시각 추가
$result = mysql_query($sql);
if($result){
	echo "OK";
}else{
	echo "Fail";
}
?>



<?php
//include "../include/bottom.php";
?>
