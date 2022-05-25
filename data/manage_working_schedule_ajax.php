<?php

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	$data = array();

	if($r_mode){
		if($r_mode == "get_working_schedule"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";

			if($r_artist_id != "" && $r_type != ""){
				if($r_type == "B"){ // 미용
					$sql = "
						SELECT *
						FROM tb_working_schedule
						WHERE customer_id = '".$r_artist_id."'
					"; // 영업시간, 공휴일 영업여부
					$result = mysqli_query($connection,$sql);
					$row = mysqli_fetch_assoc($result);
					$data["day_start"] = $row["working_start"];
					$data["day_end"] = $row["working_end"];
					$data["is_rest_holiday"] = $row["rest_public_holiday"];
					$data["id_1"] = mysqli_num_rows($result);

					$sql = "
						SELECT *
						FROM tb_time_off
						WHERE customer_id = '".$r_artist_id."'
					"; // 점심시간/휴식시간 설정
					$result = mysqli_query($connection,$sql);
					$row = mysqli_fetch_assoc($result);
					$data["rest_time"] = $row["res_time_off"];
					$data["id_2"] = mysqli_num_rows($result);

					$sql = "
						SELECT *
						FROM tb_time_schedule
						WHERE artist_id = '".$r_artist_id."'
					"; // 타임제 스케줄
					$result = mysqli_query($connection,$sql);
//					$row = mysqli_fetch_assoc($result);
//					$data["rest_time_schedule"] = $row["res_time_off"];

					while($row = mysqli_fetch_assoc($result)){
//						$tmp_data["res_time_off"] = $row["res_time_off"];
						$data["rest_time_schedule"][] = $row["res_time_off"];
						$data["artist_name"][] = $row["artist_name"];
					}
					$data["id_3"] = mysqli_num_rows($result);

					$sql = "
						SELECT *
						FROM tb_shop
						WHERE customer_id = '".$r_artist_id."'
					"; // 자유시간제/타임제 여부
					$result = mysqli_query($connection,$sql);
					$row = mysqli_fetch_assoc($result);
					$data["is_time_type"] = $row["is_time_type"];

					$sql = "
						SELECT *
						FROM tb_regular_holiday
						WHERE customer_id = '".$r_artist_id."'
					"; // 정기휴일 여부
					$result = mysqli_query($connection,$sql);
					$row = mysqli_fetch_assoc($result);
					$data["weekend_holiday"] = $row["is_sunday"].$row["is_monday"].$row["is_tuesday"].$row["is_wednesday"].$row["is_thursday"].$row["is_friday"].$row["is_saturday"];
					$data["id_4"] = mysqli_num_rows($result);

				}else{ // 호텔, 유치원/놀이방
					$sql = "
						SELECT *
						FROM tb_shop_schedule
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
							AND type = '".$r_type."'
					";
					$result = mysqli_query($connection,$sql);
					$data = mysqli_fetch_assoc($result);
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_submit_shop_schedule"){ // 펫샵 미용 스케쥴 생성/수정 통합 모듈
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_day_start = ($_POST["day_start"] != "")? $_POST["day_start"] : "";
			$r_day_end = ($_POST["day_end"] != "")? $_POST["day_end"] : "";
			$r_is_rest_holiday = ($_POST["is_rest_holiday"] != "")? $_POST["is_rest_holiday"] : "";
			$r_weekend_holiday = ($_POST["weekend_holiday"] && $_POST["weekend_holiday"] != "")? $_POST["weekend_holiday"] : "";
			$r_rest_time = ($_POST["rest_time"] && $_POST["rest_time"] != "")? $_POST["rest_time"] : "";
			$r_artist_list = ($_POST["artist_list"] && $_POST["artist_list"] != "")? $_POST["artist_list"] : "";
//			$r_rest_time_schedule = ($_POST["rest_time_schedule"] && $_POST["rest_time_schedule"] != "")? $_POST["rest_time_schedule"] : "";
			$r_is_time_type = ($_POST["is_time_type"] && $_POST["is_time_type"] != "")? $_POST["is_time_type"] : "1";
			$r_id_1 = ($_POST["id_1"] && $_POST["id_1"] != "")? $_POST["id_1"] : "";
			$r_id_2 = ($_POST["id_2"] && $_POST["id_2"] != "")? $_POST["id_2"] : "";
			$r_id_3 = ($_POST["id_3"] && $_POST["id_3"] != "")? $_POST["id_3"] : "";
			$r_id_4 = ($_POST["id_4"] && $_POST["id_4"] != "")? $_POST["id_4"] : "";

			if($r_artist_id != ""){
				if($r_type == "B"){
					$tmp_sql = ''; // test_data
					// 0.데이터 가공
					$rest_time = implode(',', $r_rest_time);
//					$rest_time_schedule = implode(',', $r_rest_time_schedule);
					$weekend_holiday = array();
					for($_i = 0; $_i < 7; $_i++){
						$weekend_holiday[$_i] = (in_array($_i, $r_weekend_holiday))? 1 : 0;
					}

					// 1.영업시간 저장
					if($r_id_1 == ""){
						$sql = "
							INSERT INTO tb_working_schedule (
								`customer_id`, `working_start`, `working_end`, `rest_public_holiday`, `update_time`
							) VALUES (
								'".$r_artist_id."', '".$r_day_start."', '".$r_day_end."', '".$r_is_rest_holiday."', NOW()
							)
						";
					}else{
						$sql = "
							UPDATE tb_working_schedule SET
								`working_start` = '".$r_day_start."',
								`working_end` = '".$r_day_end."', 
								`rest_public_holiday` = '".$r_is_rest_holiday."',
								`update_time` = NOW()
							WHERE `customer_id` = '".$r_artist_id."'
						";
					}
					$result = mysqli_query($connection,$sql);
					//$result = true;
					if($result){
						//$tmp_sql .= $sql."<br/>";
						// 2.점심/휴식시간 설정
						if($r_id_2 == ""){
							$sql = "
								INSERT INTO tb_time_off (
									`customer_id`, `res_time_off`, `res_time_cnt`, `res_time_off_yn`, `reg_date`,
									`update_date`
								) VALUES (
									'".$r_artist_id."', '".$rest_time."', '', 'Y', NOW(),
									NOW()
								)
							";
							$sqlw = $sql;
						}else{
							$sql = "
								UPDATE tb_time_off SET
									`res_time_off` = '".$rest_time."',
									`res_time_cnt` = '',
									`res_time_off_yn` = 'Y',
									`reg_date` = NOW()
								WHERE `customer_id` = '".$r_artist_id."'
							";
						}
						$result = mysqli_query($connection,$sql);
						//$result = true;
						if($result){
							//$tmp_sql .= $sql."<br/>";
							// 3.예약 운영방식 선택
							if($r_id_3 != ""){
								$delete_sql = "
									DELETE FROM tb_time_schedule 
									WHERE artist_id = '".$r_artist_id."'
								";
								$delete_result = mysqli_query($delete_sql);
							}

							$insert_qy = "";
							for($_i = 0; $_i < count($r_artist_list); $_i++){
								$r_rest_time_schedule[$_i] = ($_POST["rest_time_schedule_".$_i] && $_POST["rest_time_schedule_".$_i] != "")? $_POST["rest_time_schedule_".$_i] : "";
								$rest_time_schedule[$_i] = implode(',', $r_rest_time_schedule[$_i]);
								$insert_qy .= "('".$r_artist_id."', '".$r_artist_list[$_i]."', '".$rest_time_schedule[$_i]."', NOW()),";
							}
							$insert_qy = substr($insert_qy, 0, -1);

							$sql = "
								INSERT INTO tb_time_schedule (
									`artist_id`, `artist_name`, `res_time_off`, `reg_date`
								) VALUES ".$insert_qy."
							";
							$result = mysqli_query($connection,$sql);
							//$result = true;
							if($result){
								//$tmp_sql .= $sql."<br/>";
								// 3-1. tb_shop.is_time_type update
								$sql = "
									UPDATE tb_shop SET
										`is_time_type` = '".$r_is_time_type."'
									WHERE `customer_id` = '".$r_artist_id."'
								";
								$result = mysqli_query($connection,$sql);
								//$result = true;
								if($result){
									//$tmp_sql .= $sql."<br/>";
									// 4.정기휴일 설정
									if($r_id_4 == ""){
										$sql = "
											INSERT INTO tb_regular_holiday (
												`customer_id`, `is_sunday`, `is_monday`, `is_tuesday`, `is_wednesday`, 
												`is_thursday`, `is_friday`, `is_saturday`, `update_time`
											) VALUES (
												'".$r_artist_id."', '".$weekend_holiday[0]."', '".$weekend_holiday[1]."', '".$weekend_holiday[2]."', '".$weekend_holiday[3]."', 
												'".$weekend_holiday[4]."', '".$weekend_holiday[5]."', '".$weekend_holiday[6]."', NOW()
											)
										";
									}else{
										$sql = "
											UPDATE tb_regular_holiday SET
												`is_sunday` = '".$weekend_holiday[0]."', 
												`is_monday` = '".$weekend_holiday[1]."', 
												`is_tuesday` = '".$weekend_holiday[2]."', 
												`is_wednesday` = '".$weekend_holiday[3]."', 
												`is_thursday` = '".$weekend_holiday[4]."', 
												`is_friday` = '".$weekend_holiday[5]."', 
												`is_saturday` = '".$weekend_holiday[6]."', 
												`update_time` = NOW()
											WHERE `customer_id` = '".$r_artist_id."'
										";
									}
									$result = mysqli_query($connection,$sql);
									//$result = true;
									if($result){
										//$tmp_sql .= $sql."<br/>";

										$return_data = array("code" => "000000", "data" => "OK");
									}else{
										$return_data = array("code" => "000008", "data" => "영업시간/일정관리 등록에 실패했습니다.");
									}
								}else{
									$return_data = array("code" => "000007", "data" => "영업시간/일정관리 등록에 실패했습니다.");
								}
							}else{
								$return_data = array("code" => "000006", "data" => "영업시간/일정관리 등록에 실패했습니다.".$sqlw);	
							}
						}else{
							$return_data = array("code" => "000005", "data" => "영업시간/일정관리 등록에 실패했습니다.".$sqlw);
						}
					}else{
						$return_data = array("code" => "000004", "data" => "영업시간/일정관리 등록에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000009", "data" => "중요 데이터가 누락되었습니다.");
				}
			}else{
				$return_data = array("code" => "000003", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_shop_schedule"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_day_start = ($_POST["day_start"] != "")? $_POST["day_start"] : "";
			$r_day_end = ($_POST["day_end"] != "")? $_POST["day_end"] : "";
			$r_is_rest_holiday = ($_POST["is_rest_holiday"] && $_POST["is_rest_holiday"] != "")? $_POST["is_rest_holiday"] : "";
			$r_weekend_holiday = ($_POST["weekend_holiday"] && $_POST["weekend_holiday"] != "")? $_POST["weekend_holiday"] : "";
			$r_rest_time = ($_POST["rest_time"] && $_POST["rest_time"] != "")? $_POST["rest_time"] : "";

			if($r_artist_id != ""){
				if($r_type != "B"){
					$weekend_holiday = "";
					for($_i = 0; $_i < 7; $_i++){
						$weekend_holiday .= (in_array($_i, $r_weekend_holiday))? "1" : "0";
					}
					$r_rest_time = implode(',', $r_rest_time);

					$sql = "
						SELECT *
						FROM tb_shop_schedule
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
							AND type = '".$r_type."'
					";
					$result = mysqli_query($connection,$sql);
					$ss_cnt = mysqli_num_rows($result);

					if($ss_cnt == 0){
						$sql = "
							INSERT INTO tb_shop_schedule (
								`artist_id`, `type`, `day_start`, `day_end`, `is_rest_holiday`, 
								`weekend_holiday`, `rest_time`
							) VALUES (
								'".$r_artist_id."', '".$r_type."', '".$r_day_start."', '".$r_day_end."', '".$r_is_rest_holiday."', 
								'".$weekend_holiday."', '".$r_rest_time."'
							)
						";
						$result = mysqli_query($connection,$sql);
						if($result){
							$return_data = array("code" => "000000", "data" => "OK");
						}else{
							$return_data = array("code" => "000001", "data" => "영업시간/일정관리 등록에 실패했습니다.");
						}
					}else{
						$return_data = array("code" => "000002", "data" => "이미 일정이 등록되어 있습니다.");
					}
				}else{
					$return_data = array("code" => "000004", "data" => "중요 데이터가 누락되었습니다.");
				}
			}else{
				$return_data = array("code" => "000003", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_shop_schedule"){
			$r_ss_seq = ($_POST["ss_seq"] && $_POST["ss_seq"] != "")? $_POST["ss_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_day_start = ($_POST["day_start"] != "")? $_POST["day_start"] : "";
			$r_day_end = ($_POST["day_end"] != "")? $_POST["day_end"] : "";
			$r_is_rest_holiday = ($_POST["is_rest_holiday"] && $_POST["is_rest_holiday"] != "")? $_POST["is_rest_holiday"] : "";
			$r_weekend_holiday = ($_POST["weekend_holiday"] && $_POST["weekend_holiday"] != "")? $_POST["weekend_holiday"] : "";
			$r_rest_time = ($_POST["rest_time"] && $_POST["rest_time"] != "")? $_POST["rest_time"] : "";
			$update_qy = "";

			$weekend_holiday = "";
			for($_i = 0; $_i < 7; $_i++){
				$weekend_holiday .= (in_array($_i, $r_weekend_holiday))? "1" : "0";
			}
			$r_rest_time = implode(',', $r_rest_time);

			if($r_day_start != ""){
				$update_qy .= " day_start = '".$r_day_start."', ";
			}
			if($r_day_end != ""){
				$update_qy .= " day_end = '".$r_day_end."', ";
			}
			if($r_is_rest_holiday != ""){
				$update_qy .= " is_rest_holiday = '".$r_is_rest_holiday."', ";
			}
			if($r_weekend_holiday != ""){
				$update_qy .= " weekend_holiday = '".$weekend_holiday."', ";
			}else{
				$update_qy .= " weekend_holiday = '0000000', ";
			}
			if($r_rest_time != ""){
				$update_qy .= " rest_time = '".$r_rest_time."', ";
			}else{
				$update_qy .= " rest_time = '', ";
			}
			
			if($r_artist_id != "" && $r_type != "" & $r_ss_seq != ""){
				if($r_type != "B"){
					$sql = "
						UPDATE tb_shop_schedule SET 
							".$update_qy."
							update_dt = NOW()
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
							AND ss_seq = '".$r_ss_seq."'
							AND type = '".$r_type."'
					";
					$result = mysqli_query($connection,$sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000001", "data" => "영업시간/일정관리 변경에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000003", "data" => "중요 데이터가 누락 되었습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_delete_shop_schedule"){
			$r_ss_seq = ($_POST["ss_seq"] && $_POST["ss_seq"] != "")? $_POST["ss_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";
			$r_delete_msg = ($_POST["delete_msg"] && $_POST["delete_msg"] != "")? $_POST["delete_msg"] : "";

			if($r_ss_seq != ""){
				$sql = "
					UPDATE tb_shop_schedule SET
						is_delete = '1',
						delete_msg = '".$r_delete_id."|".$r_delete_msg."',
						delete_dt = NOW()
					WHERE is_delete = '2'
						AND ss_seq = '".$r_ss_seq."'
				";
				$result = mysqli_query($connection,$sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "영업시간/일정관리 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_private_holiday"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
			$where_qy = "";

			if($r_type == "B"){
				$sql = "
					SELECT 
						ph.ph_seq AS phh_seq,
						ph.customer_id AS artist_id,
						ph.worker, 
						(SELECT nicname FROM tb_artist_list WHERE NAME = ph.worker AND artist_id = ph.customer_id GROUP BY artist_id) AS worker_name,
						if(ph.type = 'notall', '2', '1') AS is_allday,
						date_format(concat(ph.start_year,'-',ph.start_month,'-',ph.start_day), '%Y-%m-%d') AS check_in_date,
						time_format(concat(ph.start_hour,':',IFNULL(ph.start_minute, '00'),':00'), '%H:%i:%s') AS check_in_time,
						date_format(concat(ph.end_year,'-',ph.end_month,'-',ph.end_day), '%Y-%m-%d') AS check_out_date,
						time_format(concat(ph.end_hour,':',IFNULL(ph.end_minute, '00'),':00'), '%H:%i:%s') AS check_out_time,
						ph.update_time AS update_dt
					FROM tb_private_holiday AS ph
					WHERE ph.customer_id = '".$r_artist_id."'
						AND ph.type <> 'not'
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$tmp_data["phh_seq"] = $row["phh_seq"];
					$tmp_data["artist_id"] = $row["artist_id"];
					$tmp_data["worker"] = $row["worker"];
					$tmp_data["worker_name"] = $row["worker_name"];
					$tmp_data["type"] = $r_type;
					$tmp_data["is_allday"] = $row["is_allday"];
					$tmp_data["check_in_date"] = $row["check_in_date"];
					$tmp_data["check_in_time"] = $row["check_in_time"];
					$tmp_data["check_out_date"] = $row["check_out_date"];
					$tmp_data["check_out_time"] = $row["check_out_time"];
					$tmp_data["update_dt"] = $row["update_dt"];
					$data[] = $tmp_data;
				}
			}else{
				if($r_year != "" && $r_month != ""){
					$where_qy .= " AND check_in_date BETWEEN '".$r_year."-".$r_month."-01' AND '".$r_year."-".$r_month."-31' ";
				}

				$sql = "
					SELECT *
					FROM tb_private_holiday_hotel
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
						AND type = '".$r_type."'
						".$where_qy."
				";
				$result = mysqli_query($connection,$sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_public_holiday"){
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";

			$sql = "
				SELECT *
				FROM tb_public_holiday
				WHERE year = '".$r_year."'
					AND month = '".$r_month."'
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_playroom_payment_log"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";

			$sql = "
				SELECT *
				FROM tb_playroom_payment_log AS ppl
					INNER JOIN tb_playroom_reservation AS pr ON ppl.order_num = pr.order_num
				WHERE ppl.is_delete = '2'
					AND pr.is_delete = '2'
					AND ppl.artist_id = '".$r_artist_id."'
					AND '".$r_year."-".$r_month."-01' <= pr.check_out_date AND pr.check_in_date <= '".$r_year."-".$r_month."-31'
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_hotel_payment_log"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";

			$sql = "
				SELECT *
				FROM tb_hotel_payment_log AS hpl
					INNER JOIN tb_hotel_reservation AS hr ON hpl.order_num = hr.order_num
				WHERE hpl.is_delete = '2'
					AND hr.is_delete = '2'
					AND hpl.artist_id = '".$r_artist_id."'
					AND '".$r_year."-".$r_month."-01' <= hr.check_out_date AND hr.check_in_date <= '".$r_year."-".$r_month."-31'
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_beauty_payment_log"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";

			$sql = "
				SELECT *
				FROM tb_payment_log
				WHERE is_cancel = 0
					AND artist_id = '".$r_artist_id."'
					AND DATE_FORMAT(concat(year,'-',month,'-',day)) BETWEEN '".$r_year."-".$r_month."-01' AND '".$r_year."-".$r_month."-31'
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_artist_list"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT *
				FROM tb_artist_list
				WHERE artist_id = '".$r_artist_id."'
				AND is_view = '2'
				GROUP BY nicname
				ORDER BY is_main DESC, seq
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_private_holiday"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_is_allday = ($_POST["is_allday"] && $_POST["is_allday"] != "")? $_POST["is_allday"] : "";
			$r_check_in_date = ($_POST["check_in_date"] && $_POST["check_in_date"] != "")? $_POST["check_in_date"] : "";
			$r_check_in_time = ($_POST["check_in_time"] && $_POST["check_in_time"] != "")? $_POST["check_in_time"] : "";
			$r_check_out_date = ($_POST["check_out_date"] && $_POST["check_out_date"] != "")? $_POST["check_out_date"] : "";
			$r_check_out_time = ($_POST["check_out_time"] && $_POST["check_out_time"] != "")? $_POST["check_out_time"] : "";
			$r_worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";

			if($r_artist_id != ""){
				if($r_type == "B"){
					$is_allday = ($r_is_allday == "1")? "all" : "notall";
					$start_year = date("Y", STRTOTIME($r_check_in_date));
					$start_month = date("m", STRTOTIME($r_check_in_date));
					$start_day = date("d", STRTOTIME($r_check_in_date));
					$start_hour = date("H", STRTOTIME($r_check_in_date." ".$r_check_in_time));
					$start_minute = date("i", STRTOTIME($r_check_in_date." ".$r_check_in_time));
					$end_year = date("Y", STRTOTIME($r_check_out_date));
					$end_month = date("m", STRTOTIME($r_check_out_date));
					$end_day = date("d", STRTOTIME($r_check_out_date));
					$end_hour = date("H", STRTOTIME($r_check_out_date." ".$r_check_out_time));
					$end_minute = date("i", STRTOTIME($r_check_out_date." ".$r_check_out_time));
					$sql = "
						INSERT INTO tb_private_holiday (
							`customer_id`, `worker`, `type`, `start_year`, `start_month`, 
							`start_day`, `start_hour`, `start_minute`, `end_year`, `end_month`, 
							`end_day`, `end_hour`, `end_minute`, `update_time`
						) VALUES 
					";
					foreach($r_worker AS $key => $value){
						$sql .= "(";
						$sql .= "	'".$r_artist_id."', '".$value["name"]."', '".$is_allday."', '".$start_year."', '".$start_month."', ";
						$sql .= "	'".$start_day."', '".$start_hour."', '".$start_minute."', '".$end_year."', '".$end_month."', ";
						$sql .= "	'".$end_day."', '".$end_hour."', '".$end_minute."', NOW() ";
						$sql .= "),";
					}
					$sql = substr($sql, 0, -1);
					$result = mysqli_query($connection,$sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000001", "data" => "임시휴무 생성에 실패했습니다.");
					}
				}else{
					$sql = "
						INSERT INTO tb_private_holiday_hotel (
							`artist_id`, `type`, `is_allday`, `check_in_date`, `check_in_time`, 
							`check_out_date`, `check_out_time`
						) VALUES (
							'".$r_artist_id."', '".$r_type."', '".$r_is_allday."', '".$r_check_in_date."', '".$r_check_in_time."', 
							'".$r_check_out_date."', '".$r_check_out_time."'
						)
					";
					$result = mysqli_query($connection,$sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000001", "data" => "임시휴무 생성에 실패했습니다.");
					}
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_delete_private_holiday"){
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";
			$r_delete_msg = ($_POST["delete_msg"] && $_POST["delete_msg"] != "")? $_POST["delete_msg"] : "";
			$r_phh_seq = ($_POST["phh_seq"] && $_POST["phh_seq"] != "")? $_POST["phh_seq"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";

			if($r_phh_seq != "" && $r_type != "" && $r_delete_id != ""){
				if($r_type == "B"){
					$sql = "
						DELETE FROM tb_private_holiday
						WHERE ph_seq = '".$r_phh_seq."'
							AND customer_id = '".$r_delete_id."'
					";
				}else{
					$sql = "
						UPDATE tb_private_holiday_hotel SET
							is_delete = '1',
							delete_msg = '".$r_delete_id."|".$r_delete_msg."',
							delete_dt = NOW()
						WHERE is_delete = '2'
							AND phh_seq = '".$r_phh_seq."'
					";
				}
				$result = mysqli_query($connection,$sql);
				//$result = true;
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "임시휴무 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>