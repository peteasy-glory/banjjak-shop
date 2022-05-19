<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
//print_r($_SESSION);
//print_r($_POST);

$total_price = 0;

$option_name = array('bath_price'=>'목욕',
    'part_price'=>'부분미용',
    'bath_part_price'=>'부분+목욕',
    'sanitation_price'=>'위생',
    'sanitation_bath_price'=>'위생+목욕',
    'all_price'=>'전체미용',
    'spoting_price'=>'스포팅',
    'scissors_price'=>'가위컷',
    'summercut_price'=>'썸머컷');

$product = '';

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

    $product .= $_POST['hair_type'] . '|';
    $w4 = explode(":", $_POST['hair_type']);
    $total_price += $w4[1];


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
    $sql .= " WHERE pet_seq = '{$_POST['송']}' ";
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
        if ($spa_total_cnt == 0) {
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
    if(!empty($_POST['cat_weight'])){
        $product .= $_POST['cat_weight'].':'.$_POST['hair_kg'].'|';
    } else {
        $product .= 'all:0|';
    }
    if(!empty($_POST['cat_toenail'])){
        $product .= $_POST['cat_toenail'].'|';
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

    $etc_cnt = count($_POST['cat_etc']);
    $product .= $etc_cnt.'|';
    for($i=0;$i<count($_POST['cat_etc']);$i++){
        $product .= $_POST['cat_etc'][$i].'|';
    }


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
    $que = "INSERT INTO tb_payment_log SET ";
    $que .= "pet_seq            = '{$_POST['pet_no']}', ";
    $que .= "session_id         = '" . session_id() . "', ";
    $que .= "customer_id        = '{$_POST['customer_id']}', ";
    $que .= "artist_id          = '{$_SESSION['gobeauty_user_id']}', ";
    $que .= "order_id           = '" . str2hex($_POST['customer_id'] . "_" . rand_id()) . "', ";
    $que .= "local_price        = '{$total_price}', ";
    $que .= "worker             = '{$_POST['worker']}', ";
    $que .= "year               = '{$_POST['year']}', ";
    $que .= "month              = '{$_POST['month']}', ";
    $que .= "day                = '{$_POST['day']}', ";
    $que .= "pay_type           = 'pos-card', ";
    $from_hour = date('H', $_POST['from_time']);
    $from_min = date('i', $_POST['from_time']);
    $que .= "hour               = '{$from_hour}', ";
    $que .= "minute             = '{$from_min}', ";

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
    $sql = "SELECT is_vat FROM tb_shop WHERE customer_id = '{$_SESSION['gobeauty_user_id']}'";
    $r = sql_query($sql);
    $rw = sql_fetch($r);
    $que .= "is_vat             = '{$rw['is_vat']}', ";
    $que .= "product            = '{$product}', ";
    $que .= "reserve_yn         = '{$_POST['alarm_yn']}', ";
    $que .= "a_day_ago_yn       = '{$_POST['befor_day_alarm_yn']}', ";
    $que .= "buy_time           = NOW() ";
    //echo $que . "<br>";
    $product_res = sql_query($que);
    $id = mysqli_insert_id($connection);

    $que = "UPDATE tb_coupon_history SET payment_log_seq = {$id} WHERE history_seq = '{$_POST['coupon_seq']}' ";
    sql_query($que);


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
<script>location.href = '../<?php echo $_SESSION['backurl1'];?>';</script>
