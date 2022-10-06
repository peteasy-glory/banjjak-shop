<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include ($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");
//print_r($_SESSION);
//print_r($_POST);

if($_SESSION['artist_flag'] === true){
    $user_id = $_SESSION['shop_user_id'];
}else{
    $user_id = $_SESSION['gobeauty_user_id'];
}

$total_price = 0;

$plus_que = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '{$user_id}' ";
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

if($_SESSION['reserve_go'] == 1){
    unset($_SESSION['reserve_go']); // 예약 프로세스 시작하면 세션 삭제(중복예약 방지)

//강아지 예약접숙

if($_POST['pet_kind']=='dog') {
    $product .= $_POST['pet_name'] . '|';
    $product .= ($_POST['pet_kind'] == 'dog') ? '개|' : '고양이|';
    $product .= $_POST['shopName'] . '|';
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

    //print_r($_POST);
    //펫 기본 정보 업데이트
    $sql = "UPDATE tb_mypet SET ";
    $sql .= "type       = '{$_POST['pet_kind']}', ";
    $sql .= "pet_type   = '{$_POST['pet_type']}', ";
    $sql .= "year       = '{$_POST['pet_year']}', ";
    $sql .= "month      = '{$_POST['pet_month']}', ";
    $sql .= "day        = '{$_POST['pet_day']}', ";
    $sql .= "gender     = '{$_POST['pet_gender_m']}', ";
    $sql .= "weight     = '{$_POST['pet_weight1']}.{$_POST['pet_weight2']}' ";
    $sql .= " WHERE pet_seq = '{$_POST['pet_no']}' ";
    //echo $sql."<br>";
    $res = sql_query($sql);
    if ($res) {

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
        $product .= '0|'; //제품구매수
        $product .= '0|'; //쿠폰구매수
    }
} else {

    $product .= $_POST['pet_name'].'|';
    $product .= '고양이|';
    $product .= $_POST['shopName'] . '|';

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

    $product .= '0|'; //제품구매수
    $product .= '0|'; //쿠폰구매수


//print_r($_POST);
//펫 기본 정보 업데이트
    $sql  = "UPDATE tb_mypet SET ";
    $sql .= "type       = '{$_POST['pet_kind']}', ";
    $sql .= "pet_type   = '{$_POST['pet_type']}', ";
    $sql .= "year       = '{$_POST['pet_year']}', ";
    $sql .= "month      = '{$_POST['pet_month']}', ";
    $sql .= "day        = '{$_POST['pet_day']}', ";
    $sql .= "gender     = '{$_POST['pet_gender_m']}', ";
    $sql .= "weight     = '{$_POST['pet_weight1']}.{$_POST['pet_weight2']}' ";
    $sql .= " WHERE pet_seq = '{$_POST['pet_no']}' ";
    //echo $sql."<br>";
    $res = sql_query($sql);

}
$chk_sql = "
        SELECT * FROM tb_payment_log WHERE artist_id = '{$user_id}' AND worker = '{$_POST['worker']}'
        AND YEAR = '{$_POST['year']}' AND MONTH = '{$_POST['month']}' AND DAY = '{$_POST['day']}' AND HOUR = '".date('H', $_POST['from_time'])."' AND MINUTE = '".date('i', $_POST['from_time'])."' and is_cancel = 0
    ";
$chk_result = mysqli_query($connection,$chk_sql);
$chk_cnt = mysqli_num_rows($chk_result);
if($chk_cnt < 1) {
    $pay_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);

    $que = "INSERT INTO tb_payment_log SET ";
    $que .= "pet_seq            = '{$_POST['pet_no']}', ";
    $que .= "session_id         = '" . session_id() . "', ";
    $que .= "customer_id        = '{$_POST['customer_id']}', ";
    $que .= "artist_id          = '{$user_id}', ";
    $que .= "order_id           = '" . str2hex($_POST['customer_id'] . "_" . rand_id()) . "', ";
    $que .= "local_price        = '{$total_price}', ";
    $que .= "worker             = '{$_POST['worker']}', ";
    $que .= "year               = '{$_POST['year']}', ";
    $que .= "month              = '{$_POST['month']}', ";
    $que .= "day                = '{$_POST['day']}', ";
    $que .= "status             = 'POS',";
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
    $que .= "cellphone          = '{$_POST['cellPhone']}', ";
    $que .= "approval           = '1', ";
    if ($_POST['coupon_use_yn'] == 'y') {
        $que .= "is_coupon          = 'Y', ";
    }
    $sql = "SELECT is_vat FROM tb_shop WHERE customer_id = '{$user_id}'";
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

    $que = "UPDATE tb_coupon_history SET payment_log_seq = {$id} WHERE history_seq = '{$_POST['coupon_seq']}' ";
    sql_query($que);

    // 알림톡발송
    $now = time();
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    //$reservationTime = strtotime($_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'].' '.$from_hour.':'.$from_min);
    $reservationTime = strtotime("$year-$month-$day $from_hour:$from_min");



    if ($reservationTime > $now && $_POST['alarm_yn'] == "Y" && $_POST['is_reserve_pay'] == '0') {
        $talk = new Allimtalk();

        $talk->cellphone = $_POST['cellPhone'];

        $week_arr = ['일', '월', '화', '수', '목', '금', '토'];
        $talkCustomerName = substr($_POST['cellPhone'], -4);
        //$talkDate = date("Y년 m월 d일 H시 i분", $reservationTime);
        $talkDate = date("Y년 m월 d일", $reservationTime);
        $talkDate .= "(".$week_arr[date("w", $reservationTime)].") ";
        $talkDate .= date("H시 i분", $reservationTime);
        $talkBtnLink = "https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$id;
        $talkResult = $talk->sendReservationNotice_new($talkCustomerName, $_POST['pet_name'], $_POST['shopName'], $talkDate, $talkBtnLink);
    }else if($reservationTime > $now  && $_POST['deposit_check'] == 'Y'){

        $talk2 = new Allimtalk();

        $talk2->cellphone = $_POST['cellPhone'];



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

/*펫이름|
펫 종류(개,고양이)|
샵이름|
크기(소형견미용,중형견미용 등)|
미용상품(부분미용, 목욕, 위생 등)|
무게:추가요금(2:6000 => ~2kg이면 6000원)|
얼굴컷:추가요금|
미용털길이:추가요금|
털특징:추가요금(이중모:5000)|
발톱의가격(3000)|
장화의가격(4000)|
방울의가격(5000)|
미확인|
미확인|
다리추가서비스 중 선택할 갯수(기존 발톱, 장화, 방울을 선택했다면 0, 샵에 운동화컷, 구두컷 두개 상품이 추가되어있을때 하나를 선택하면 1, 두개 다 선택하면 2, 선택한 것 만큼 바로 뒤에 추가로 운동화컷:1000 추가)|
스파선택상품개수|
스파1:1000|
염색선택개수|
브릿지:3000|
기타선택개수|
기타1:3000|
제품구매수|
용품시퀀스:제품명:가격:개수(해당 카테고리에서 더 구매했다면 바로 뒤에 추가)|
간식시퀀스:제품명:가격:개수|
사료시퀀스:제품명:가격:개수|
기타제품시퀀스:제품명:가격:개수|
쿠폰구매개수|
쿠폰시퀀스:쿠폰명:가격*/
?>
<script>
    location.href = '../<?php echo $_SESSION['backurl1'];?>';
</script>
