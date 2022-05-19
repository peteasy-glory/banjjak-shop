<?php
include "../include/configure.php";

include "../include/session.php";
include "../include/db_connection.php";
include "../include/php_function.php";
include "../include/Allimtalk.class.php";

function check_param( $param ) {
    if ( is_null($param) || !$param ) {
        return false;
    } else {
        return true;
    }
}

$return = array();

$return['POST'] = $_POST;
$return['GET'] = $_GET;

$payment_log_seq = isset($_POST['payment_log_seq']) ? $_POST['payment_log_seq'] : null; 
$artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : null; 
$worker = isset($_POST['worker']) ? $_POST['worker'] : null; 
$from_time = isset($_POST['from_time']) ? $_POST['from_time'] : null;
$to_time = isset($_POST['to_time']) ? $_POST['to_time'] : null;

$change_yn = isset($_POST['change_yn']) ? $_POST['change_yn'] : 'Y';

if ( check_param($payment_log_seq) && check_param($worker) && check_param($from_time) && check_param($to_time) ) {
    list($from_hour,$from_minute) = explode(':',$from_time);
    list($to_hour,$to_minute) = explode(':',$to_time);
} else {
    $return['res']['data'] = false;
    $return['alert']['data'] = '예약정보가 부족합니다.';
    $return['error']['data'] = 'POST';
    echo json_encode($return);
    exit;
}

// 예약정보 존재여부 체크
$sql_is_payment = "SELECT
        pay.* ,
        SUBSTRING_INDEX(SUBSTRING_INDEX(pay.product,'|',1),'|',-1) petName ,
        SUBSTRING_INDEX(SUBSTRING_INDEX(pay.product,'|',3),'|',-1) shopName ,
        date_format(concat(pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00'),'%Y-%m-%d %H:%i') req_date_from ,
        date_format(concat(pay.year,'-',pay.month,'-',pay.day,' ',pay.to_hour,':',IFNULL(pay.to_minute,'00'),':00'),'%Y-%m-%d %H:%i') req_data_to ,
        date_format('{$_POST['year']}-{$_POST['month']}-{$_POST['day']} {$_POST['from_time']}:00','%Y-%m-%d %H:%i') date_from ,
        date_format('{$_POST['year']}-{$_POST['month']}-{$_POST['day']} {$_POST['to_time']}:00','%Y-%m-%d %H:%i') date_to
        -- date_format(concat(pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL('00',pay.minute),':00'),'%Y-%m-%d') run_date
    FROM
        `tb_payment_log` pay
    WHERE
        pay.payment_log_seq = '{$payment_log_seq}'
        AND pay.worker = '{$worker}'
		AND pay.artist_id = '{$artist_id}'
        AND pay.is_cancel = 0
";
$return['SQL']['select']['is'] = $sql_is_payment;
if ( $result = mysql_query($sql_is_payment) ) {
    $rows = mysql_num_rows($result);
    $return['res']['select']['is'] = true;
    $return['rows']['select']['is'] = (int)$rows;
    if ( !$rows ) {
        $return['alert']['select']['is'] = '예약정보가 존재하지 않습니다.';
        echo json_encode($return);
        exit;
    } else {
        $row = mysql_fetch_assoc($result);
        $year = $row['year'];
        $month = $row['month'];
        $day = $row['day'];
    }
} else {
    $return['res']['select']['is'] = false;
    $return['alert']['select']['is'] = '오류가 발생하였습니다.';
    $return['error']['select']['is'] = mysql_error();
    $return['errno']['select']['is'] = mysql_errno();
    echo json_encode($return);
    exit;
}

// 예약시간중복 체크
$sql_check_time = "SELECT
        pay.*
    FROM 
        `tb_payment_log` pay
    WHERE
        pay.worker = '{$worker}'
		AND pay.artist_id = '{$artist_id}'
        AND pay.payment_log_seq != '{$payment_log_seq}'
        AND pay.is_cancel = 0
        AND DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) >= DATE_FORMAT( '{$year}-{$month}-{$day} {$from_time}:00' , '%Y-%m-%d %H:%i' )
        AND DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) < DATE_FORMAT( '{$year}-{$month}-{$day} {$to_time}:00' , '%Y-%m-%d %H:%i' )
";
$return['SQL']['select']['time'] = $sql_check_time;
if ( $result = mysql_query($sql_check_time) ) {
    $rows = mysql_num_rows($result);
    $return['res']['select']['time'] = true;
    $return['rows']['select']['time'] = (int)$rows;
    if ( $rows ) {
        $return['alert']['select']['time'] = '이미 선택하신 시간대에 예약되어 있습니다.';
        echo json_encode($return);
        exit;
    }
} else {
    $return['res']['select']['time'] = false;
    $return['alert']['select']['time'] = '오류가 발생하였습니다.';
    $return['error']['select']['time'] = mysql_error();
    $return['errno']['select']['time'] = mysql_errno();
    echo json_encode($return);
    exit;
}

