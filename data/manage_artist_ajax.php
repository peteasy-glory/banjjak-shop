<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$data = array();
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_artist_list"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT 
					  *
					, GROUP_CONCAT(week ORDER BY week ASC) week_list
					, GROUP_CONCAT(time_start ORDER BY seq ASC) time_start_list
					, GROUP_CONCAT(time_end ORDER BY seq ASC) time_end_list
					, GROUP_CONCAT(seq) as seq_list /*20210607 by migo - 출력순서 조정용*/
				FROM tb_artist_list
				WHERE artist_id = '".$r_artist_id."'
				GROUP BY name
	            ORDER BY sequ_prnt asc, is_main DESC, nicname ASC
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_artist_list"){
//		    print_r($_POST);
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_nicname = ($_POST["nicname"] && $_POST["nicname"] != "")? $_POST["nicname"] : "";
			$r_is_main = ($_POST["is_main"] != "")? $_POST["is_main"] : "0";
			$r_week = ($_POST["checkbox_time"] != "")? $_POST["checkbox_time"] : "";
			$r_time_start = ($_POST["select_time_from"] && $_POST["select_time_from"] != "")? $_POST["select_time_from"] : "";
			$r_time_end = ($_POST["select_time_to"] && $_POST["select_time_to"] != "")? $_POST["select_time_to"] : "";
            $r_old_name = $_POST["old_name"];

			//print_r($r_time_end);
			if($r_artist_id != "" && $r_name != "" && $r_nicname != ""){
				$sql = "
					SELECT * 
					FROM tb_artist_list 
					WHERE artist_id = '".$r_artist_id."' 
						AND is_main = '1'
				";
				$result = mysqli_query($connection, $sql);
				$cnt = mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if(($r_is_main == "1" && $cnt > 0 && $row['name'] == $r_name) || $r_is_main == "0"){
					if(count($r_week) > 0){
						$sql2 = "
							DELETE FROM tb_artist_list 
							WHERE artist_id = '".$r_artist_id."' AND name = '".$r_old_name."'
						";
						$result = mysqli_query($connection, $sql2);
						//$result = true;
						if($result){
							$insert_qy = "";
							for($_i = 0; $_i < count($r_time_end); $_i++){
							    if($r_week[$_i] != '') {
                                    //echo $r_week[$_i]." : ".$r_time_end[$r_week[$_i]];
                                    $insert_qy .= "('" . $r_artist_id . "', '" . $r_name . "', '" . $r_nicname . "', '" . $r_is_main . "', '" . $r_week[$_i] . "', '" . $r_time_start[$r_week[$_i]] . "', '" . $r_time_end[$r_week[$_i]] . "',NULLIF('" . $_POST['grade'] . "','')),";
                                }
							}
							$insert_qy = substr($insert_qy, 0, -1);

							$sql = "
								INSERT INTO tb_artist_list (
									`artist_id`, `name`, `nicname`, `is_main`, `week`, 
									`time_start`, `time_end`, `sequ_prnt`
								) VALUES ".$insert_qy."
							";
							//echo $sql;
							$result = mysqli_query($connection, $sql);
							//$result = true;
							if($result){
								$return_data = array("code" => "000000", "data" => $sql);
							}else{
								$return_data = array("code" => "000002", "data" => "미용사 추가에 실패했습니다.");
							}
						}else{
							$return_data = array("code" => "000002", "data" => "미용사 초기화에 실패했습니다.");
						}
					}else{
						$return_data = array("code" => "000003", "data" => "최소 하루는 선택하여 주시기 바랍니다.");
					}
				}else{
					$return_data = array("code" => "000004", "data" => "대표 미용사는 한 명만 선택 가능합니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_update_artist_list"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_is_main = ($_POST["is_main"] != "")? $_POST["is_main"] : "";
			$r_is_out = ($_POST["is_out"] != "")? $_POST["is_out"] : "";
			$r_is_view = ($_POST["is_view"] != "")? $_POST["is_view"] : "";
			$update_qy = "";
			$where_qy = "";

			if($r_is_out != "" && $r_is_main != "1"){
				$update_qy = " is_out = '".$r_is_out."' ";
			}
			if($r_is_view != ""){
				$update_qy = " is_view = '".$r_is_view."' ";
			}

			if($r_artist_id != ""){
				$where_qy .= " AND artist_id = '".$r_artist_id."' ";
			}
			if($r_name != ""){
				$where_qy .= " AND name = '".$r_name."' ";
			}

			if($update_qy != "" && $where_qy != ""){
				$sql = "
					UPDATE tb_artist_list SET
						".$update_qy."
					WHERE 1=1
						".$where_qy."
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "미용사 변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_delete_artist_list"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_is_main = ($_POST["is_main"] != "")? $_POST["is_main"] : "";

			if($r_artist_id != "" && $r_name != ""){
				if($r_is_main != "1"){
					$sql = "
						DELETE FROM tb_artist_list 
						WHERE artist_id = '".$r_artist_id."' 
							AND name = '".$r_name."'
					";
					$result = mysqli_query($connection, $sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000002", "data" => "미용사 삭제에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000004", "data" => "대표 미용사는 삭제할 수 없습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_working_schedule"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT * 
				FROM tb_working_schedule 
				WHERE customer_id = '".$r_artist_id."'
			";
			$result = mysqli_query($connection, $sql);
			$data = mysqli_fetch_assoc($result);
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_regular_holiday"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			$sql = "
				SELECT * 
				FROM tb_regular_holiday 
				WHERE customer_id = '".$r_artist_id."'
			";
			$result = mysqli_query($connection, $sql);
			$data = mysqli_fetch_assoc($result);
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_print_sequ_artist_list"){	// 20210607 by migo - 미용사 출력순서 조정용, 이용 빈도 수가 적어 매번 update 처리 
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_sequ_prnt = ($_POST["sequ_prnt"] && $_POST["sequ_prnt"] != "")? $_POST["sequ_prnt"] : "0"; $r_sequ_prnt = intval($r_sequ_prnt);	// 출력순서  1~
			$r_seq_lst = ($_POST["seq_lst"] && $_POST["seq_lst"] != "")? $_POST["seq_lst"] : "";	// tb_artist_list.seq 값 목록 (콤마 구분)

			if($r_artist_id != "" && $r_sequ_prnt != "" && $r_seq_lst != ""){
				$sql = "
					UPDATE tb_artist_list set sequ_prnt = '{$r_sequ_prnt}'
					WHERE artist_id = '".$r_artist_id."' and seq in ({$r_seq_lst});
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "미용사 출력 순서 조정에 실패했습니다.");
				}
			} else {
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
