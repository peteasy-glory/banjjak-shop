<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");

$data = array();
$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

if($r_mode){
    if($r_mode == "delete_mypet"){
        $r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";

        $sql = "
				UPDATE tb_mypet SET
					data_delete = '1'
				WHERE pet_seq = '".$r_pet_seq."'
			";
        $result = mysqli_query($connection,$sql);
        if($result){
            $return_data = array("code" => "000000", "data" => "OK");
        }else{
            $return_data = array("code" => "000001", "data" => "펫 삭제에 실패했습니다.");
        }
    }else if($r_mode == "delete_payment_log"){
        $r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
        $r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $r_hotel_cnt = ($_POST["hotel_cnt"] && $_POST["hotel_cnt"] != "")? $_POST["hotel_cnt"] : ""; // 호텔 이용기록이 있는지
        $r_play_cnt = ($_POST["play_cnt"] && $_POST["play_cnt"] != "")? $_POST["play_cnt"] : ""; // 유치원 이용기록이 있는지

        // 호텔 이용 기록 있을 때
        if($r_hotel_cnt > 0 && $r_hotel_cnt != ""){
            $hotel_table = ", tb_hotel_payment_log AS b ";
            $hotel_set = " , b.data_delete = '1' ";
            $hotel_update = " AND b.artist_id = '".$r_artist_id."' AND b.pet_seq = '".$r_pet_seq."' ";
        }

        // 유치원 이용 기록이 있을 때
        if($r_play_cnt > 0 && $r_play_cnt != ""){
            $play_table = ", tb_playroom_payment_log AS c ";
            $play_set = " , c.data_delete = '1' ";
            $play_update = " AND c.artist_id = '".$r_artist_id."' AND c.pet_seq = '".$r_pet_seq."' ";
        }

        if($r_pet_seq != "" && $r_artist_id != ""){
            $sql = "
					UPDATE tb_payment_log AS a ".$hotel_table." ".$play_table." SET
						a.data_delete = '1'
						".$hotel_set." 
						".$play_set."
					WHERE a.pet_seq = '".$r_pet_seq."' AND a.artist_id = '".$r_artist_id."'
					".$hotel_update." 
					".$play_update."
				";
            $result = mysqli_query($connection,$sql);
            if($result){
                $return_data = array("code" => "000000", "data" => "OK");
            }else{
                $return_data = array("code" => "000001", "data" => "펫 삭제에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
        }

    }else if($r_mode == "delete_payment_all"){
        $r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
        $r_hotel_cnt = ($_POST["hotel_cnt"] && $_POST["hotel_cnt"] != "")? $_POST["hotel_cnt"] : ""; // 호텔 이용기록이 있는지
        $r_play_cnt = ($_POST["play_cnt"] && $_POST["play_cnt"] != "")? $_POST["play_cnt"] : ""; // 유치원 이용기록이 있는지
        $approval_set = ($_POST["customer_id"] && $_POST["customer_id"] != "")? ", a.approval = '3' " : ""; // 정회원일 경우 예약거부처리

        // 호텔 이용 기록 있을 때
        if($r_hotel_cnt > 0 && $r_hotel_cnt != ""){
            $hotel_table = ", tb_hotel_payment_log AS b ";
            $hotel_set = " , b.data_delete = '1' ";
            $hotel_update = " AND b.artist_id = '".$r_artist_id."' AND b.cellphone = '".$r_cellphone."' ";
        }

        // 유치원 이용 기록이 있을 때
        if($r_play_cnt > 0 && $r_play_cnt != ""){
            $play_table = ", tb_playroom_payment_log AS c ";
            $play_set = " , c.data_delete = '1' ";
            $play_update = " AND c.artist_id = '".$r_artist_id."' AND c.cellphone = '".$r_cellphone."' ";
        }

        if($r_artist_id != "" && $r_cellphone != ""){
            $sql = "
					UPDATE tb_payment_log AS a ".$hotel_table." ".$play_table." SET
						a.data_delete = '1'
						".$approval_set."
						".$hotel_set."
						".$play_set."
					WHERE a.cellphone = '".$r_cellphone."' AND a.artist_id = '".$r_artist_id."'
					".$hotel_update."
					".$play_update."
				";
            $result = mysqli_query($connection,$sql);
            if($result){
                $sql = "UPDATE tb_sent_cell_id SET is_delete = 1 WHERE artist_id = '".$r_artist_id."' AND cellphone = '".$r_cellphone."'";
                $result = mysqli_query($connection,$sql);
                if($result){
                    $return_data = array("code" => "000000", "data" => "OK");
                }
                else{
                    $return_data = array("code" => "000001", "data" => "발신 정보 삭제에 실패했습니다.");
                }
            }else{
                $return_data = array("code" => "000001", "data" => "회원 삭제에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
        }
    }else if($r_mode == "delete_another_payment_pet"){
        $r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
        $r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
        $r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";



        if($r_artist_id != "" && $r_cellphone != ""){
            $sql = "
					INSERT INTO tb_payment_log(
						pet_seq, 
						session_id, 
						customer_id, 
						order_id, 
						artist_id, 
						cellphone, 
						cancel_time, 
						data_delete
					)VALUES(
						'".$r_pet_seq."', 
						'another_shop_pet_delete', 
						'".$r_customer_id."', 
						'0', 
						'".$r_artist_id."', 
						'".$r_cellphone."', 
						NOW(), 
						'1'
					)
				";
            $result = mysqli_query($connection,$sql);
            if($result){
                $return_data = array("code" => "000000", "data" => "OK");
            }else{
                $return_data = array("code" => "000001", "data" => "회원 삭제에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
        }
    }else if($r_mode == "delete_another_payment_all"){
        $r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
        $r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
        $r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";



        if($r_artist_id != "" && $r_cellphone != ""){
            $sql = "
					INSERT INTO tb_payment_log(
						pet_seq, 
						session_id, 
						customer_id, 
						order_id, 
						artist_id, 
						cellphone, 
						cancel_time, 
						data_delete
					)VALUES(
						'".$r_pet_seq."', 
						'another_shop_pet_delete', 
						'".$r_customer_id."', 
						'0', 
						'".$r_artist_id."', 
						'".$r_cellphone."', 
						NOW(), 
						'1'
					)
				";
            $result = mysqli_query($connection,$sql);
            if($result){
                $return_data = array("code" => "000000", "data" => "OK");
            }else{
                $return_data = array("code" => "000001", "data" => "회원 삭제에 실패했습니다.");
            }
        }else{
            $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
        }
    }else{
        $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
    }
}else{
    $return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
}
echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>