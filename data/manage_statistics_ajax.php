<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$r_mode = $_POST["mode"];

if($r_mode != ""){
    if($r_mode == "getSearchDetailList"){
        $r_type = $_POST["type"];
        $r_artist_id = $_POST["artist_id"];

        if($r_type == "date"){
            $payment_type_arr = array(
                "0" => "미용",
                "1" => "호텔",
                "2" => "유치원"
            );

            foreach($payment_type_arr AS $key => $value){
                $data["list"][] = $value;
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else if($r_type == "service"){
            /*$service_arr = array(
                "0" => "가위컷",
                "1" => "목욕",
                "2" => "부분+목욕",
                "3" => "부분미용",
                "4" => "스포팅",
                "5" => "썸머컷",
                "6" => "위생",
                "7" => "전체미용"
            );*/
            $dog_product_title = array();
            $dog_product_arr = array(
                "무게",
                "목욕",
                "부분미용",
                "부분+목욕",
                "위생",
                "위생+목욕",
                "전체미용",
                "스포팅",
                "가위컷",
                "썸머컷"
            );
            $dog_product_seq = array(
                "목욕"         =>'bath',
                "부분미용"      =>'part',
                "부분+목욕"     =>'bath_part',
                "위생"         =>'sanitation',
                "위생+목욕"    =>'sanitation_bath',
                "전체미용"     =>'all',
                "스포팅"      =>'spoting',
                "가위컷"      =>'scissors',
                "썸머컷"       =>'summercut'
            );
            $option_name = array('bath_price','part_price','bath_part_price','sanitation_price','sanitation_bath_price','all_price','spoting_price','scissors_price','summercut_price');
            $option_chk_name = array('is_consult_bath','is_consult_part','is_consult_bath_part','is_consult_sanitation','is_consult_sanitation_bath','is_consult_all',
                'is_consult_spoting','is_consult_scissors','is_consult_summercut','is_consult_beauty1','is_consult_beauty2','is_consult_beauty3','is_consult_beauty4','is_consult_beauty5');
            $cnt = 10;
            $beauty = 1;
            $que = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '{$_SESSION['gobeauty_user_id']}'";
            //echo $que;
            $res = mysqli_query($connection, $que);
            $row = mysqli_fetch_assoc($res);
            for($i=1;$i<=14;$i++){
                if(!empty($row['worktime'.$i.'_disp_yn'] == 'y')){
                    if($i<10){
                        array_push($dog_product_title, $dog_product_arr[$i]);
                    } else {
                        $dog_product_seq[$row['worktime'.$i.'_title']] = 'beauty'.$beauty;
                        $option_name[] = 'beauty'.$beauty."_price";
                        if($row['worktime'.$i.'_title']!='') {
                            array_push($dog_product_title, $row['worktime' . $i . '_title']);
                            $beauty++;
                        }
                    }
                }
            }
            $service_arr = $dog_product_title;


            foreach($service_arr AS $key => $value){
                $data["list"][] = $value;
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else if($r_type == "artist"){
            $sql = "
					SELECT count(*) as cnt, name
					FROM tb_artist_list
					WHERE artist_id = '".$_SESSION['gobeauty_user_id']."' AND is_out = 2
					group by name
				";
            $result = mysqli_query($connection,$sql);
            while($row = mysqli_fetch_array($result)){
                $data["list"][] = $row["name"];
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else if($r_type == "payment"){
            $type_arr = array(
                "0" => "앱-선결제",
                "1" => "앱-매장결제",
                "2" => "매장접수"
            );

            foreach($type_arr AS $key => $value){
                $data["list"][$key] = $value;
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else if($r_type == "hotel"){
            $sql = "
					SELECT count(*) as cnt, hp.room_name, h.pet_type, hp.room_pet_type
					FROM tb_hotel_product as hp
						INNER JOIN tb_hotel as h on hp.h_seq = h.h_seq
					WHERE hp.artist_id = '".$_SESSION['gobeauty_user_id']."'
						AND hp.is_delete = '2'
					group by hp.room_name
				";
            $result = mysqli_query($connection,$sql);
            while($row = mysqli_fetch_array($result)){
                if($row["room_pet_type"] == $row["pet_type"]){
                    $data["list"][] = $row["room_name"];
                }else{
                    if($row["pet_type"] == "both"){
                        $data["list"][] = $row["room_name"];
                    }
                }
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else if($r_type == "playroom"){
            $type_arr = array(
                "1" => "종일권",
                "2" => "몇시간만"
            );

            foreach($type_arr AS $key => $value){
                $data["list"][$key] = $value;
            }

            $return_data = array("code" => "000000", "data" => $data);
        }else{
            $return_data = array("code" => "000001", "data" => "잘못된 파라메터를 호출했습니다.");
        }
    }else{
        $return_data = array("code" => "999997", "data" => "잘못된 함수를 호출했습니다.");
    }
}else{
    $return_data = array("code" => "999998", "data" => "잘못된 접근입니다.");
}

echo json_encode($return_data);
?>