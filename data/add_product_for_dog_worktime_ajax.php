<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_product_dog_worktime"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT *
				FROM tb_product_dog_worktime
				WHERE is_delete = '2'
					AND artist_id = '".$r_artist_id."'
			";
			
			$result = mysqli_query($connection, $sql);
			$data = mysqli_fetch_assoc($result);
			
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_product_dog_worktime"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_worktime1 = ($_POST["worktime1"] && $_POST["worktime1"] != "")? $_POST["worktime1"] : "";
			$r_worktime2 = ($_POST["worktime2"] && $_POST["worktime2"] != "")? $_POST["worktime2"] : "";
			$r_worktime3 = ($_POST["worktime3"] && $_POST["worktime3"] != "")? $_POST["worktime3"] : "";
			$r_worktime4 = ($_POST["worktime4"] && $_POST["worktime4"] != "")? $_POST["worktime4"] : "";
			$r_worktime5 = ($_POST["worktime5"] && $_POST["worktime5"] != "")? $_POST["worktime5"] : "";
			$r_worktime6 = ($_POST["worktime6"] && $_POST["worktime6"] != "")? $_POST["worktime6"] : "";
			$r_worktime7 = ($_POST["worktime7"] && $_POST["worktime7"] != "")? $_POST["worktime7"] : "";
			$r_worktime8 = ($_POST["worktime8"] && $_POST["worktime8"] != "")? $_POST["worktime8"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT *
					FROM tb_product_dog_worktime
					WHERE is_delete = '2'
						AND artist_id = '".$r_artist_id."'
				";
				$result = mysqli_query($connection, $sql);
				$cnt = mysqli_num_rows($result);
				if($cnt == 0){ // insert
					$sql = "
						INSERT INTO tb_product_dog_worktime (
							artist_id, worktime1, worktime2, worktime3, worktime4,
							worktime5, worktime6, worktime7, worktime8, reg_dt
						) VALUES (
							'".$r_artist_id."', '".$r_worktime1."', '".$r_worktime2."', '".$r_worktime3."', '".$r_worktime4."', 
							'".$r_worktime5."', '".$r_worktime6."', '".$r_worktime7."', '".$r_worktime8."', NOW()
						)
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK_insert");
					}else{
						$return_data = array("code" => "000001", "data" => "미용별 시간 생성에 실패했습니다.");
					}
				}else{ // update
					$sql = "
						UPDATE tb_product_dog_worktime SET
							worktime1 = '".$r_worktime1."',
							worktime2 = '".$r_worktime2."',
							worktime3 = '".$r_worktime3."',
							worktime4 = '".$r_worktime4."',
							worktime5 = '".$r_worktime5."',
							worktime6 = '".$r_worktime6."',
							worktime7 = '".$r_worktime7."',
							worktime8 = '".$r_worktime8."',
							update_dt = NOW()
						WHERE artist_id = '".$r_artist_id."'
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK_update");
					}else{
						$return_data = array("code" => "000002", "data" => "미용별 시간 수정에 실패했습니다.");
					}
				}
			}else{
				$return_data = array("code" => "000003", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>