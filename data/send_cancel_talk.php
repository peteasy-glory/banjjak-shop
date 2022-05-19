<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$payment_log_seq = $_POST['payment_log_seq'];
$timer = ($_POST['timer']=='y')?'0':$_POST['timer'];
$resultData = array();


// 취소 알림톡
$shop_sql = "SELECT s.name as name FROM tb_customer as c LEFT OUTER JOIN tb_shop s ON s.customer_id = c.id WHERE c.id = '".$artist_id."'";
$shop_result = sql_query($connection, $shop_sql);
$shop_data = mysql_fetch_object($shop_result);
$shopName = $shop_data->name;

$now = DATE("Y-m-d H:i");
$nowTime = date("Y-m-d H:i", strtotime($now));

$year = $check_data->year;
$month = $check_data->month;
$day = $check_data->day;
$hour = $check_data->hour;
$minute = $check_data->minute;
$reservationTime = strtotime("$year-$month-$day $hour:$minute");

if ($allim_chk == "Y") { // 발송을 눌렀을때 발송하기
    $talk = new Allimtalk();

    $talk->cellphone = $cellphone;

    $week_arr = ['일', '월', '화', '수', '목', '금', '토'];
    $talkCustomerName = substr($cellphone, -4);
    //$talkDate = date("Y년 m월 d일 H시 i분", $reservationTime);
    $talkDate = date("Y년 m월 d일", $reservationTime);
    $talkDate .= "(".$week_arr[date("w", $reservationTime)].") ";
    $talkDate .= date("H시 i분", $reservationTime);

    $cancelTime = strtotime($nowTime);
    $cancelDate = date("Y년 m월 d일", $cancelTime);
    $cancelDate .= "(".$week_arr[date("w", $cancelTime)].") ";
    $cancelDate .= date("H시 i분", $cancelTime);
    $talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=".$payment_log_seq;
    $talkResult = $talk->sendScheduleCancelNoticeNow_new($talkCustomerName, $pet_name, $shopName, $cancelDate, $talkDate, $talkBtnLink);
}




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