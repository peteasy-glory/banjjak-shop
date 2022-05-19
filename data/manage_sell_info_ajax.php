<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	$data = array();

	if($r_mode){
		if($r_mode == "get_public_holiday"){ // 법정공휴일
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
			$r_week_start = ($_POST["week_start"] && $_POST["week_start"] != "")? $_POST["week_start"] : "";
			$r_week_end = ($_POST["week_end"] && $_POST["week_end"] != "")? $_POST["week_end"] : "";
			$where_qy = "";

			if($r_year != ""){
				$where_qy .= " AND year = '".$r_year."' ";
			}

			if($r_month != ""){
				$where_qy .= " AND month = '".$r_month."' ";
			}

			if($r_week_start != "" && $r_week_end != ""){
				$where_qy .= " AND day BETWEEN '".$r_week_start."' AND '".$r_week_end."' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT *
					FROM tb_public_holiday
					WHERE 1=1 
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_beauty_payment_log"){ // 미용내역 가져오기
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
			$r_day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
			$where_qy = "";

			if($r_artist_id != ""){
				$where_qy .= " AND artist_id = '".$r_artist_id."' ";
			}

			if($r_year != ""){
				$where_qy .= " AND year = '".$r_year."' ";
			}

			if($r_month != ""){
				$where_qy .= " AND month = '".$r_month."' ";
			}

			if($r_day != ""){
				$where_qy .= " AND day = '".$r_day."' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT *
						, SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',1),'|',-1) AS pet_name
						, SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',5),'|',-1) AS service
					FROM tb_payment_log
					WHERE is_cancel = '0'
						".$where_qy."
					ORDER BY worker ASC, year ASC, month ASC, day ASC, hour ASC, minute ASC
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");			
			}
		}else if($r_mode == "get_beauty_payment_log_cnt"){ // 미용내역 일별횟수 가져오기
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
			$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
			$r_day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
			$where_qy = "";

			if($r_artist_id != ""){
				$where_qy .= " AND artist_id = '".$r_artist_id."' ";
			}

			if($r_year != ""){
				$where_qy .= " AND year = '".$r_year."' ";
			}

			if($r_month != ""){
				$where_qy .= " AND month = '".$r_month."' ";
			}

			if($r_day != ""){
				$where_qy .= " AND day = '".$r_day."' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT year, month, day, COUNT(*) AS cnt
					FROM tb_payment_log
					WHERE is_cancel = '0'
						AND is_no_show = '0'
						".$where_qy."
					GROUP BY year, month, day
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");			
			}
		}else if($r_mode == "get_regular_holiday"){ // 정기휴일
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT *
					FROM tb_regular_holiday
					WHERE customer_id = '".$r_artist_id."'
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_pet_type"){
			$r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";

			if($r_pet_seq != ""){
				$sql = "
					SELECT *
					FROM tb_mypet
					WHERE pet_seq = '".$r_pet_seq."'
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;	
				}

				$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_private_holiday"){ // 개인휴일
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT *
					FROM tb_private_holiday 
					WHERE customer_id = '".$r_artist_id."'
				";
				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					$data[] = $row;	
				}

				$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_artist_list"){ // 미용사 리스트
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT *
				FROM tb_artist_list
				WHERE artist_id = '".$r_artist_id."'
					AND is_view = '2'
				GROUP BY name
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;	
			}

			$return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result));
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>