<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

	//$s_artist_id = $_SESSION["gobeauty_user_id"];
	//$s_user_id = $_SESSION["bjj_cellphone"];
	//$s_pet_id = $_SESSION["bjj_pet_seq"];
	$s_artist_id = $_POST["artist_id"];
	$s_user_id = $_POST["user_id"];
	$s_pet_id = $_POST["pet_id"];
	$Crypto = new Crypto();

	$is_session = false;
	$bs_seq = ""; // 싸인번호(seq)
	$ba_seq = ""; // 미용동의서번호(seq)
	$auth_url = ""; // 암호화url
	$customer_id = ""; // 회원(정회원:email, 가회원:휴대폰번호)
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");

	if($s_artist_id != "" || $s_user_id != "" || $s_pet_id != ""){
		$is_session = true;
	}

	if($is_session == true){
		$r_tmp_seq = $_POST["tmp_seq"]; // 가회원 회원번호(seq)
		$r_tmp_yn = $_POST["tmp_yn"]; // 가회원 여부(Y-가회원, N-정회원) - tb_mypet
		$r_ba_seq = $_POST["ba_seq"]; // 미용동의서 번호(수정용)
		$r_customer_id = $_POST["customer_id"]; // 정회원 id(email)
		$r_customer_name = $_POST["customer_name"]; // 회원명
		$r_cellphone = $_POST["cellphone"]; // 회원 휴대폰번호
		$r_pet_kind = $_POST["pet_kind"]; // 펫 - 종류(개, 고양이)
		$r_pet_type = $_POST["pet_type"]; // 펫 - 품종
		$r_pet_type2 = $_POST["pet_type2"]; // 펫 - 품종(기타)
		$r_pet_name = $_POST["pet_name"]; // 펫 - 이름
		$r_pet_year = $_POST["pet_year"]; // 펫 - 생년월일(연)
		$r_pet_month = $_POST["pet_month"]; // 펫 - 생년월일(월)
		$r_pet_day = $_POST["pet_day"]; // 펫 - 생년월일(일)
		$r_gender = $_POST["gender"]; // 펫 - 성별
		$r_neutral = $_POST["neutral"]; // 펫 - 중성화여부
		$r_vaccination = $_POST["vaccination"]; // 펫 - 예방접종
		$r_heart_trouble = ($_POST["heart_trouble"] != "")? $_POST["heart_trouble"] : "0"; // 펫 - 심장질환여부
		$r_dermatosis = ($_POST["dermatosis"] != "")? $_POST["dermatosis"] : "0"; // 펫 - 피부병
		$r_etc_for_disease = $_POST["etc_for_disease"]; // 펫 - 기타 질병사항
		$r_luxation = $_POST["luxation"]; // 펫 - 슬개골탈구
		$r_bite = $_POST["bite"]; // 펫 - 입질
		$r_marking = ($_POST["marking"] != "")? $_POST["marking"] : "0"; // 펫 - 마킹
		$r_mounting = ($_POST["mounting"] != "")? $_POST["mounting"] : "0"; // 펫 - 마운팅
		$r_etc_for_owner = $_POST["etc_for_owner"]; // 펫 - 기타 특이사항
		$r_is_agree = $_POST["is_agree"]; // 미용동의
		$r_is_customer = $_POST["is_customer"]; // 개인정보동의
		$r_sign_image_url = $_POST["sign_image"]; // 사인 이미지
		$r_doc_type = ($_POST["docType"] && $_POST["docType"] != "")? $_POST["docType"] : "0"; // 동의서 종류 (0-미용, 1-호텔)
		$r_doc_type = ($r_doc_type != "undefined")? $r_doc_type : "0"; // undefined 에러 발생하여 한번더 예외처리

		// 세션과 전화번호가 다를 경우 가회원은 회원데이터 변경(생성X)
		if($r_tmp_yn == "N"){ // 정회원
			$return_data = array("code" => "000000", "data" => "not tmp_user");
		}else{
			if($s_user_id != $r_cellphone){ // 가져온 번호와 입력한 번호가 일치하지 않을때 변경
				// /shop/update_tmp_user.php 에서 가져옴
				if($r_cellphone != null && $r_cellphone != ""){
					//가회원 중복 검사
					$dup_query = "SELECT * FROM tb_tmp_user WHERE cellphone = '{$r_cellphone}'";
					$dup_result = mysqli_query($connection,$dup_query);
					$dup_cnt = mysqli_num_rows($dup_result);
					$dup_data = mysqli_fetch_object($dup_result);

					//정회원 중복 검사
					$cellphone_encode = $Crypto->encode($r_cellphone, $access_key, $secret_key);
					$member_query = "SELECT * FROM tb_customer WHERE cellphone = '{$cellphone_encode}' and nickname not like 'cellp_%'";
					$member_result = mysqli_query($connection,$member_query);
					$member_cnt = mysqli_num_rows($member_result);

					if(($dup_cnt == 0 || $dup_data->tmp_seq == $r_tmp_seq) && $member_cnt == 0){
						if($r_tmp_seq > 0){
							$tmp_user_query = "SELECT * FROM tb_tmp_user WHERE tmp_seq = '{$r_tmp_seq}'";
							$tmp_user_result = mysqli_query($connection,$tmp_user_query);
							$tmp_user_data = mysqli_fetch_object($tmp_user_result);

							if(isset($tmp_user_data) && $tmp_user_data != null){
								$before_cellphone = $tmp_user_data->cellphone;

								$tmp_user_update_query = "UPDATE tb_tmp_user SET cellphone = '{$r_cellphone}' WHERE tmp_seq = '{$r_tmp_seq}'";
								mysqli_query($connection,$tmp_user_update_query);

								$payment_list = "SELECT * FROM tb_payment_log WHERE cellphone = '{$before_cellphone}'";
								$payment_list_result = mysqli_query($connection,$payment_list);
								
								while($payment_data = mysqli_fetch_object($payment_list_result)){
									$pay_data = json_decode($payment_data->pay_data);
									$pay_data->cellphone = $r_cellphone;

									$pay_data = json_encode($pay_data, JSON_UNESCAPED_UNICODE);

									$update_query = "UPDATE tb_payment_log SET pay_data = '{$pay_data}', cellphone = '{$r_cellphone}' WHERE payment_log_seq = '{$payment_data->payment_log_seq}'";
									mysqli_query($connection,$update_query);
								}

								$return_data = array("code" => "000000", "data" => "OK");
							}else{
								$return_data = array("code" => "000006", "data" => "잘못된 접근입니다.");
							}
						}else{
							$return_data = array("code" => "000005", "data" => "잘못된 접근입니다.");
						}
					}else{
						if(!$member_cnt == 0){
							$return_data = array("code" => "000004", "data" => "동일한 휴대전화 번호가 존재합니다.(member)");
						}

						if(!$dup_cnt == 0){
							$return_data = array("code" => "000003", "data" => "동일한 휴대전화 번호가 존재합니다.");
						}
					}
				}else{
					$return_data = array("code" => "000002", "data" => "잘못된 접근입니다.");
				}
			}else{
				$return_data = array("code" => "000000", "data" => "SAME");
			}
		}

		// 펫 업데이트
		if($s_pet_id && $s_pet_id != ""){
			$sql = "
				UPDATE tb_mypet SET
					type = '".$r_pet_kind."',
					pet_type = '".$r_pet_type."',
					pet_type2 = '".$r_pet_type2."',
					name = '".$r_pet_name."',
					year = '".$r_pet_year."',
					month = '".$r_pet_month."',
					day = '".$r_pet_day."',
					gender = '".$r_gender."',
					neutral = '".$r_neutral."',
					vaccination = '".$r_vaccination."',
					heart_trouble = '".$r_heart_trouble."',
					dermatosis = '".$r_dermatosis."',
					etc_for_disease = '".$r_etc_for_disease."',
					luxation = '".$r_luxation."',
					bite = '".$r_bite."',
					marking = '".$r_marking."',
					mounting = '".$r_mounting."',
					etc_for_owner = '".$r_etc_for_owner."'
				WHERE pet_seq = '".$s_pet_id."'
					AND tmp_yn = '".$r_tmp_yn."'
			";
			if($r_tmp_yn == "N"){ // 정회원
				$sql .= " AND customer_id = '".$r_customer_id."' ";
			}else{ // 가회원
				$sql .= " AND tmp_seq = '".$r_tmp_seq."' ";
			}

			$result = mysqli_query($connection,$sql);
			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "000008", "data" => "펫 업데이트에 실패했습니다.".$sql);
			}
		}else{
			$return_data = array("code" => "000007", "data" => "잘못된 접근입니다.");
		}

		if($r_tmp_yn == "N"){ // 정회원
			$customer_id = $r_customer_id;
		}else{ // 가회원
			$customer_id = $r_cellphone;
		}

		if($r_sign_image_url != "" && $customer_id != ""){
			// 싸인 등록
			$sql = "
				INSERT INTO tb_beauty_sign (customer_id, image, reg_date) VALUES ('".$customer_id."', '".$r_sign_image_url."', NOW())
			";

			$result = mysqli_query($connection,$sql);
			if($result){
				$bs_seq = mysqli_insert_id($connection);
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "000011", "data" => "미용동의용 싸인 등록에 실패했습니다.");
			}
		}

		// 주소생성
		$auth_url = $Crypto->encode($customer_id.$s_artist_id.$s_pet_id.DATE("Y-m-d H:i:s"), $access_key, $secret_key);

		// 미용동의서 등록
		$return_data = beauty_agree($bs_seq, $customer_id, $s_artist_id, $s_pet_id, $r_customer_name, $r_cellphone, $r_is_agree, $r_is_customer, $r_doc_type, $auth_url, $r_ba_seq);
	}else{
		$return_data = array("code" => "999990", "data" => "Session over");
	}

	echo json_encode($return_data);

