<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$data = array();
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_reserve"){ // 적립금 여부 확인
			$r_sr_seq = ($_POST["sr_seq"] && $_POST["sr_seq"] != "")? $_POST["sr_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$where_qy = "";

			if($r_sr_seq != ""){ // 적립금 불러올때 사용
				$where_qy .= " AND sr_seq = '".$r_sr_seq."' ";
			}

			if($r_artist_id != ""){
				$sql = "
					SELECT *
					FROM tb_shop_reserve
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				$cnt = mysqli_num_rows($result);
				if($cnt > 0){
					while($row = mysqli_fetch_assoc($result)){
						$data = $row;
					}

					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "000002", "data" => "적립금 설정을 찾을 수 없습니다.".$sql);	
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");	
			}
		}else if($r_mode == "set_insert_reserve"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_artist_id != ""){
				$sql = "
					INSERT INTO tb_shop_reserve (
						`artist_id`
					) VALUES (
						'".$r_artist_id."'
					)
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$sr_seq = mysqli_insert_id($connection);
					$data = array(
						"sr_seq" => $sr_seq,
						"artist_id" => $r_artist_id,
						"is_use" => "1",
						"percent" => "0",
						"min_reserve" => "0"
					);
					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "000002", "data" => "회원이 존재하지 않습니다.");	
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_reserve"){
			$r_sr_seq = ($_POST["sr_seq"] && $_POST["sr_seq"] != "")? $_POST["sr_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_is_use = ($_POST["is_use"] && $_POST["is_use"] != "")? $_POST["is_use"] : "";
			$r_percent = ($_POST["percent"] != "")? $_POST["percent"] : "";
			$r_min_reserve = ($_POST["min_reserve"] != "")? $_POST["min_reserve"] : "";
			$update_qy = "";

			if($r_is_use != ""){
				$update_qy .= " is_use = '".$r_is_use."', ";
			}
			if($r_percent != ""){
				$update_qy .= " percent = '".$r_percent."', ";
			}
			if($r_min_reserve != ""){
				$update_qy .= " min_reserve = '".$r_min_reserve."', ";
			}

			if($r_artist_id != "" && $r_sr_seq != ""){
				$sql = "
					UPDATE tb_shop_reserve SET
						".$update_qy."
						update_dt = NOW()
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						AND sr_seq = '".$r_sr_seq."'
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "적립금 설정 변경에 실패했습니다.".$sql);	
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_reserve"){
			$r_sr_seq = ($_POST["sr_seq"] && $_POST["sr_seq"] != "")? $_POST["sr_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";

			if($r_delete_id != "" && $r_sr_seq != ""){
				$sql = "
					UPDATE tb_shop_reserve SET
						is_delete = '1',
						delete_msg = '".$r_delete_id."|적립금 설정 직접 취소',
						delete_dt = NOW()
					WHERE is_delete = '2'
						AND artist_id = '".$r_delete_id."'
						AND sr_seq = '".$r_sr_seq."'
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "적립금 설정 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_user_reserve"){
			$r_ur_seq = ($_POST["ur_seq"] && $_POST["ur_seq"] != "")? $_POST["ur_seq"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$where_qy = "";

			if($r_ur_seq != ""){ // 적립금 불러올때 사용
				$where_qy .= " AND ur_seq = '".$r_ur_seq."' ";
			}

			if($r_customer_id != ""){ // 정회원
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}else{
				if($r_tmp_seq != "" && $r_cellphone != ""){ // 가회원
					$where_qy .= " AND tmp_seq = '".$r_tmp_seq."' ";
					$where_qy .= " AND cellphone = '".$r_cellphone."' ";
				}
			}

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "")){
				$sql = "
					SELECT *
					FROM tb_user_reserve
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data, "sql" => $sql);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_user_reserve"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_reserve = ($_POST["reserve"] && $_POST["reserve"] != "")? $_POST["reserve"] : "0";

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "") && $r_reserve != ""){
				if((int)$r_reserve > 0){
					$sql = "
						INSERT INTO tb_user_reserve (
							`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `reserve`
						) VALUES (
							'".$r_customer_id."', '".$r_tmp_seq."', '".$r_cellphone."', '".$r_artist_id."', '".$r_reserve."'
						)
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$ur_seq = mysqli_insert_id($connection);
						$data = array(
							"ur_seq" => $ur_seq,
							"customer_id" => $r_customer_id,
							"tmp_seq" => $r_tmp_seq,
							"cellphone" => $r_cellphone,
							"artist_id" => $r_artist_id,
							"reserve" => $r_reserve
						);
						$return_data = array("code" => "000000", "data" => $data);
					}else{
						$return_data = array("code" => "000101", "data" => "회원 적립금 생성에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000002", "data" => "적립금이 올바르지 않습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_user_reserve"){
			$r_ur_seq = ($_POST["ur_seq"] && $_POST["ur_seq"] != "")? $_POST["ur_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_add_reserve = ($_POST["add_reserve"] != "")? $_POST["add_reserve"] : "";
			$r_use_reserve = ($_POST["use_reserve"] != "")? $_POST["use_reserve"] : "";
			$r_now_reserve = ($_POST["now_reserve"] != "")? $_POST["now_reserve"] : "";
			$update_qy = "";
			$where_qy = "";

			if($r_add_reserve != ""){
				$now_reserve = $r_now_reserve + $r_add_reserve;
				$update_qy .= " reserve = '".$now_reserve."', ";
			}

			if($r_use_reserve != ""){
				$now_reserve = $r_now_reserve - $r_use_reserve;
				$update_qy .= " reserve = '".$now_reserve."', ";
			}

			if($r_customer_id != ""){ // 정회원
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}else{
				if($r_tmp_seq != "" && $r_cellphone != ""){ // 가회원
					$where_qy .= " AND tmp_seq = '".$r_tmp_seq."' ";
					$where_qy .= " AND cellphone = '".$r_cellphone."' ";
				}
			}
			if($r_ur_seq != ""){
				$where_qy .= " AND ur_seq = '".$r_ur_seq."' ";
			}

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "") && $r_now_reserve != ""){
				if((int)$r_now_reserve > 0){
					$sql = "
						UPDATE tb_user_reserve SET
							".$update_qy."
							update_dt = NOW()
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
							".$where_qy."
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000002", "data" => "회원 적립금 변경에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000003", "data" => "적립금이 올바르지 않습니다.");
				}
			}else{
				$return_data = array("code" => "000401", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_user_reserve"){
			$r_ur_seq = ($_POST["ur_seq"] && $_POST["ur_seq"] != "")? $_POST["ur_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";

			if($r_delete_id != "" && $r_ur_seq != ""){
				$sql = "
					UPDATE tb_user_reserve SET
						is_delete = '1',
						delete_msg = '".$r_delete_id."|회원 적립금 직접 취소',
						delete_dt = NOW()
					WHERE is_delete = '2'
						AND delete_id = '".$r_delete_id."'
						AND ur_seq = '".$r_ur_seq."'
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "회원 적립금 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_user_reserve_log"){ ////////////////// ******************* 메모 및 사용처, 사용내역 추가 필요 ******************** ///////////////
			$r_url_seq = ($_POST["url_seq"] && $_POST["url_seq"] != "")? $_POST["url_seq"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";
			$r_service_type = ($_POST["service_type"] && $_POST["service_type"] != "")? $_POST["service_type"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_memo = ($_POST["memo"] && $_POST["memo"] != "")? $_POST["memo"] : "";
			$r_orderby = ($_POST["orderby"] && $_POST["orderby"] != "")? $_POST["orderby"] : "";
			$where_qy = "";
			$order_qy = "";

			if($r_url_seq != ""){ // 적립금 불러올때 사용
				$where_qy .= " AND url_seq = '".$r_url_seq."' ";
			}
			if($r_payment_log_seq != ""){
				$where_qy .= " AND payment_log_seq = '".$r_payment_log_seq."' ";
			}
			if($r_service_type != ""){
				$where_qy .= " AND service_type = '".$r_service_type."' ";
			}
			if($r_type != ""){
				$where_qy .= " AND type = '".$r_type."' ";
			}
			if($r_memo != ""){
				$where_qy .= " AND memo = '".$r_memo."' ";
			}

			if($r_customer_id != ""){ // 정회원
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}else{
				if($r_tmp_seq != "" && $r_cellphone != ""){ // 가회원
					$where_qy .= " AND tmp_seq = '".$r_tmp_seq."' ";
					$where_qy .= " AND cellphone = '".$r_cellphone."' ";
				}
			}

			if($r_orderby != ""){
				$order_qy .= " ORDER BY ifnull(update_dt, reg_dt) DESC ";
			}

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "")){
				$sql = "
					SELECT *
					FROM tb_user_reserve_log
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
						".$order_qy."
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_user_reserve_log_validate"){
			$r_url_seq = ($_POST["url_seq"] && $_POST["url_seq"] != "")? $_POST["url_seq"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_customer_id != ""){ // 정회원
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}else{
				if($r_tmp_seq != "" && $r_cellphone != ""){ // 가회원
					$where_qy .= " AND tmp_seq = '".$r_tmp_seq."' ";
					$where_qy .= " AND cellphone = '".$r_cellphone."' ";
				}
			}

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "")){
				$sql = "
					SELECT *
					FROM tb_user_reserve
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				$row = mysqli_fetch_assoc($result);
				$reserve = $row["reserve"];

				$sql = "
					SELECT *
					FROM tb_user_reserve_log
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
					ORDER BY reg_dt DESC
					LIMIT 0, 1
				";
				$result = mysqli_query($connection, $sql);
				$row = mysqli_fetch_assoc($result);
				$now_reserve = $row["now_reserve"];

				if($reserve == $now_reserve){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "적립금 검증에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_user_reserve_log"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";
			$r_service_type = ($_POST["service_type"] && $_POST["service_type"] != "")? $_POST["service_type"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_memo = ($_POST["memo"] && $_POST["memo"] != "")? $_POST["memo"] : "";
			$r_add_reserve = ($_POST["add_reserve"] && $_POST["add_reserve"] != "")? $_POST["add_reserve"] : "0";
			$r_use_reserve = ($_POST["use_reserve"] && $_POST["use_reserve"] != "")? $_POST["use_reserve"] : "0";
			$r_now_reserve = ($_POST["now_reserve"] && $_POST["now_reserve"] != "")? $_POST["now_reserve"] : "0";

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "") && $r_payment_log_seq != ""){
				if(((int)$r_add_reserve > 0 || (int)$r_use_reserve > 0) && (int)$r_now_reserve > 0){

					if($r_add_reserve != ""){
						$now_reserve = $r_now_reserve + $r_add_reserve;
					}

					if($r_use_reserve != ""){
						$now_reserve = $r_now_reserve - $r_use_reserve;
					}

					$sql = "
						INSERT INTO tb_user_reserve_log (
							`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `payment_log_seq`, 
							`service_type`, `type`, `memo`, `add_reserve`, `use_reserve`, 
							`now_reserve`
						) VALUES (
							'".$r_customer_id."', '".$r_tmp_seq."', '".$r_cellphone."', '".$r_artist_id."', '".$r_payment_log_seq."', 
							'".$r_service_type."', '".$r_type."', '".$r_memo."', '".$r_add_reserve."', '".$r_use_reserve."', 
							'".$now_reserve."'
						)
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$url_seq = mysqli_insert_id($connection);
						$data = array(
							"url_seq" => $url_seq,
							"customer_id" => $r_customer_id,
							"tmp_seq" => $r_tmp_seq,
							"cellphone" => $r_cellphone,
							"artist_id" => $r_artist_id,
							"payment_log_seq" => $r_payment_log_seq,
							"service_type" => $r_service_type,
							"type" => $r_type,
							"memo" => $r_memo,
							"add_reserve" => $r_add_reserve,
							"use_reserve" => $r_use_reserve,
							"now_reserve" => $r_now_reserve,
						);
						$return_data = array("code" => "000000", "data" => $data);
					}else{
						$return_data = array("code" => "000501", "data" => "회원 적립금 생성에 실패했습니다.".$sql);
					}
				}else{
					$return_data = array("code" => "000001", "data" => "적립금이 올바르지 않습니다.");
				}
			}else{
				$return_data = array("code" => "000501", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_user_reserve_log"){
			$r_url_seq = ($_POST["url_seq"] && $_POST["url_seq"] != "")? $_POST["url_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";
			$r_service_type = ($_POST["service_type"] && $_POST["service_type"] != "")? $_POST["service_type"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_memo = ($_POST["memo"] && $_POST["memo"] != "")? $_POST["memo"] : "";
			$r_add_reserve = ($_POST["add_reserve"] && $_POST["add_reserve"] != "")? $_POST["add_reserve"] : "0";
			$r_use_reserve = ($_POST["use_reserve"] && $_POST["use_reserve"] != "")? $_POST["use_reserve"] : "0";
			$r_now_reserve = ($_POST["now_reserve"] && $_POST["now_reserve"] != "")? $_POST["now_reserve"] : "0";
			$update_qy = "";
			$where_qy = "";

			if($r_add_reserve != ""){
				$update_qy = " add_reserve = '".$r_add_reserve."', ";
			}
			if($r_use_reserve != ""){
				$update_qy = " use_reserve = '".$r_use_reserve."', ";
			}
			if($r_now_reserve != ""){
				$update_qy = " now_reserve = '".$r_now_reserve."', ";
			}

			if($r_customer_id != ""){ // 정회원
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}else{
				if($r_tmp_seq != "" && $r_cellphone != ""){ // 가회원
					$where_qy .= " AND tmp_seq = '".$r_tmp_seq."' ";
					$where_qy .= " AND cellphone = '".$r_cellphone."' ";
				}
			}
			if($r_url_seq != ""){
				$where_qy .= " AND url_seq = '".$r_url_seq."' ";
			}

			if($r_artist_id != "" && ($r_customer_id != "" || $r_tmp_seq != "") && $r_reserve != ""){
				$sql = "
					UPDATE tb_user_reserve_log SET
						".$update_dt."
						update_dt = NOW()
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "회원 적립금 변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_user_reserve_log"){
			$r_url_seq = ($_POST["url_seq"] && $_POST["url_seq"] != "")? $_POST["url_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";

			if($r_delete_id != "" && $r_url_seq != ""){
				$sql = "
					UPDATE tb_user_reserve_log SET
						is_delete = '1',
						delete_msg = '".$r_delete_id."|회원 적립금 직접 취소',
						delete_dt = NOW()
					WHERE is_delete = '2'
						AND delete_id = '".$r_delete_id."'
						AND url_seq = '".$r_url_seq."'
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "회원 적립금 삭제에 실패했습니다.");
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