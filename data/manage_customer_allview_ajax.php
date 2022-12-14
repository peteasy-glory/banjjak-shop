<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode != ""){
		if($r_mode == "get_customer_list"){
			$r_search_btn = ($_POST["search_btn"] && $_POST["search_btn"] != "")? $_POST["search_btn"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$where_qy = "";
			$order_qy = "";
		
			if($r_search_btn != ""){
				if($r_search_btn == "1"){
					$order_qy = " ORDER BY a.update_time2 DESC ";
				}else if($r_search_btn == "2"){
					$order_qy = " ORDER BY a.pet_name ASC ";
				}else if($r_search_btn == "3"){
					$order_qy = " ORDER BY a.cnt DESC ";
				}else if($r_search_btn == "4"){
					$order_qy = " ORDER BY a.type DESC, a.pet_type ASC ";
				}else if($r_search_btn == "11"){
					$order_qy = " ORDER BY a.update_time2 ASC ";
				}else if($r_search_btn == "12"){
					$order_qy = " ORDER BY a.pet_name DESC ";
				}else if($r_search_btn == "13"){
					$order_qy = " ORDER BY a.cnt ASC ";
				}else if($r_search_btn == "14"){
					$order_qy = " ORDER BY a.type DESC, a.pet_type DESC ";
				}
			}

			$sql = "
				SELECT *
				FROM (
					SELECT 
						pl.cellphone, 
						IFNULL(SUM(pl.local_price), '0') AS sum_local_price, 
						IFNULL(SUM(pl.local_price_cash), '0') AS sum_local_price_cash, 
						pl.pet_seq, 
						mp.pet_seq AS mypet_seq,
					    pl.customer_id,
						IFNULL(mp.name, IFNULL(acl.pet_name, SUBSTRING_INDEX(SUBSTRING_INDEX(pl.product,'|',1),'|',-1))) AS pet_name,					   
						(
							SELECT payment_log_seq
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as payment_log_seq,						 
						(
							SELECT update_time
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as update_time2,
						(
							SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',4),'|',-1) AS service
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
                                #AND product LIKE '|'
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as service,
						(
							SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',5),'|',-1) AS service2
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
                                #AND product LIKE '|'
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as service2,
						(
							SELECT is_cancel
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as is_cancel,
						(
							SELECT IFNULL(cancel_time, '')
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
							ORDER BY update_time DESC
							LIMIT 0 , 1
						) as cancel_time,
						(
							SELECT COUNT(*) as cnt
							FROM tb_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
								AND is_cancel = 0
								AND product_type = 'B'
							LIMIT 0 , 1
						) as cnt,
						mp.type,
						mp.pet_type,
						(
							SELECT IFNULL(ba_seq, '') as ba_seq
							FROM tb_beauty_agree
							WHERE artist_id = '".$r_artist_id."'
								AND pet_id = pl.pet_seq
								AND doc_type = '0'
							LIMIT 0 , 1
						) as ba_seq,
						'beauty' AS payment_type,
						(
							SELECT reserve
							FROM tb_user_reserve
							WHERE is_delete = '2'
								AND artist_id = '".$r_artist_id."'
								AND cellphone = pl.cellphone
							LIMIT 0 , 1
						) as user_reserve
					FROM tb_payment_log AS pl
						LEFT OUTER JOIN tb_artist_customer_list AS acl ON pl.pet_seq = acl.pet_seq
						LEFT OUTER JOIN tb_mypet AS mp ON pl.pet_seq = mp.pet_seq
					WHERE pl.artist_id = '".$r_artist_id."'
						AND pl.cellphone != ''
						AND (pl.pet_seq != '' OR pl.pet_seq != '0')
						AND pl.data_delete = '0'
						AND pl.is_cancel = '0'
					GROUP BY pl.cellphone
					
					UNION ALL

					SELECT 
						hpl.cellphone, 
						IFNULL(SUM(hpl.add_price_card), '0') AS sum_local_price, 
						IFNULL(SUM(hpl.add_price_cash), '0') AS sum_local_price_cash, 
						hpl.pet_seq,
						mp.pet_seq AS mypet_seq,
						mp.name AS pet_name,
						hpl.customer_id,
						(
							SELECT order_num
							FROM tb_hotel_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as payment_log_seq,
						(
							SELECT IFNULL(update_dt, reg_dt)
							FROM tb_hotel_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) AS update_time2,
						(
							SELECT concat(room_name,'/',TIMESTAMPDIFF(DAY, check_in_date, check_out_date),'박/~',weight,' Kg') AS service
							FROM tb_hotel_reservation
							WHERE order_num = hpl.order_num
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt desc
							LIMIT 0 , 1
						) AS service,
						(
							SELECT concat(if(extra_price > 0, '시간추가', ''),if(neutral_price > 0, '중성화', ''),if(pickup_price > 0, '픽업', '')) AS service2
							FROM tb_hotel_reservation
							WHERE artist_id = hpl.order_num
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as service2,
						(
							SELECT is_delete
							FROM tb_hotel_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as is_cancel,
						(
							SELECT IFNULL(delete_dt, '')
							FROM tb_hotel_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as cancel_time,	
						(
							SELECT COUNT(*) as cnt
							FROM tb_hotel_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							LIMIT 0 , 1
						) as cnt,
						mp.type,
						mp.pet_type,
						(
							SELECT IFNULL(ba_seq, '') as ba_seq
							FROM tb_beauty_agree
							WHERE artist_id = '".$r_artist_id."'
								AND pet_id = hpl.pet_seq
								AND doc_type = '1'
							LIMIT 0 , 1
						) as ba_seq,
						'hotel' AS payment_type,
						(
							SELECT reserve
							FROM tb_user_reserve
							WHERE is_delete = '2'
								AND artist_id = '".$r_artist_id."'
								AND cellphone = hpl.cellphone
							LIMIT 0 , 1
						) as user_reserve
					FROM tb_hotel_payment_log AS hpl
						INNER JOIN tb_mypet AS mp ON hpl.pet_seq = mp.pet_seq
					WHERE hpl.artist_id = '".$r_artist_id."'
						AND hpl.cellphone != ''
						AND (hpl.pet_seq != '' OR hpl.pet_seq != '0')
						AND hpl.is_delete = '2'
					GROUP BY hpl.cellphone
					
					UNION ALL

					SELECT 
						ppl.cellphone, 
						IFNULL(SUM(ppl.add_price_card), '0') AS sum_local_price, 
						IFNULL(SUM(ppl.add_price_cash), '0') AS sum_local_price_cash, 
						ppl.pet_seq,
						mp.pet_seq AS mypet_seq,
						mp.name AS pet_name,
						ppl.customer_id,
						(
							SELECT order_num
							FROM tb_playroom_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as payment_log_seq,
						(
							SELECT IFNULL(update_dt, reg_dt)
							FROM tb_playroom_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) AS update_time2,
						(
							SELECT if(service_type = 1, concat(allday_pass_name), if((room_name * count) <> 0, concat((room_name * count),'시간/~',weight,' Kg'), '')) AS service
							FROM tb_playroom_reservation
							WHERE order_num = ppl.order_num
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt desc
							LIMIT 0 , 1
						) AS service,
						(
							SELECT concat(if(extra_price > 0, '시간추가', ''),if(neutral_price > 0, '중성화', ''),if(pickup_price > 0, '픽업', '')) AS service2
							FROM tb_playroom_reservation
							WHERE artist_id = ppl.order_num
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as service2,
						(
							SELECT is_delete
							FROM tb_playroom_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as is_cancel,
						(
							SELECT IFNULL(delete_dt, '')
							FROM tb_playroom_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							ORDER BY reg_dt DESC
							LIMIT 0 , 1
						) as cancel_time,	
						(
							SELECT COUNT(*) as cnt
							FROM tb_playroom_payment_log
							WHERE artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							LIMIT 0 , 1
						) as cnt,
						mp.type,
						mp.pet_type,
						(
							SELECT IFNULL(ba_seq, '') as ba_seq
							FROM tb_beauty_agree
							WHERE artist_id = '".$r_artist_id."'
								AND pet_id = ppl.pet_seq
								AND doc_type = '1'
							LIMIT 0 , 1
						) as ba_seq,
						'playroom' AS payment_type,
						(
							SELECT reserve
							FROM tb_user_reserve
							WHERE is_delete = '2'
								AND artist_id = '".$r_artist_id."'
								AND cellphone = ppl.cellphone
							LIMIT 0 , 1
						) as user_reserve
					FROM tb_playroom_payment_log AS ppl
						INNER JOIN tb_mypet AS mp ON ppl.pet_seq = mp.pet_seq
					WHERE ppl.artist_id = '".$r_artist_id."'
						AND ppl.cellphone != ''
						AND (ppl.pet_seq != '' OR ppl.pet_seq != '0')
						AND ppl.is_delete = '2'
					GROUP BY ppl.cellphone
				) AS a
				".$order_qy."
			";

			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_customer_count"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			
			$sql = "
					SELECT A.cellphone
	FROM
	(
		(
			SELECT cellphone 
			FROM tb_payment_log
			WHERE data_delete = 0 AND artist_id = '{$r_artist_id}'
			GROUP BY cellphone
		)
		UNION
		(
			SELECT cellphone
			FROM tb_hotel_payment_log
			WHERE is_delete = 2 AND artist_id = '{$r_artist_id}'
			GROUP BY cellphone
		)
		UNION
		(
			SELECT cellphone
			FROM tb_playroom_payment_log
			WHERE is_delete = 2 AND artist_id = '{$r_artist_id}'
			GROUP BY cellphone
		)
	) A
			";
			$result = mysqli_query($connection, $sql);
			$data = mysqli_num_rows($result);

			$return_data = array("code" => "000000", "data" => $data);

		}else if($r_mode == "get_pet_list"){ // 펫 여러마리일 때 여러마리 모두 불러오기
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
//			$r_payment_type = ($_POST["payment_type"] && $_POST["payment_type"] != "")? $_POST["payment_type"] : "";
			
			$sql = "
				SELECT DISTINCT(SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',1),'|',-1)) AS pet_list
				FROM tb_payment_log
				WHERE artist_id = '".$r_artist_id."'
				AND data_delete = '0'
				AND cellphone = '".$r_cellphone."'
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_customer_pet_count"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT COUNT(*) cnt
	FROM
	(
		(
			SELECT pet_seq 
			FROM tb_payment_log
			WHERE data_delete = 0 AND artist_id = '{$r_artist_id}'
			GROUP BY pet_seq
		)
		UNION
		(
			SELECT pet_seq
			FROM tb_hotel_payment_log
			WHERE is_delete = 2 AND artist_id = '{$r_artist_id}'
			GROUP BY pet_seq
		)
		UNION
		(
			SELECT pet_seq
			FROM tb_playroom_payment_log
			WHERE is_delete = 2 AND artist_id = '{$r_artist_id}'
			GROUP BY pet_seq
		)
	) A
			";
			
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data = $row["cnt"];
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
