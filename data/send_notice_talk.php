<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$payment_log_seq = $_POST['payment_log_seq'];
$timer = ($_POST['timer']=='y')?'0':$_POST['timer'];
$resultData = array();

$query = "SELECT *
        , SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',1),'|',-1) petName
        , SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',3),'|',-1) shopName
     FROM tb_payment_log WHERE payment_log_seq = '{$payment_log_seq}'";
//echo $query;
$result = sql_query($query);
$data = sql_fetch($result);

if(isset($data) && $data != null && $data['notice_yn'] != "Y"){
    $talk = new Allimtalk();

    $cellphone = $data['cellphone'];
    $talk->cellphone = $cellphone;

    $talkCustomerName = substr($cellphone, -4);
    $talkPetName = $data['petName'];
    $talkShopName = $data['shopName'];
    $talkTimer = $timer."분 전";

    if($timer == "0"){ // 미용종료 즉시 알림
		$result = $talk->sendScheduleEndNoticeNow_new($talkCustomerName, $talkPetName, $talkShopName);
		$resultData["result"] = $result;
	}else{ // 미용 종료 n분천 알림
		$result = $talk->sendScheduleEndNotice_new($talkCustomerName, $talkPetName, $talkShopName, $talkTimer);
		$resultData["result"] = $result;
	}

    if($result){
        $update = "UPDATE tb_payment_log SET notice_yn = 'Y' WHERE payment_log_seq = '{$payment_log_seq}'";
        sql_query($update);
    }
}else{
    $resultData["result"] = false;
}

echo json_encode($resultData, JSON_UNESCAPED_UNICODE);
?>