<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_search" || $r_mode == "get_search_all"){
			$r_search = ($_POST["search"] && $_POST["search"] != "")? $_POST["search"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$search_type = "";
			$where_qy = "";

			if($r_search != ""){
				$search = preg_replace("/[^0-9]*/s", "", $r_search);
				if(is_numeric($search)){ // 숫자만 입력시 전화번호로 검색
					$search_type = "1";
					$r_search = $search;
				}else{
					$search_type = "2";
				}

				if($search_type == "1"){
					// family 여부
					$sql = "
						SELECT DISTINCT GROUP_CONCAT(to_cellphone SEPARATOR ',') AS to_cellphone_group 
						FROM tb_customer_family 
						WHERE is_delete = 0 
							AND artist_id = '".$r_artist_id."'
							AND from_cellphone like '%".$r_search."%'
					";
					$result = mysqli_query($connection, $sql);
					$row = mysqli_fetch_assoc($result);

					$where_qy .= " AND ( ";
					$where_qy .= "	pl.cellphone LIKE '%".$r_search."%' ";
					$where_qy .= ($row["to_cellphone_group"] != "")? "	OR pl.cellphone IN (".$row["to_cellphone_group"].") " : "";
					$where_qy .= " )";	
				}else{
					// 펫 이름 변경 여부
					$sql = "
						SELECT DISTINCT GROUP_CONCAT(pet_seq SEPARATOR ',') AS pet_seq_group 
						FROM tb_artist_customer_list
						WHERE artist_id = '".$r_artist_id."'
							AND pet_name LIKE '%".$r_search."%'
					";
					$result = mysqli_query($connection, $sql);
					$row = mysqli_fetch_assoc($result);

					$where_qy .= " AND (";
					$where_qy .= "	mp.name LIKE '%".$r_search."%' ";
					$where_qy .= "	OR mp.pet_type LIKE '%".$r_search."%' ";
					$where_qy .= "	OR mp.pet_type2 LIKE '%".$r_search."%' ";
					$where_qy .= ($mainpage_type == "0")? "	OR SUBSTRING_INDEX(pl.product, '|', 1) LIKE '%".$r_search."%' " : "";
					$where_qy .= ($row["pet_seq_group"] != "")? "	OR pl.pet_seq IN (".$row["pet_seq_group"].") " : "";
					$where_qy .= " )";
				}

				// 검색 고객 리스트 호출
				$sql = "
                    SELECT * FROM (
                        SELECT pl.*, mp.name, mp.tmp_seq, mp.type, mp.pet_type, mp.pet_type2, mp.photo, mp.tmp_yn,
                            SUBSTRING_INDEX(pl.product, '|', 1) AS product_pet_name,
                            (
                                SELECT pet_name 
                                FROM tb_artist_customer_list 
                                WHERE artist_id = '" . $r_artist_id . "' AND pet_seq = pl.pet_seq
                            ) AS pet_name
                        FROM tb_payment_log AS pl
                            INNER JOIN tb_mypet AS mp ON pl.pet_seq = mp.pet_seq
                        WHERE pl.artist_id = '" . $r_artist_id . "'
                        AND pl.data_delete = '0'
                        AND mp.data_delete = '0'
                            " . $where_qy . "
                        ORDER BY pl.payment_log_seq DESC
					) sub
					GROUP BY sub.cellphone
					ORDER BY sub.payment_log_seq DESC
				";
			    //echo $sql;
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[$row["cellphone"]]["payment"] = $row;

					$family_arr = array();
					$sql2 = "
						SELECT * 
						FROM tb_customer_family 
						WHERE is_delete = 0 
							AND artist_id = '".$r_artist_id."'
							AND to_cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
//						$family_arr[] = add_hyphen($row2["from_cellphone"]);
						$family_arr[] = add_hyphen($row2["from_cellphone"])."<br>";
					}
					$data[$row["cellphone"]]["family"] = implode('', $family_arr);

					$sql2 = "
						SELECT count(is_no_show) AS cnt
						FROM tb_payment_log
						WHERE artist_id = '".$r_artist_id."'
							AND is_no_show = 1 
							AND is_cancel = 0 
							AND cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
						$data[$row["cellphone"]]["noshow"] = $row2["cnt"];
					}
				}

				$sql = "
					SELECT pl.*, mp.name, mp.tmp_seq, mp.type, mp.pet_type, mp.pet_type2, mp.photo, mp.tmp_yn
					FROM tb_hotel_payment_log AS pl
						INNER JOIN tb_mypet AS mp ON pl.pet_seq = mp.pet_seq
					WHERE pl.artist_id = '".$r_artist_id."'
						".$where_qy."
					GROUP BY pl.cellphone
					ORDER BY pl.hp_log_seq DESC
				";
				/*
				AND NOT pl.pet_seq IN (
				SELECT pet_seq from tb_payment_log 
				WHERE artist_id = '".$r_artist_id."' 
				AND data_delete = '1'
				)
				*/
				$arrayHotelValue = ($r_mode == "get_search_all") ? "payment" : "hotel";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[$row["cellphone"]][$arrayHotelValue] = $row;

					$family_arr = array();
					$sql2 = "
						SELECT * 
						FROM tb_customer_family 
						WHERE is_delete = 0 
							AND artist_id = '".$r_artist_id."'
							AND to_cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
						$family_arr[] = add_hyphen($row2["from_cellphone"]);
					}
					$data[$row["cellphone"]]["family"] = implode(',', $family_arr);
					$data[$row["cellphone"]]["photo"] = img_link_change($row["photo"]);

					$sql2 = "
						SELECT count(is_no_show) AS cnt
						FROM tb_hotel_payment_log
						WHERE artist_id = '".$r_artist_id."'
							AND is_no_show = 1 
							AND is_cancel = 0 
							AND cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
						$data[$row["cellphone"]]["noshow"] = $row2["cnt"];
					}
				}

				$sql = "
					SELECT pl.*, mp.name, mp.tmp_seq, mp.type, mp.pet_type, mp.pet_type2, mp.photo, mp.tmp_yn
					FROM tb_playroom_payment_log AS pl
						INNER JOIN tb_mypet AS mp ON pl.pet_seq = mp.pet_seq
					WHERE pl.artist_id = '".$r_artist_id."'
						".$where_qy."
					GROUP BY pl.cellphone
					ORDER BY pl.pp_log_seq DESC
				";
				/*
				AND NOT pl.pet_seq IN (
				SELECT pet_seq from tb_payment_log 
				WHERE artist_id = '".$r_artist_id."' 
				AND data_delete = '1'
				)
				*/
				$arrayPlayroomValue = ($r_mode == "get_search_all") ? "payment" : "playroom";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[$row["cellphone"]][$arrayPlayroomValue] = $row;

					$family_arr = array();
					$sql2 = "
						SELECT * 
						FROM tb_customer_family 
						WHERE is_delete = 0 
							AND artist_id = '".$r_artist_id."'
							AND to_cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
						$family_arr[] = add_hyphen($row2["from_cellphone"]);
					}
					$data[$row["cellphone"]]["family"] = implode(',', $family_arr);

					$sql2 = "
						SELECT count(is_no_show) AS cnt
						FROM tb_playroom_payment_log
						WHERE artist_id = '".$r_artist_id."'
							AND is_no_show = 1 
							AND is_cancel = 0 
							AND cellphone = '".$row["cellphone"]."'
					";
					$result2 = mysqli_query($connection, $sql2);
					while($row2 = mysqli_fetch_assoc($result2)){
						$data[$row["cellphone"]]["noshow"] = $row2["cnt"];
					}
				}
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