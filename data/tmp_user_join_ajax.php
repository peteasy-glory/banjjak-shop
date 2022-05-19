<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_pet_kind"){
			$r_pet_type = ($_POST["pet_type"] && $_POST["pet_type"] != "")? $_POST["pet_type"] : "";

			$sql = "
				SELECT *
				FROM tb_pet_type
				WHERE type = '".$r_pet_type."'
					AND enable_flag = '1'
				ORDER BY name ASC
			";
			$result = mysqli_query($connection, $sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_cellphone_chk"){
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$crypto = new Crypto();

			$sql = "
				SELECT cellphone
				FROM tb_customer
				WHERE id = '".$r_artist_id."'
			";
			$result = mysqli_query($connection, $sql);
			$cellphone_chk = mysqli_fetch_assoc($result);
			$cellphone_chk = $crypto->decode($cellphone_chk["cellphone"], $access_key, $secret_key);
			
			if($r_cellphone != $cellphone_chk){
				$sql = "
					SELECT *
					FROM tb_payment_log
					WHERE cellphone = '".$r_cellphone."'
						AND artist_id = '".$r_artist_id."'
						AND data_delete = '0'
				";
				$result = mysqli_query($connection, $sql);
				$cnt = mysqli_num_rows($result);

				if($cnt == 0){
					$return_data = array("code" => "000000", "data" => array("result" => true, "message" => "가입 가능한 번호입니다."));
				}else{
					$return_data = array("code" => "000000", "data" => array("result" => false, "message" => "이미 가입된 번호입니다."));
				}
			}else{
				$return_data = array("code" => "000001", "data" => "자신의 번호는 등록하실 수 없습니다.");
			}
		}else if($r_mode == "get_mypet"){
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$crypto = new Crypto();
			$encode_cellphone = $crypto->encode($r_cellphone, $access_key, $secret_key);
			
			if($r_pet_seq != ""){
				$sql = "
					SELECT mp.*, ac.pet_name
					FROM tb_mypet AS mp
						LEFT OUTER JOIN tb_artist_customer_list AS ac ON ac.pet_seq = mp.pet_seq
					WHERE mp.pet_seq = '".$r_pet_seq."'
				";

				$result = mysqli_query($connection, $sql);
				$data = mysqli_fetch_assoc($result);
				if($data["pet_name"] != ""){
					$data["name"] = $data["pet_name"];
				}
			}else{
				$sql = "
					SELECT mp.*, ac.pet_name
					FROM tb_mypet AS mp
						LEFT OUTER JOIN tb_customer AS cs ON cs.id = mp.customer_id
						LEFT OUTER JOIN tb_tmp_user AS tu ON tu.tmp_seq = mp.tmp_seq
						LEFT OUTER JOIN tb_artist_customer_list AS ac ON ac.pet_seq = mp.pet_seq
					WHERE (tu.cellphone = '".$r_cellphone."' OR cs.cellphone = '".$encode_cellphone."')
					AND mp.data_delete = '0'
					AND NOT mp.pet_seq IN (
						SELECT pet_seq FROM tb_payment_log
						WHERE artist_id = '".$r_artist_id."'
						AND data_delete = '1'
					)
				";

				$result = mysqli_query($connection, $sql);
				while($row = mysqli_fetch_assoc($result)){
					if($row["pet_name"] != ""){
						$row["name"] = $row["pet_name"];
					}
					$data[] = $row;
				}
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_add_tmp_user"){
			$r_artist_id	= $_POST["artist_id"];
			$r_customer_id	= $_POST["customer_id"];
			$r_tmp_seq		= $_POST["tmp_seq"];
			$r_pet_seq		= $_POST["pet_seq"];
			$r_cellphone	= $_POST["cellphone"];
			$r_pet_name		= addslashes($_POST["pet_name"]);
			$r_pet_kind		= ($_POST["pet_kind"] && $_POST["pet_kind"] != "")? $_POST["pet_kind"] : "dog";
			$r_pet_type		= ($_POST["pet_type"] && $_POST["pet_type"] != "")? $_POST["pet_type"] : "골든두들";
			$r_pet_type2	= addslashes($_POST["pet_type2"]);
			$r_pet_year		= ($_POST["pet_year"] && $_POST["pet_year"] != "")? $_POST["pet_year"] : "2020";
			$r_pet_month	= ($_POST["pet_month"] && $_POST["pet_month"] != "")? $_POST["pet_month"] : "1";
			$r_pet_day		= ($_POST["pet_day"] && $_POST["pet_day"] != "")? $_POST["pet_day"] : "1";
			$r_pet_gender	= ($_POST["pet_gender"] && $_POST["pet_gender"] != "")? $_POST["pet_gender"] : "남아";
			$r_neutral		= ($_POST["neutral"] && $_POST["neutral"] != "")? $_POST["neutral"] : "0";
			$r_pet_weight1	= ($_POST["pet_weight1"] && $_POST["pet_weight1"] != "")? $_POST["pet_weight1"] : "5";
			$r_pet_weight2	= ($_POST["pet_weight2"] && $_POST["pet_weight2"] != "")? $_POST["pet_weight2"] : "0";
			$r_memo			= addslashes($_POST["memo"]);
			$r_direct		= ($_POST["direct"] && $_POST["direct"] != "")? $_POST["direct"] : "0";
			$pet_weight		= floatval($r_pet_weight1.".".$r_pet_weight2);

			//$tmp_seq = "1"; // 가회원 번호
			//$pet_seq = "2"; // 펫 번호
			//$temp = "";
			//$result = true;
			//$result2 = true;
			//$result3 = true;
			//$result4 = true;
			if($r_direct == "1"){
		        $_SESSION['bjj_stop_direct_flag'] = "0";
		        $_SESSION['bjj_cellphone'] = $r_cellphone;
				$_SESSION['bjj_pet_name'] = $r_pet_name;
				$_SESSION['bjj_pet_kind'] = $r_pet_kind;
				$_SESSION['bjj_pet_seq'] = $r_pet_seq;
				$_SESSION['bjj_pet_type'] = $r_pet_type;
				$_SESSION['bjj_pet_type2'] = $r_pet_type2;
				$_SESSION['bjj_pet_gender'] = $r_pet_gender;
				$_SESSION['bjj_neutral'] = $r_neutral;
			}

			if($r_artist_id != ""){
				if($r_tmp_seq == "" && $r_customer_id == ""){ // 신규등록
					// 가회원 존재 여부 확인
					$sql = "
						SELECT *
						FROM tb_tmp_user
						WHERE cellphone = '".$r_cellphone."'
					";
					$result = mysqli_query($connection, $sql);
					$tmp_user_cnt = mysqli_num_rows($result);
					if($tmp_user_cnt == 0){
						$sql = "
							INSERT INTO tb_tmp_user (cellphone) VALUES
							('".$r_cellphone."')
						";
						//$temp .= $sql."<br/>";
						$result = mysqli_query($connection, $sql);
						if($result){
							$tmp_seq = mysqli_insert_id($connection);
						}
					}else{
						$row = mysqli_fetch_assoc($result);
						$tmp_seq = $row["tmp_seq"];
					}
					if($tmp_seq != ""){
						$sql = "
							INSERT INTO tb_mypet (
								tmp_seq, name, name_for_owner, type, pet_type, 
								pet_type2, year, month, day, gender, 
								neutral, weight, tmp_yn
							) VALUES (
								'".$tmp_seq."','".$r_pet_name."','".$r_pet_name."','".$r_pet_kind."','".$r_pet_type."',
								'".$r_pet_type2."','".$r_pet_year."','".$r_pet_month."','".$r_pet_day."','".$r_pet_gender."',
								'".$r_neutral."','".$pet_weight."','Y'
							)
						";
						//$temp .= $sql."<br/>";

						$result2 = mysqli_query($connection, $sql);
                        $pay_seq = mysqli_insert_id($connection);
						if($result2){
							$pet_seq = mysqli_insert_id($connection);

							$sql = "
								INSERT INTO tb_payment_log (
									pet_seq, session_id, customer_id, order_id, artist_id,
									cellphone, etc_memo, update_time, approval, product, product_type
								) VALUES (
									'".$pet_seq."', '0', '신규등록(".$tmp_seq.")', '0', '".$r_artist_id."', 
									'".$r_cellphone."', '".$r_memo."', NOW(), '0', '".$r_pet_name."', 'A'
								)
							";
							//$temp .= $sql."<br/>";

							$result3 = mysqli_query($connection, $sql);
							$pay_seq = mysqli_insert_id($connection);
							if($result3){
								$sql = "
									SELECT *
									FROM tb_artist_customer_list
									WHERE artist_id = '".$r_artist_id."'
										AND pet_seq = '".$pet_seq."'
								";
								//$temp .= $sql."<br/>";

								$result4 = mysqli_query($connection, $sql);
								$ac_cnt = mysqli_num_rows($result4);
								if($ac_cnt == 0){ // insert
									$sql = "
										INSERT INTO tb_artist_customer_list (pet_seq, artist_id, pet_name) VALUES
										('".$pet_seq."', '".$r_artist_id."', '".$r_pet_name."')
									";
									//$temp .= $sql."<br/>";

									$result4 = mysqli_query($connection, $sql);
									if($result4){
										$return_data = array("code" => "000000", "data" => $r_pet_name, "pay_seq"=>$pay_seq); // OK
									}else{
										$return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
									}
								}else{ // update
									$sql = "
										UPDATE tb_artist_customer_list SET
											pet_name = '".$r_pet_name."'
										WHERE artist_id = '".$r_artist_id."'
											AND pet_seq = '".$pet_seq."'
									";
									//$temp .= $sql."<br/>";

									$result4 = mysqli_query($connection, $sql);
									if($result4){
										$return_data = array("code" => "000000", "data" => $r_pet_name); // OK
									}else{
										$return_data = array("code" => "000104", "data" => "펫이름 입력에 실패했습니다.");
									}
								}
							}else{
								$return_data = array("code" => "000103", "data" => "결제정보 입력에 실패했습니다.");
							}
						}else{
							$return_data = array("code" => "000102", "data" => "펫정보 입력에 실패했습니다.");
						}
					}else{
						$return_data = array("code" => "000101", "data" => "회원정보 입력에 실패했습니다.");
					}
				}else{ // 가입자일 경우
					if($r_customer_id != "" && $r_pet_seq != ""){ // 정회원
						$sql = "
							UPDATE tb_mypet SET
								name = '".$r_pet_name."', 
								type = '".$r_pet_kind."', 
								pet_type = '".$r_pet_type."', 
								pet_type2 = '".$r_pet_type2."', 
								year = '".$r_pet_year."', 
								month = '".$r_pet_month."', 
								day = '".$r_pet_day."', 
								gender = '".$r_pet_gender."', 
								neutral = '".$r_neutral."', 
								weight = '".$pet_weight."'
							WHERE customer_id = '".$r_customer_id."'
								AND pet_seq = '".$r_pet_seq."'
								AND tmp_yn = 'N'
						";
						//$temp .= $sql."<br/>";

						$result2 = mysqli_query($connection, $sql);
						if($result2){
							$sql = "
								INSERT INTO tb_payment_log (
									pet_seq, session_id, customer_id, order_id, artist_id,
									cellphone, etc_memo, update_time, approval, product, product_type
								) VALUES (
									'".$r_pet_seq."', '0', '신규등록(".$r_customer_id.")', '0', '".$r_artist_id."', 
									'".$r_cellphone."', '".$r_memo."', NOW(), '0', '".$r_pet_name."', 'A'
								)
							";
							//$temp .= $sql."<br/>";
<<<<<<< HEAD

=======
						    //echo $sql;
>>>>>>> d9eeb70938c4a6807528f6e5fc17be88ec5a06e3
							$result3 = mysqli_query($connection, $sql);
							$pay_seq = mysqli_insert_id($connection);
							if($result3){
								$sql = "
									SELECT *
									FROM tb_artist_customer_list
									WHERE artist_id = '".$r_artist_id."'
										AND pet_seq = '".$r_pet_seq."'
								";
								//$temp .= $sql."<br/>";

								$result4 = mysqli_query($connection, $sql);
								$ac_cnt = mysqli_num_rows($result4);
								if($ac_cnt == 0){ // insert
									$sql = "
										INSERT INTO tb_artist_customer_list (pet_seq, artist_id, pet_name) VALUES
										('".$r_pet_seq."', '".$r_artist_id."', '".$r_pet_name."')
									";
									//$temp .= $sql."<br/>";

									$result4 = mysqli_query($connection, $sql);
									if($result4){
										$return_data = array("code" => "000000", "data" => $r_pet_name, "pay_seq"=>$pay_seq); // OK
									}else{
										$return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
									}
								}else{ // update
									$sql = "
										UPDATE tb_artist_customer_list SET
											pet_name = '".$r_pet_name."'
										WHERE artist_id = '".$r_artist_id."'
											AND pet_seq = '".$r_pet_seq."'
									";
									//$temp .= $sql."<br/>";

									$result4 = mysqli_query($connection, $sql);
									if($result4){
										$return_data = array("code" => "000000", "data" => $r_pet_name, "pay_seq"=>$pay_seq); // OK
									}else{
										$return_data = array("code" => "000104", "data" => "펫이름 입력에 실패했습니다.");
									}
								}
							}else{
								$return_data = array("code" => "000112", "data" => "결제정보 입력에 실패했습니다.");
							}
						}else{
							$return_data = array("code" => "000111", "data" => "펫정보 입력에 실패했습니다.");
						}
					 }else if($r_tmp_seq != "" && $r_pet_seq != "") { // 가회원
                        $sql = "
							UPDATE tb_mypet SET
								name = '" . $r_pet_name . "', 
								name_for_owner = '" . $r_pet_name . "', 
								type = '" . $r_pet_kind . "', 
								pet_type = '" . $r_pet_type . "', 
								pet_type2 = '" . $r_pet_type2 . "', 
								year = '" . $r_pet_year . "', 
								month = '" . $r_pet_month . "', 
								day = '" . $r_pet_day . "', 
								gender = '" . $r_pet_gender . "', 
								neutral = '" . $r_neutral . "', 
								weight = '" . $pet_weight . "'
							WHERE tmp_seq = '" . $r_tmp_seq . "'
								AND pet_seq = '" . $r_pet_seq . "'
								AND tmp_yn = 'Y'
						";
                        //$temp .= $sql."<br/>";

                        $result2 = mysqli_query($connection, $sql);
                        if ($result2) {
                            $sql = "
								INSERT INTO tb_payment_log (
									pet_seq, session_id, customer_id, order_id, artist_id,
									cellphone, etc_memo, update_time, approval, product, product_type
								) VALUES (
									'" . $r_pet_seq . "', '0', '신규등록(" . $r_tmp_seq . ")', '0', '" . $r_artist_id . "', 
									'" . $r_cellphone . "', '" . $r_memo . "', NOW(), '0', '" . $r_pet_name . "', 'A'
								)
							";
<<<<<<< HEAD
                            //$temp .= $sql."<br/>";

                            $result3 = mysqli_query($connection, $sql);
                            if ($result3) {
                                $sql = "
=======
							//$temp .= $sql."<br/>";
                            //echo $sql;
							$result3 = mysqli_query($connection, $sql);
                            $pay_seq = mysqli_insert_id($connection);
							if($result3){
								$sql = "
>>>>>>> d9eeb70938c4a6807528f6e5fc17be88ec5a06e3
									SELECT *
									FROM tb_artist_customer_list
									WHERE artist_id = '" . $r_artist_id . "'
										AND pet_seq = '" . $r_pet_seq . "'
								";
                                //$temp .= $sql."<br/>";

                                $result4 = mysqli_query($connection, $sql);
                                $ac_cnt = mysqli_num_rows($result4);
                                if ($ac_cnt == 0) { // insert
                                    $sql = "
										INSERT INTO tb_artist_customer_list (pet_seq, artist_id, pet_name) VALUES
										('" . $r_pet_seq . "', '" . $r_artist_id . "', '" . $r_pet_name . "')
									";
                                    //$temp .= $sql."<br/>";

<<<<<<< HEAD
                                    $result4 = mysqli_query($connection, $sql);
                                    if ($result4) {
                                        $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                    } else {
                                        $return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
                                    }
                                } else { // update
                                    $sql = "
=======
									$result4 = mysqli_query($connection, $sql);

									if($result4){
										$return_data = array("code" => "000000", "data" => $r_pet_name, "pay_seq"=>$pay_seq); // OK
									}else{
										$return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
									}
								}else{ // update
									$sql = "
>>>>>>> d9eeb70938c4a6807528f6e5fc17be88ec5a06e3
										UPDATE tb_artist_customer_list SET
											pet_name = '" . $r_pet_name . "'
										WHERE artist_id = '" . $r_artist_id . "'
											AND pet_seq = '" . $r_pet_seq . "'
									";
                                    //$temp .= $sql."<br/>";

                                    $result4 = mysqli_query($connection, $sql);
                                    if ($result4) {
                                        $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                    } else {
                                        $return_data = array("code" => "000104", "data" => "펫이름 입력에 실패했습니다.");
                                    }
                                }
                            } else {
                                $return_data = array("code" => "000122", "data" => "결제정보 입력에 실패했습니다.");
                            }
                        } else {
                            $return_data = array("code" => "000121", "data" => "펫정보 입력에 실패했습니다.");
                        }
                    }else{ // 정회원 등록되어있지만 펫 없을경우 고려 ㅡㅡ by.glory

                        if($r_customer_id != ""){ // 정회원 펫 추가
                            $sql = "
                                INSERT INTO tb_mypet (
                                    customer_id, name, name_for_owner, type, pet_type, 
                                    pet_type2, year, month, day, gender, 
                                    neutral, weight, tmp_yn
                                ) VALUES (
                                    '".$r_customer_id."','".$r_pet_name."','".$r_pet_name."','".$r_pet_kind."','".$r_pet_type."',
                                    '".$r_pet_type2."','".$r_pet_year."','".$r_pet_month."','".$r_pet_day."','".$r_pet_gender."',
                                    '".$r_neutral."','".$pet_weight."','N'
                                )
                            ";
                            //$temp .= $sql."<br/>";

                            $result2 = mysqli_query($connection, $sql);
                            if($result2){
                                $pet_seq = mysqli_insert_id($connection);

                                $sql = "
                                    INSERT INTO tb_payment_log (
                                        pet_seq, session_id, customer_id, order_id, artist_id,
                                        cellphone, etc_memo, update_time, approval, product, product_type
                                    ) VALUES (
                                        '".$pet_seq."', '0', '신규등록(".$r_customer_id.")', '0', '".$r_artist_id."', 
                                        '".$r_cellphone."', '".$r_memo."', NOW(), '0', '".$r_pet_name."', 'A'
                                    )
                                ";
                                //$temp .= $sql."<br/>";

                                $result3 = mysqli_query($connection, $sql);
                                if($result3){
                                    $sql = "
                                        SELECT *
                                        FROM tb_artist_customer_list
                                        WHERE artist_id = '".$r_artist_id."'
                                            AND pet_seq = '".$pet_seq."'
                                    ";
                                    //$temp .= $sql."<br/>";

                                    $result4 = mysqli_query($connection, $sql);
                                    $ac_cnt = mysqli_num_rows($result4);
                                    if($ac_cnt == 0){ // insert
                                        $sql = "
                                            INSERT INTO tb_artist_customer_list (pet_seq, artist_id, pet_name) VALUES
                                            ('".$pet_seq."', '".$r_artist_id."', '".$r_pet_name."')
                                        ";
                                        //$temp .= $sql."<br/>";

                                        $result4 = mysqli_query($connection, $sql);
                                        if($result4){
                                            $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                        }else{
                                            $return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
                                        }
                                    }else{ // update
                                        $sql = "
                                            UPDATE tb_artist_customer_list SET
                                                pet_name = '".$r_pet_name."'
                                            WHERE artist_id = '".$r_artist_id."'
                                                AND pet_seq = '".$pet_seq."'
                                        ";
                                        //$temp .= $sql."<br/>";

                                        $result4 = mysqli_query($connection, $sql);
                                        if($result4){
                                            $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                        }else{
                                            $return_data = array("code" => "000104", "data" => "펫이름 입력에 실패했습니다.");
                                        }
                                    }
                                }else{
                                    $return_data = array("code" => "000103", "data" => "결제정보 입력에 실패했습니다.");
                                }
                            }else{
                                $return_data = array("code" => "000102", "data" => "펫정보 입력에 실패했습니다.");
                            }
                        }else if($r_tmp_seq != ""){
                            $sql = "
                                INSERT INTO tb_mypet (
                                    tmp_seq, name, name_for_owner, type, pet_type, 
                                    pet_type2, year, month, day, gender, 
                                    neutral, weight, tmp_yn
                                ) VALUES (
                                    '".$r_tmp_seq."','".$r_pet_name."','".$r_pet_name."','".$r_pet_kind."','".$r_pet_type."',
                                    '".$r_pet_type2."','".$r_pet_year."','".$r_pet_month."','".$r_pet_day."','".$r_pet_gender."',
                                    '".$r_neutral."','".$pet_weight."','Y'
                                )
                            ";
                            //$temp .= $sql."<br/>";

                            $result2 = mysqli_query($connection, $sql);
                            if($result2){
                                $pet_seq = mysqli_insert_id($connection);

                                $sql = "
                                    INSERT INTO tb_payment_log (
                                        pet_seq, session_id, customer_id, order_id, artist_id,
                                        cellphone, etc_memo, update_time, approval, product, product_type
                                    ) VALUES (
                                        '".$pet_seq."', '0', '신규등록()', '0', '".$r_artist_id."', 
                                        '".$r_cellphone."', '".$r_memo."', NOW(), '0', '".$r_pet_name."', 'A'
                                    )
                                ";
                                //$temp .= $sql."<br/>";

                                $result3 = mysqli_query($connection, $sql);
                                if($result3){
                                    $sql = "
                                        SELECT *
                                        FROM tb_artist_customer_list
                                        WHERE artist_id = '".$r_artist_id."'
                                            AND pet_seq = '".$pet_seq."'
                                    ";
                                    //$temp .= $sql."<br/>";

                                    $result4 = mysqli_query($connection, $sql);
                                    $ac_cnt = mysqli_num_rows($result4);
                                    if($ac_cnt == 0){ // insert
                                        $sql = "
                                            INSERT INTO tb_artist_customer_list (pet_seq, artist_id, pet_name) VALUES
                                            ('".$pet_seq."', '".$r_artist_id."', '".$r_pet_name."')
                                        ";
                                        //$temp .= $sql."<br/>";

                                        $result4 = mysqli_query($connection, $sql);
                                        if($result4){
                                            $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                        }else{
                                            $return_data = array("code" => "000105", "data" => "펫이름 입력에 실패했습니다.");
                                        }
                                    }else{ // update
                                        $sql = "
                                            UPDATE tb_artist_customer_list SET
                                                pet_name = '".$r_pet_name."'
                                            WHERE artist_id = '".$r_artist_id."'
                                                AND pet_seq = '".$pet_seq."'
                                        ";
                                        //$temp .= $sql."<br/>";

                                        $result4 = mysqli_query($connection, $sql);
                                        if($result4){
                                            $return_data = array("code" => "000000", "data" => $r_pet_name); // OK
                                        }else{
                                            $return_data = array("code" => "000104", "data" => "펫이름 입력에 실패했습니다.");
                                        }
                                    }
                                }else{
                                    $return_data = array("code" => "000103", "data" => "결제정보 입력에 실패했습니다.");
                                }
                            }else{
                                $return_data = array("code" => "000102", "data" => "펫정보 입력에 실패했습니다.");
                            }

                        }else{
                            $return_data = array("code" => "000193", "data" => "중요 데이터가 누락되었습니다.");
                        }

                    }
				}
			}else{
				$return_data = array("code" => "000199", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_add_mypet"){ // 예약하기 - 펫등록하기 사용
			$r_artist_id	= ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_customer_id	= ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_pet_name		= ($_POST["pet_name"] && $_POST["pet_name"] != "")? $_POST["pet_name"] : "";
			$r_pet_kind		= ($_POST["pet_kind"] && $_POST["pet_kind"] != "")? $_POST["pet_kind"] : "dog";
			$r_pet_type		= ($_POST["pet_type"] && $_POST["pet_type"] != "")? $_POST["pet_type"] : "골든두들";
			$r_pet_type2	= ($_POST["pet_type2"] && $_POST["pet_type2"] != "")? $_POST["pet_type2"] : "";
			$r_pet_year		= ($_POST["pet_year"] && $_POST["pet_year"] != "")? $_POST["pet_year"] : "2020";
			$r_pet_month	= ($_POST["pet_month"] && $_POST["pet_month"] != "")? $_POST["pet_month"] : "1";
			$r_pet_day		= ($_POST["pet_day"] && $_POST["pet_day"] != "")? $_POST["pet_day"] : "1";
			$r_pet_gender	= ($_POST["pet_gender"] && $_POST["pet_gender"] != "")? $_POST["pet_gender"] : "남아";
			$r_neutral		= ($_POST["neutral"] && $_POST["neutral"] != "")? $_POST["neutral"] : "0";
			$r_pet_weight1	= ($_POST["pet_weight1"] && $_POST["pet_weight1"] != "")? $_POST["pet_weight1"] : "5";
			$r_pet_weight2	= ($_POST["pet_weight2"] && $_POST["pet_weight2"] != "")? $_POST["pet_weight2"] : "0";
			$pet_weight		= floatval($r_pet_weight1.".".$r_pet_weight2);
			
			if($r_customer_id != "" && addslashes($r_pet_name) != ""){
				$sql = "
					INSERT INTO tb_mypet (
						customer_id, name, name_for_owner, type, pet_type, 
						pet_type2, year, month, day, gender, 
						neutral, weight, tmp_yn
					) VALUES (
						'".$r_customer_id."','".addslashes($r_pet_name)."','".addslashes($r_pet_name)."','".$r_pet_kind."','".$r_pet_type."',
						'".addslashes($r_pet_type2)."','".$r_pet_year."','".$r_pet_month."','".$r_pet_day."','".$r_pet_gender."',
						'".$r_neutral."','".$pet_weight."','N'
					)
				";
				$result = mysqli_query($connection, $sql);
				if($result){
					$data = mysqli_insert_id($connection);
					$return_data = array("code" => "000000", "data" => $data); // OK
				}else{
					$return_data = array("code" => "000202", "data" => "펫 등록에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000201", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>