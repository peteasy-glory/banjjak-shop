<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include ($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");
/*print_r($_SESSION);
print_r($_POST);*/

if($_SESSION['artist_flag'] === true){
    $shop_id = $_SESSION['shop_user_id'];
    $user_name = $_SESSION['shop_user_nickname'];
}else{
    $shop_id = $_SESSION['gobeauty_user_id'];
    $user_name = $_SESSION['gobeauty_user_nickname'];
}

$total_price = 0;

$plus_que = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '{$shop_id}' ";
$plus_res = sql_query($plus_que);
$plus_row = sql_fetch($plus_res);

$option_name = array('bath_price'=>'목욕',
    'part_price'=>'부분미용',
    'bath_part_price'=>'부분+목욕',
    'sanitation_price'=>'위생',
    'sanitation_bath_price'=>'위생+목욕',
    'all_price'=>'전체미용',
    'spoting_price'=>'스포팅',
    'scissors_price'=>'가위컷',
    'summercut_price'=>'썸머컷',
    'beauty1_price' =>$plus_row["worktime10_title"],
    'beauty2_price' =>$plus_row["worktime11_title"],
    'beauty3_price' =>$plus_row["worktime12_title"],
    'beauty4_price' =>$plus_row["worktime13_title"],
    'beauty5_price' =>$plus_row["worktime14_title"]
);

$product = '';
$phone = $_GET['cellphone'];
$customer_id = $_POST['customer_id'];

if($_SESSION['reserve_go'] == 1) {
    unset($_SESSION['reserve_go']); // 예약 프로세스 시작하면 세션 삭제(중복예약 방지)

//유저등록
if($_POST['id_use_yn']=='n' && empty($_POST['pet_seq'])) {
    // 정회원 여부
        if($_POST['customer_id']!=''){
            $customer_id = $_POST['customer_id'];
        }else{
            $select_c_q = "SELECT * FROM tb_customer WHERE cellphone = '".$_POST['cellphone']."' and nickname not like 'cellp_%' ORDER BY last_login_time DESC LIMIT 1";
            $select_c_r = mysqli_query($connection, $select_c_q);
            $select_c_cnt = mysqli_num_rows($select_c_r);
            //echo $select_c_q."<br>";

            // 가회원 여부
            $select_q = "SELECT * FROM tb_tmp_user WHERE cellphone = '".$_POST['cellphone']."'";
            $select_r = mysqli_query($connection, $select_q);
            $select_cnt = mysqli_num_rows($select_r);
            //echo $select_q."<br>";
            if($select_c_cnt>0){
                $select_c_data['data'] = mysqli_fetch_assoc($select_c_r);
                $customer_id = $select_c_data['data']['id'];
                //echo $customer_id."<br>";
            }else if($select_cnt>0){
                $select_data['data'] = mysqli_fetch_assoc($select_r);
                $tmp_seq = $select_data['data']['tmp_seq'];
                //echo $tmp_seq."<br>";
            }else{
                $que = "INSERT INTO tb_tmp_user SET ";
                $que .= "cellphone = '{$_POST['cellphone']}' ";
                //echo $que;
                $res = sql_query($que);
                $tmp_seq = mysqli_insert_id($connection);
            }
        }

    $dermatosis     = ($_POST['dermatosis']=='')?0:$_POST['dermatosis'];
    $heart_trouble  = ($_POST['heart_trouble']=='')?0:$_POST['heart_trouble'];
    $marking        = ($_POST['marking']=='')?0:$_POST['marking'];
    $mounting       = ($_POST['mounting']=='')?0:$_POST['mounting'];

    $pet_type = ($_POST['pet_kind'] == 'cat')? $_POST['pet_type_cat'] : $_POST['pet_type'];
    $neutral = ($_POST['neutral'] != '')? "neutral = '{$_POST['neutral']}', " : "";

    //펫 기본 정보 업데이트
    $sql  = "INSERT INTO tb_mypet SET ";
    $sql .= "tmp_seq        = nullif('{$tmp_seq}',''), ";
    $sql .= "customer_id    = nullif('{$customer_id}',''), ";
    $sql .= "type           = '{$_POST['pet_kind']}', ";
    $sql .= "pet_type       = '{$pet_type}', ";
    $sql .= "name           = '{$_POST['pet_name']}', ";
    $sql .= "year           = '{$_POST['pet_year']}', ";
    $sql .= "month          = '{$_POST['pet_month']}', ";
    $sql .= "day            = '{$_POST['pet_day']}', ";
    $sql .= "gender         = '{$_POST['pet_gender_m']}', ";
    $sql .= $neutral;
    $sql .= "weight         = '{$_POST['pet_weight1']}.{$_POST['pet_weight2']}', ";
    $sql .= "beauty_exp     = '{$_POST['beauty_cnt']}', ";
    $sql .= "vaccination    = '{$_POST['vaccination']}', ";
    $sql .= "luxation       = '{$_POST['luxation']}', ";
    $sql .= "bite           = '{$_POST['bite']}', ";
    $sql .= "dermatosis     = '{$dermatosis}', ";
    $sql .= "heart_trouble  = '{$heart_trouble}', ";
    $sql .= "marking        = '{$marking}', ";
    $sql .= "mounting       = '{$mounting}' ";

//echo $sql."<br>";
    $res = sql_query($sql);
    $pet_seq = mysqli_insert_id($connection);
} else {
    // 회원 데이터 호출(정회원)
    $crypto = new Crypto();
    $pet_seq = $_POST['pet_seq'];
    $cellphone_encode = $crypto->encode($phone, $access_key, $secret_key);
    $sql = "
		SELECT *
		FROM tb_customer
		WHERE cellphone = '" . $cellphone_encode . "' 
			and nickname not like 'cellp_%'
	";	// 20210705 by migo - cellp 제외 조건
//    echo $sql."<p>";

    $result = mysqli_query($connection, $sql);
    $member_cnt = mysqli_num_rows($result);
    if ($member_cnt == 0) { // (가회원)
        $sql = "
			SELECT *
			FROM tb_tmp_user
			WHERE cellphone = '" . $phone . "'
		";
        $result = mysqli_query($connection, $sql);
        $data["customer"] = mysqli_fetch_assoc($result);
        $data["customer"]["tmp_yn"] = "Y";
        $where_qy  = " AND mp.tmp_yn = '" . $data["customer"]["tmp_yn"] . "' ";
        $where_qy .= " AND mp.tmp_seq = '" . $data["customer"]["tmp_seq"] . "' ";
    } else {
        $data["customer"] = mysqli_fetch_assoc($result);
        $data["customer"]["tmp_yn"] = "N";
        $where_qy  = " AND mp.tmp_yn = '" . $data["customer"]["tmp_yn"] . "' ";
        $where_qy .= " AND mp.customer_id = '" . $data["customer"]["id"] . "' ";
        $data["customer"]["cellphone"] = $phone;
    }

    $tmp_seq = $data["customer"]["tmp_seq"];


    $dermatosis     = ($_POST['dermatosis']=='')?0:$_POST['dermatosis'];
    $heart_trouble  = ($_POST['heart_trouble']=='')?0:$_POST['heart_trouble'];
    $marking        = ($_POST['marking']=='')?0:$_POST['marking'];
    $mounting       = ($_POST['mounting']=='')?0:$_POST['mounting'];


    //펫 기본 정보 업데이트
    $sql  = "UPDATE tb_mypet SET ";
    $sql .= "tmp_seq        = nullif('{$tmp_seq}',''), ";
    $sql .= "customer_id    = nullif('{$data["customer"]["id"]}',''), ";
    $sql .= "type           = '{$_POST['pet_kind']}', ";
    $sql .= "pet_type       = '{$_POST['pet_type']}', ";
    $sql .= "name           = '{$_POST['pet_name']}', ";
    $sql .= "year           = '{$_POST['pet_year']}', ";
    $sql .= "month          = '{$_POST['pet_month']}', ";
    $sql .= "day            = '{$_POST['pet_day']}', ";
    $sql .= "gender         = '{$_POST['pet_gender_m']}', ";
    $sql .= "weight         = '{$_POST['pet_weight1']}.{$_POST['pet_weight2']}', ";
    $sql .= "beauty_exp     = '{$_POST['beauty_cnt']}', ";
    $sql .= "vaccination    = '{$_POST['vaccination']}', ";
    $sql .= "luxation       = '{$_POST['luxation']}', ";
    $sql .= "bite           = '{$_POST['bite']}', ";
    $sql .= "dermatosis     = '{$dermatosis}', ";
    $sql .= "heart_trouble  = '{$heart_trouble}', ";
    $sql .= "marking        = '{$marking}', ";
    $sql .= "mounting       = '{$mounting}' ";
    $sql .= " WHERE pet_seq = '{$pet_seq} ";
    $res = sql_query($sql);
}
$product .= $_POST['pet_name'] . '|';
$product .= ($_POST['pet_kind'] == 'dog') ? '개|' : '고양이|';
$product .= $_POST['shopName'] . '|';


if($_POST['pet_kind']=='dog') {

    $product .= $_POST['size'] . '|';
    $product .= $option_name[$_POST['service']] . '|';
    $product .= $_POST['weight'] . '|';
    $w1 = explode(":", $_POST['weight']);
    $total_price += $w1[1];

    $product .= $_POST['face'] . '|';
    $w2 = explode(":", $_POST['face']);
    $total_price += $w2[1];

    $product .= $_POST['hair_length'] . '|';
    $w3 = explode(":", $_POST['hair_length']);
    $total_price += $w3[1];

    //$product .= $_POST['hair_type'] . '|';
    //$w4 = explode(":", $_POST['hair_type']);
    //$total_price += $w4[1];

    $hair_type_cnt = count($_POST['hair_type']);
        if ($hair_type_cnt == 0) {
            $product .= '|';
        } else {

            for ($i = 0; $i < count($_POST['hair_type']); $i++) {
                $hair_type_price = explode(':', $_POST['hair_type'][$i]);
                $total_price += $hair_type_price[1];
                $product .= $_POST['hair_type'][$i] . ',';
            }
            $product = substr($product , 0, -1).'|';
        }

    $leg_other_cnt = count($_POST['leg']);
    $is_leg1 = $is_leg2 = $is_leg3 = false;
    for ($i = 0; $i < count($_POST['leg']); $i++) {
        $tmp = explode(":", $_POST['leg'][$i]);
        if ($tmp[0] == '발톱') {
            $reg1 = $tmp[1];
            $total_price += $reg1;
            $is_leg1 = true;
            $leg_other_cnt--;
        } else if ($tmp[0] == '장화') {
            $reg2 = $tmp[1] . '|';
            $total_price += $reg2;
            $is_leg2 = true;
            $leg_other_cnt--;
        } else if ($tmp[0] == '방울') {
            $reg3 = $tmp[1] . '|';
            $total_price += $reg3;
            $is_leg3 = true;
            $leg_other_cnt--;
        } else {
            $leg['title'][] = $tmp[0];
            $leg['price'][] = $tmp[1];
            $total_price += $tmp[1];
        }
    }
    if ($is_leg1 == false) {
        $product .= '|';
    } else {
        $product .= $reg1 . '|';
    }
    if ($is_leg2 == false) {
        $product .= '|';
    } else {
        $product .= $reg2;
        if ($is_leg3 == false) {
            $product .= '|';
        }
    }
    if ($is_leg3 == false) {
        if ($is_leg2 == false) {
            $product .= '|';
        }
    } else {
        $product .= $reg3;
    }

    $product .= '|';
    $product .= '|';
//다리 기타
    if ($leg_other_cnt == 0) {
        $product .= '0|';
    } else {
        $product .= $leg_other_cnt . '|';
        for ($i = 0; $i < count($leg['title']); $i++) {
            if (!empty($leg['title'][$i])) {
                $product .= $leg['title'][$i] . ':' . $leg['price'][$i] . '|';
            }
        }
    }

    $spa_total_cnt = count($_POST['spa']);
    if ($spa_total_cnt == 0) {
        $product .= '0|';
    } else {
        $product .= $spa_total_cnt . '|';
        for ($i = 0; $i < count($_POST['spa']); $i++) {
            $spa_price = explode(':', $_POST['spa'][$i]);
            $total_price += $spa_price[1];
            $product .= $_POST['spa'][$i] . '|';
        }
    }

    $color_total_cnt = count($_POST['color']);
    if ($color_total_cnt == 0) {
        $product .= '0|';
    } else {
        $product .= $color_total_cnt . '|';
        for ($i = 0; $i < count($_POST['color']); $i++) {
            $color_price = explode(':', $_POST['color'][$i]);
            $total_price += $color_price[1];
            $product .= $_POST['color'][$i] . '|';
        }
    }

    $etc_total_cnt = count($_POST['etc']);
    if ($etc_total_cnt == 0) {
        $product .= '0|';
    } else {
        $product .= $etc_total_cnt . '|';
        for ($i = 0; $i < count($_POST['etc']); $i++) {
            $color_price = explode(':', $_POST['etc'][$i]);
            $total_price += $color_price[1];
            $product .= $_POST['etc'][$i] . '|';
        }
    }

} else {
    /*echo "고양이";
    print_r($_POST);*/

    // 무게별 금액 구하기
    if(!empty($_POST['cat_weight'])){
        $product .= '미용|'.$_POST['cat_weight'].':0|';

    } else {
        $product .= '|all:0|';
    }
    if(!empty($_POST['hair_kg'])){
        $product .= $_POST['hair_kg'].'|';
        $total_price += explode(':',$_POST['hair_kg'])[1];
    } else {
        $product .= '|';
    }

    if(!empty($_POST['cat_toenail'])){
        $product .= $_POST['cat_toenail'].'|';
        $total_price += $_POST['cat_toenail'];
    }else{
        $product .= '|';
    }

    //단모목욕
    $bath = explode(":",$_POST['cat_bath']);
    if($bath[0]=='단모'){
        $product .= $bath[1].'|';
    } else {
        $product .= '|';
    }
    if($bath[0]=='장모'){
        $product .= $bath[1].'|';
    } else {
        $product .= '|';
    }
    $total_price += $bath[1];

    $etc_cnt = count($_POST['cat_etc']);
    $product .= $etc_cnt.'|';
    for($i=0;$i<count($_POST['cat_etc']);$i++){
        $product .= $_POST['cat_etc'][$i].'|';
        $total_price += explode(':',$_POST['cat_etc'][$i])[1];
    }
}


    $product .= '0|'; //제품구매수
    $product .= '0|'; //쿠폰구매수


$chk_sql = "
            SELECT * FROM tb_payment_log WHERE artist_id = '{$shop_id}' AND worker = '{$_POST['worker']}'
            AND YEAR = '{$_POST['year']}' AND MONTH = '{$_POST['month']}' AND DAY = '{$_POST['day']}' AND HOUR = '".date('H', $_POST['from_time'])."' AND MINUTE = '".date('i', $_POST['from_time'])."' and is_cancel = 0
        ";
$chk_result = mysqli_query($connection,$chk_sql);
$chk_cnt = mysqli_num_rows($chk_result);
if($chk_cnt < 1) {

    $pay_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);

    $que = "INSERT INTO tb_payment_log SET ";
    $que .= "pet_seq            = '{$pet_seq}', ";
    $que .= "session_id         = '" . session_id() . "', ";
    $que .= "customer_id        = '{$customer_id}', ";
    $que .= "artist_id          = '{$shop_id}', ";
    $que .= "order_id           = '" . str2hex($_POST['customer_id'] . "_" . rand_id()) . "', ";
    $que .= "local_price        = '{$total_price}', ";
    $que .= "cellphone          = '{$_POST['cellphone']}', ";
    $que .= "worker             = '{$_POST['worker']}', ";
    $que .= "year               = '{$_POST['year']}', ";
    $que .= "month              = '{$_POST['month']}', ";
    $que .= "day                = '{$_POST['day']}', ";
    $que .= "pay_type           = 'pos-card', ";
    $from_hour = date('H', $_POST['from_time']);
    $from_min = date('i', $_POST['from_time']);
    $que .= "hour               = '{$from_hour}', ";
    $que .= "minute             = '{$from_min}', ";

    $que .= "pay_data           = '{$pay_data}',";

    $to_hour = date('H', $_POST['to_time']);
    $to_min = date('i', $_POST['to_time']);
    $que .= "to_hour            = '{$to_hour}', ";
    $que .= "to_minute          = '{$to_min}', ";
    $que .= "product_type       = 'B', ";
    $que .= "approval           = '1', ";
    if ($_POST['coupon_use_yn'] == 'y') {
        $que .= "is_coupon          = 'Y', ";
    }
    $sql = "SELECT is_vat FROM tb_shop WHERE customer_id = '{$shop_id}'";
    $r = sql_query($sql);
    $rw = sql_fetch($r);
    $que .= "is_vat             = '{$rw['is_vat']}', ";
    $que .= "product            = '{$product}', ";
    $que .= "reserve_yn         = '{$_POST['alarm_yn']}', ";
    $que .= "a_day_ago_yn       = '{$_POST['befor_day_alarm_yn']}', ";
    $que .= "buy_time           = NOW(), ";
    $que .= "is_reserve_pay           = {$_POST['is_reserve_pay']}, ";
    $que .= "reserve_pay_price           = {$_POST['deposit_input']}, ";
    $que .= "reserve_pay_deadline           = '{$_POST['reserve_deposit_time_']}' ";
    //echo $que . "<br>";
    $product_res = sql_query($que);
    $id = mysqli_insert_id($connection);

    // 알림톡발송
    $now = time();
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    //$reservationTime = strtotime($_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'].' '.$from_hour.':'.$from_min);
    $reservationTime = strtotime("$year-$month-$day $from_hour:$from_min");

    if ($reservationTime > $now && $_POST['alarm_yn'] == "Y"  && $_POST['is_reserve_pay'] == '0') {

        $talk = new Allimtalk();

        $talk->cellphone = $_POST['cellphone'];

        $week_arr = ['일', '월', '화', '수', '목', '금', '토'];
        $talkCustomerName = substr($_POST['cellphone'], -4);
        //$talkDate = date("Y년 m월 d일 H시 i분", $reservationTime);
        $talkDate = date("Y년 m월 d일", $reservationTime);
        $talkDate .= "(".$week_arr[date("w", $reservationTime)].") ";
        $talkDate .= date("H시 i분", $reservationTime);
        $talkBtnLink = "https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$id;
        $talkResult = $talk->sendReservationNotice_new($talkCustomerName, $_POST['pet_name'], $_POST['shopName'], $talkDate, $talkBtnLink);
    }

    if($reservationTime > $now  && $_POST['deposit_check'] == 'Y'){

        $talk2 = new Allimtalk();

        $talk2->cellphone = $_POST['cellphone'];



        $week_arr = ['일', '월', '화', '수', '목', '금', '토'];
        $talkDate = date("Y년 m월 d일", $reservationTime);
        $talkDate .= "(".$week_arr[date("w", $reservationTime)].") ";
        $talkDate .= date("H시 i분", $reservationTime);

        $date = $_POST['reserve_deposit_time_'];
        $year = substr($date,0,4);
        $month = substr($date,5,2);
        $date_ = substr($date,8,2);
        $hour = substr($date,11,2);
        $min = substr($date,14,2);
        $_date = $year.'년 '.$month.'월 '.$date_.'일 '.$hour.'시 '.$min.'분';


        $pay = $_POST['deposit_input'].'원';


        $talkBtnLink = "";
        $talkResult = $talk2->sendReservePay($_POST['pet_name'],$_POST['shopName'],$pay,$talkDate,$_POST['bank_name'],$_POST['bank_account'],$_date);

    }
}
}
?>
<script>location.href = '../<?php echo $_SESSION['backurl1'];?>';</script>
