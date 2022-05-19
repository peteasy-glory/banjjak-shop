<?php
include "../include/configure.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/Crypto.class.php";
include "../include/php_function.php";
include "../include/Allimtalk.class_2.php";
//include "../include/AllimtalkFailMessage.class.php";

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");



	$talk = new Allimtalk();
//	$talk_m = new AllimtalkFailMessage();

	$talk->cellphone = "01086331776";
//	$talk_m->cellphone = "01086331776";

	
	$talkCustomerName = "1776";
	$talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=265509";
	// 미용 예약 내용 template_code = 14040
	$talkResult = $talk->sendReservationNotice_new($talkCustomerName, "글로리개", "글로리샵", "2021-06-04", $talkBtnLink);

	// 예약 변경시 template_code = 14041
//	$talkResult = $talk->sendUpdateNotice_new($talkCustomerName, "글로리강아지", "글로리샵", "2021-06-04", "2021-06-05", $talkBtnLink);

	// 미용 종료 알림 template_code = 14042
//	$talkresult = $talk->sendScheduleEndNotice_new($talkCustomerName, "글로리강아지", "글로리샵", "10분전");
	
//	$talkresult = $talk_m->sendScheduleEndNotice_new($talkCustomerName, "글로리강아지", "글로리샵", "10분전"); // 문자 발송코드

	// 전날알림 templayte_code = 14043
//	$talkResult = $talk->sendReservationNoticeTempUser_new($talkCustomerName, "글로리강아지", "글로리샵", "2021-06-04", $talkBtnLink);


	// 문자 테스트 코드 시작
	/*
	if($talk){
		$m_query = "SELECT * FROM ita_talk_tran 
					WHERE recipient_num = '01086331776'
					AND template_code = '1000004530_14042'
				";
		$m_result = mysql_query($m_query);
		while($row = mysql_fetch_assoc($m_result)){
			$data[] = $row;
		}
		$return_data = array("code" => "000000", "data" => $data);

		if($row["recipient_num"] != "01086331776"){
			$talkresult = $talk_m->sendScheduleEndNotice_new($talkCustomerName, "글로리강아지문자", "글로리샵문자", "10분전문자");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}
	*/
	// 문자 테스트코드 끝

//echo json_encode($row, JSON_UNESCAPED_UNICODE);
//echo $row["report_code"];