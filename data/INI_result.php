<?php
	include "../include/top.php";
/*
	$return_arr = array();
	$return_data = "P_STATUS=00&P_AUTH_DT=20200821102612&P_AUTH_NO=22043784&P_RMESG1=성공적으로 처리 하였습니다.&P_RMESG2=00&P_TID=INIMX_ISP_INIpayTest20200821102612225347&P_FN_CD1=06&P_AMT=1005&P_TYPE=CARD&P_UNAME=홍길동&P_MID=INIpayTest&P_OID=I1234567890123&P_NOTI=tb_item_cart.ic_seq&P_NEXT_URL=https://gopet.kr/pet/mainpage/INI_return.php&P_MNAME=울모애견샵&P_NOTEURL=&P_CARD_MEMBER_NUM=&P_CARD_NUM=536510*********8&P_CARD_ISSUER_CODE=04&P_CARD_PURCHASE_CODE=06&P_CARD_PRTC_CODE=1&P_CARD_INTEREST=0&P_CARD_CHECKFLAG=1&P_CARD_ISSUER_NAME=국민카드&P_CARD_PURCHASE_NAME=국민계열&P_FN_NM=국민계열&P_ISP_CARDCODE=050204900094081&P_CARD_APPLPRICE=1005";

	if(strpos($return_data, "&") !== false) {
		$tmp_data = explode("&", $return_data);
		foreach($tmp_data AS $key => $value){
			if(strpos($value, "=") !== false) {
				$tmp_val = explode("=", $value);
				$return_arr[$tmp_val[0]] = $tmp_val[1];
			}else{
				// key=value 값이 아니면 무시
			}
		}
	}else{
		//echo "정확한 리턴값이 아닙니다.";
	}

	//echo "<pre>";
	//print_r($return_arr);
	//echo "</pre>";
	$pg_log = addslashes(json_encode($return_arr));

	if($return_arr["P_STATUS"] == "00"){
		// P_OID = order_num
		// P_AUTH_DT = 20200821124578 -> Y-m-d H:i:s 로 형식 변경
		$sql = "
			UPDATE tb_hotel_payment_log SET
				total_price = '".$return_arr["P_AMT"]."',
				receipt_type = '1',
				pay_type = '1',
				pay_status = '3',
				pg_log = '".$pg_log."',
				pay_dt = NOW(),
				update_dt = NOW()
			WHERE order_num = '1'
		"; // WHERE order_num = '".$return_arr["P_OID"]."'

		//$result = mysql_query($sql);
		if($result){
			//echo "OK";
		}else{
			//echo "Error".$sql;
		}
	}
*/
?>
