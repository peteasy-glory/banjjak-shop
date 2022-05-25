<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$data = array();
	$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
	$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
	$r_payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";
	$r_discount_type = ($_POST["discount_type"] && $_POST["discount_type"] != "")? $_POST["discount_type"] : "";
	$r_discount_num = ($_POST["discount_num"] && $_POST["discount_num"] != "")? $_POST["discount_num"] : "";
	$r_local_price = ($_POST["local_price"] && $_POST["local_price"] != "")? $_POST["local_price"] : "";
	$r_local_price_cash = ($_POST["local_price_cash"] && $_POST["local_price_cash"] != "")? $_POST["local_price_cash"] : "";

	if($r_artist_id != "" && $r_cellphone != ""){
		if($r_payment_log_seq !=""){

			if($r_discount_type != "" && $r_discount_num != ""){
				$sql = "
					UPDATE tb_payment_log SET
						discount_type = '".$r_discount_type."',
						discount_num = '".$r_discount_num."',
						local_price = '".$r_local_price."',
						local_price_cash = '".$r_local_price_cash."',
						update_time = NOW()
					WHERE is_cancel = '0'
						AND artist_id = '".$r_artist_id."'
						AND cellphone = '".$r_cellphone."'
						AND payment_log_seq = '".$r_payment_log_seq."'
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "할인에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{ 
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>