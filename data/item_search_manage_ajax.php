<?php
    include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "change_rank"){
			$r_rank_one = ($_POST["rank_one"] && $_POST["rank_one"] != "")? $_POST["rank_one"] : "";
			$r_rank_two = ($_POST["rank_two"] && $_POST["rank_two"] != "")? $_POST["rank_two"] : "";
			$r_rank_three = ($_POST["rank_three"] && $_POST["rank_three"] != "")? $_POST["rank_three"] : "";
			$r_rank_four = ($_POST["rank_four"] && $_POST["rank_four"] != "")? $_POST["rank_four"] : "";
			$r_rank_five = ($_POST["rank_five"] && $_POST["rank_five"] != "")? $_POST["rank_five"] : "";

			if($r_rank_one != "" && $r_rank_two != "" && $r_rank_three != "" && $r_rank_four != "" && $r_rank_five != ""){
				$sql = "
					UPDATE tb_item_rank SET
						search = CASE num WHEN '1' THEN '".$r_rank_one."' ELSE search END,
						search = CASE num WHEN '2' THEN '".$r_rank_two."' ELSE search END,
						search = CASE num WHEN '3' THEN '".$r_rank_three."' ELSE search END,
						search = CASE num WHEN '4' THEN '".$r_rank_four."' ELSE search END,
						search = CASE num WHEN '5' THEN '".$r_rank_five."' ELSE search END
				";
				$result = mysqli_query($connection, $sql);
//				if($r_type == "beauty"){
//					$sql = "
//						UPDATE tb_shop SET
//							enable_flag = '".$r_value."'
//						WHERE customer_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "hotel"){
//					$sql = "
//						UPDATE tb_hotel SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "playroom"){
//					$sql = "
//						UPDATE tb_playroom SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}
//				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_rank") {

            $sql = "
				SELECT * FROM tb_item_rank ORDER BY num
			";
            $result = mysqli_query($connection, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            $return_data = array("code" => "000000", "data" => $data);

        }else if($r_mode == "get_item_list"){
            $r_search_word	   = ($_POST["search_word"] && $_POST["search_word"] != "")? $_POST["search_word"] : "";
            if($r_search_word != ''){
                $sql = "
                    SELECT * FROM tb_item_list a
                     LEFT JOIN tb_file b ON b.f_seq = a.product_img
                     LEFT JOIN (
                         SELECT product_no p_no, AVG(rating) rating_avg, COUNT(product_no) AS rating_cnt 
                         FROM tb_item_review WHERE rating IS NOT NULL AND is_delete = '2' GROUP BY product_no
                     ) c ON c.p_no = a.product_no
                     WHERE (a.product_name like '%".$r_search_word."%' OR a.brand LIKE '%".$r_search_word."%')
                     AND a.is_delete = '1' AND a.is_view = '1' AND a.is_shop = '2'
                     ORDER BY a.is_soldout, a.il_seq desc
                ";

                $result = mysqli_query($connection, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $data['list'][] = $row;
                }
                $sql = "
                    SELECT *
                    FROM tb_item_list
                    WHERE is_delete != '2' AND is_soldout != '2'
                    AND (product_name like '%".$r_search_word."%' OR brand LIKE '%".$r_search_word."%')
                ";
                $result = mysqli_query($connection, $sql);
                $data["cnt"] = mysqli_num_rows($result);
                $return_data = array("code" => "000000", "data" => $data);
            }else{
                $return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
            }
		}else if($r_mode == "get_main_banner"){
			
			$sql = "
				SELECT * FROM tb_main_banner
				WHERE is_use = 1 AND is_delete = 2
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data); 

		}else if($r_mode == "change_banner"){
			$r_mb_seq = ($_POST["mb_seq"] && $_POST["mb_seq"] != "")? $_POST["mb_seq"] : "";
			
			if($r_mb_seq != ""){
				$sql = "
					UPDATE tb_main_banner SET
						search_rank = CASE mb_seq WHEN '".$r_mb_seq."' THEN '1' END
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}

		}else if($r_mode == "get_search_banner"){
			$sql = "
				SELECT * FROM tb_main_banner
				WHERE is_use = 1 AND is_delete = 2 AND search_rank = 1
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
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