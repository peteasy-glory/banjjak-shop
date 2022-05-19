<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$id = $_POST['id'];
$token = $_POST['token'];
$is_android = $_POST['is_android'];
$is_android_app = 0;
if ($is_android == "yes") {
    $is_android_app = 1;
}
// partner 분리
//$token_column = ($_SERVER['HTTP_HOST'] == "partner.banjjakpet.com")? "partner_token" : "token";
$sql = "update tb_customer set partner_token = '".$token."', is_android = ".$is_android_app.", last_applogin_time=now() where id = '".$id."';";	// 20210701 by migo - last_applogin_time 최신 앱로그인 시각 추가
$result = mysqli_query($connection,$sql);
if($result){
    echo "OK";
}else{
    echo "Fail";
}
?>