?>

<?php
///////// function list /////////

function beauty_agree($bs_seq, $customer_id, $s_artist_id, $s_pet_id, $r_customer_name, $r_cellphone, $r_is_agree, $r_is_customer, $r_doc_type, $auth_url, $r_ba_seq){
	global $connection;
	$return_data = array();
	$ba_cnt = 0;

	if($r_ba_seq != ""){
		$sql = "
			SELECT *
			FROM tb_beauty_agree
			WHERE ba_seq = '".$r_ba_seq."'
		";
		$result = mysqli_query($connection,$sql);
		$ba_cnt = mysqli_num_rows($result);
	}else{
		$sql = "
			SELECT *
			FROM tb_beauty_agree
			WHERE customer_id = '".$customer_id."'
				AND artist_id = '".$s_artist_id."'
				AND pet_id = '".$s_pet_id."'
				AND doc_type = '".$r_doc_type."'
		";
		$result = mysqli_query($connection,$sql);
		$ba_cnt = mysqli_num_rows($result);
	}

	if($ba_cnt == 0){
		$sql = "INSERT INTO tb_beauty_agree (bs_seq, customer_id, artist_id, pet_id, customer_name, cellphone, is_agree, is_customer, doc_type, auth_url, reg_date) 
				VALUES ('".$bs_seq."', '".$customer_id."', '".$s_artist_id."', '".$s_pet_id."', '".$r_customer_name."','".$r_cellphone."', '".$r_is_agree."', '".$r_is_customer."', '".$r_doc_type."', '".$auth_url."', NOW())";
		//echo $sql;

		$result = mysqli_query($connection, $sql);
		
		if($result){
			$ba_seq = mysqli_insert_id($connection);
			$return_data = array("code" => "000000", "data" => $ba_seq);
		}else{
			$return_data = array("code" => "000009", "data" => "미용동의서 생성에 실패했습니다.");
		}
		
	}else{
		if($r_ba_seq != ""){
			$sql = "
				UPDATE tb_beauty_agree SET
					bs_seq = '".$bs_seq."',
					customer_id = '".$customer_id."',
					pet_id = '".$s_pet_id."',
					customer_name = '".$r_customer_name."',
					cellphone = '".$r_cellphone."',
					is_agree = '".$r_is_agree."',
					is_customer = '".$r_is_customer."',
					doc_type = '".$r_doc_type."'
				WHERE ba_seq = '".$r_ba_seq."'
					AND artist_id = '".$s_artist_id."'
			";
			//echo $sql;
			$result = mysqli_query($connection,$sql);
			if($result){
				$return_data = array("code" => "000000", "data" => "update"); // (주의) "update" 단어로 알림톡 발송여부 체크함
			}else{
				$return_data = array("code" => "000010", "data" => "미용동의서 업데이트에 실패했습니다.");
			}
		}else{
			$sql = "
				UPDATE tb_beauty_agree SET
					bs_seq = '".$bs_seq."',
					customer_id = '".$customer_id."',
					pet_id = '".$s_pet_id."',
					customer_name = '".$r_customer_name."',
					cellphone = '".$r_cellphone."',
					is_agree = '".$r_is_agree."',
					is_customer = '".$r_is_customer."'
				WHERE artist_id = '".$s_artist_id."'
					AND pet_id = '".$s_pet_id."'
			";
			//echo $sql;
			$result = mysqli_query($connection,$sql);
			if($result){
				$return_data = array("code" => "000000", "data" => "update"); // (주의) "update" 단어로 알림톡 발송여부 체크함
			}else{
				$return_data = array("code" => "000010", "data" => "미용동의서 업데이트에 실패했습니다.");
			}
		}
	}

	return $return_data;
}

?>