// 예약시간 변경
$sql_update = "UPDATE 
        `tb_payment_log` 
    SET 
        hour={$from_hour} ,
        to_hour={$to_hour} ,
        minute={$from_minute} ,
        to_minute={$to_minute} ,
        update_time=now()
    WHERE
        payment_log_seq='{$payment_log_seq}'
		AND artist_id = '{$artist_id}'
        AND worker='{$worker}'
";
$return['SQL']['update'] = $sql_update;
if ( $result = mysql_query($sql_update) ) {
    $return['res']['update'] = true;
    $return['rows']['update'] = $rows = mysql_affected_rows();
    if ( $rows ) {
        $return['alert']['update'] = '예약정보가 변경되었습니다.';

        $now = time();
        $beforeTime = strtotime($row["req_date_from"]);
        $changeTime = strtotime("$year-$month-$day $from_hour:$from_minute");

        if($beforeTime != $changeTime && $changeTime > $now && $change_yn == "Y"){
            $talk = new Allimtalk();
            
            $talk->cellphone = $row["cellphone"];

            $talkCustomerName = substr($row["cellphone"], -4);
            $talkPetName = $row["petName"];
            $talkShopName = $row["shopName"];
            $talkDate = date("Y년 m월 d일 H시 i분", $beforeTime);
            $talkChangeDate = date("Y년 m월 d일 H시 i분", $changeTime);
            $talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=$payment_log_seq";

            $talkResult = $talk->sendUpdateNotice_new($talkCustomerName, $talkPetName, $talkShopName, $talkDate, $talkChangeDate, $talkBtnLink);
        }

		// 적립금 조건 지급
		if(STRTOTIME(DATE("Y-m-d H:i:s", STRTOTIME($year."-".$month."-".$day." ".$to_time.":00"))) < STRTOTIME(DATE("Y-m-d H:i:s"))){
			$sql = "
				SELECT *
				FROM tb_shop_reserve
				WHERE is_delete = '2'
					AND artist_id = '".$artist_id."'
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$percent = $row["percent"];

			if($row["is_use"] == "1"){
				$sql = "
					SELECT *
					FROM tb_payment_log
					WHERE payment_log_seq = '".$payment_log_seq."'
				";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$customer_id = $row["customer_id"];
				$cellphone = $row["cellphone"];
				$tmp_seq = "";
				$local_price = $row["local_price"]; // 총거래금액
				$local_price_cash = $row["local_price_cash"]; // 총거래금액
				$add_reserve = round(($local_price + $local_price_cash) * $percent / 100); // 적립금
				$now_reserve = 0;
				$err_msg = "";

				if(($local_price + $local_price_cash) > 0){ // 총 거래금액이 0원 이상
					$where_qy = "";
					if($customer_id != ""){ // 정회원
						$where_qy .= " AND customer_id = '".$customer_id."' ";
					}else{ // 가회원
						$sql = "
							SELECT *
							FROM tb_tmp_user
							WHERE cellphone = '".$cellphone."'
						";

						$result = mysql_query($sql);
						$row = mysql_fetch_assoc($result);
						$tmp_seq = $row["tmp_seq"];
						$where_qy .= " AND tmp_seq = '".$tmp_seq."' ";
						$where_qy .= " AND cellphone = '".$cellphone."' ";
					}

					$sql = "
						SELECT *
						FROM tb_user_reserve
						WHERE is_delete = '2'
							AND artist_id = '".$artist_id."'
							".$where_qy."
					";
					$result = mysql_query($sql);
					$cnt = mysql_num_rows($result);
					$row = mysql_fetch_assoc($result);
					$reserve = $row["reserve"];

					if($cnt > 0){ // update
						// 이미 지급 되었는지 확인
						$sql = "
							SELECT *
							FROM tb_user_reserve_log
							WHERE is_delete = '2'
								AND artist_id = '".$artist_id."'
								AND payment_log_seq = '".$payment_log_seq."'
								AND service_type = 'B'
								AND type = 'R'
								".$where_qy."
						";
						$result = mysql_query($sql);
						$cnt = mysql_num_rows($result);
						$row = mysql_fetch_assoc($result);
						$cancel_add_reserve = $row["add_reserve"];
						$cencel_memo = $row["memo"];

						if($cnt > 0){ // update + update
							// 지급된 적립금 삭제 + 새로 지급
							$now_reserve = $reserve - $cancel_add_reserve;
							$sql = "
								UPDATE tb_user_reserve SET
									reserve = '".$now_reserve."'
								WHERE is_delete = '2'
									AND artist_id = '".$artist_id."'
									".$where_qy."
							";
							$result = mysql_query($sql);
							if($result){
								$now_reserve = $now_reserve + $add_reserve;
								$sql = "
									UPDATE tb_user_reserve SET
										reserve = '".$now_reserve."'
									WHERE is_delete = '2'
										AND artist_id = '".$artist_id."'
										".$where_qy."
								";
								$result = mysql_query($sql);
								if($result){
									$sql = "
										UPDATE tb_user_reserve_log SET
											add_reserve = '".$add_reserve."',
											now_reserve = '".$now_reserve."',
											memo = '".$cencel_memo."|시간변경으로 지급',
											update_dt = NOW()
										WHERE is_delete = '2'
											AND artist_id = '".$artist_id."'
											AND payment_log_seq = '".$payment_log_seq."'
											AND service_type = 'B'
											AND type = 'R'
											".$where_qy."
									";
									$result = mysql_query($sql);
									if($result){
										// OK
									}else{
										// Err - 회원 적립내역 변경 실패(tb_user_reserve_log)
										$err_msg = "회원 적립내역 변경 실패(tb_user_reserve_log)";
									}
								}else{
									// Err - 회원 적립금 추가 실패(tb_user_reserve)
									$err_msg = "회원 적립금 추가 실패(tb_user_reserve)";
								}
							}else{
								// Err - 회원 적립금 회수 실패(tb_user_reserve)
								$err_msg = "회원 적립금 회수 실패(tb_user_reserve)";
							}
						}else{ // update + insert
							$now_reserve = $reserve + $add_reserve; // 보유 적립금 + 적립금
							$sql = "
								UPDATE tb_user_reserve SET
									reserve = '".$now_reserve."'
								WHERE is_delete = '2'
									AND artist_id = '".$artist_id."'
									".$where_qy."
							";
							$result = mysql_query($sql);
							if($result){
								$sql = "
									INSERT INTO tb_user_reserve_log (
										`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `payment_log_seq`, 
										`service_type`, `type`, `memo`, `add_reserve`, `now_reserve`
									) VALUES (
										'".$customer_id."', '".$tmp_seq."', '".$cellphone."', '".$artist_id."', '".$payment_log_seq."', 
										'B', 'R', 'view_payment_customer_info의 시간변경으로 지급', '".$add_reserve."', '".$now_reserve."'
									)
								";
								$result = mysql_query($sql);
								if($result){
									// OK
								}else{
									// Err - 회원 적립내역 변경 실패(tb_user_reserve_log)
									$err_msg = "회원 적립내역 변경 실패(tb_user_reserve_log)";
								}
							}else{ 
								// ERR - 회원 적립금 적립 실패(tb_user_reserve)
								$err_msg = "회원 적립금 적립 실패(tb_user_reserve)";
							}
						}
					}else{ // insert
						$now_reserve = $add_reserve; // $reserve = 0;
						$sql = "
							INSERT INTO tb_user_reserve (
								`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `reserve`
							) VALUES (
								'".$customer_id."', '".$tmp_seq."', '".$cellphone."', '".$artist_id."', '".$now_reserve."'
							)
						";
						$result = mysql_query($sql);
						if($result){
							$sql = "
								INSERT INTO tb_user_reserve_log (
									`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `payment_log_seq`, 
									`service_type`, `type`, `memo`, `add_reserve`, `now_reserve`
								) VALUES (
									'".$customer_id."', '".$tmp_seq."', '".$cellphone."', '".$artist_id."', '".$payment_log_seq."', 
									'B', 'R', 'view_payment_customer_info의 시간변경으로 지급', '".$add_reserve."', '".$now_reserve."'
								)
							";
							$result = mysql_query($sql);
							if($result){
								// OK
							}else{
								// ERR - 회원 적립내역 생성 실패(tb_user_reserve_log)
								$err_msg = "회원 적립내역 생성 실패(tb_user_reserve_log)";
							}
						}else{
							// ERR - 회원 적립금 적립 실패(tb_user_reserve)
							$err_msg = "회원 적립금 적립 실패(tb_user_reserve)";
						}					
					}
				}else{
					// PASS - 거래금액 0원 이하는 skip
				}
			}else{
				// PASS - 적립금 사용 안하면 안줘도 되니 skip
			}
		}else{
			// PASS - cron이 줄꺼기 때문에 skip
		}

		if($err_msg != ""){
			$return['alert']['update'] = $return['alert']['update']." [".$err_msg."] ";
		}
    } else {
        $return['alert']['update'] = '예약정보의 변경사항이 없습니다.';
    }
    echo json_encode($return ,JSON_UNESCAPED_UNICODE);
    exit;
} else {
    $return['res']['update'] = false;
    $return['error']['update'] = mysql_error();
    $return['errno']['update'] = mysql_errno();
    $return['alert']['update'] = '예약정보 변경이 실패되었습니다.';
    echo json_encode($return, JSON_UNESCAPED_UNICODE);
    exit;
}
?>