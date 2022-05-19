<?php
include "../include/configure.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/Crypto.class.php";
include "../include/php_function.php";
include "../include/AllimtalkFailMessage.class.php";



	$talk = new AllimtalkFailMessage();

	$talk->cellphone = "01086331776";

	
	$talkCustomerName = "1776";
	$talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=265509";
	// 미용 예약 내용 template_code = 14040
	$talkResult = $talk->sendReservationNotice_new($talkCustomerName, "글로리개", "글로리샵", "2021-06-04", $talkBtnLink);

	// 예약 변경시 template_code = 14041
//	$talkResult = $talk->sendUpdateNotice_new($talkCustomerName, "글로리강아지", "글로리샵", "2021-06-04", "2021-06-05", $talkBtnLink);

	// 미용 종료 알림 template_code = 14042
//	$talkresult = $talk->sendScheduleEndNotice_new($talkCustomerName, "글로리강아지", "글로리샵", "10분전");

	// 전날알림 templayte_code = 14043
//	$talkResult = $talk->sendReservationNoticeTempUser_new($talkCustomerName, "글로리강아지", "글로리샵", "2021-06-04", $talkBtnLink);

