<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
/*print_r($_SESSION);
print_r($_POST);*/

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
$phone = $_GET['cellphone'];


//유저등록
if($_POST['id_use_yn']=='n' && empty($_POST['pet_seq'])) {
    $que = "INSERT INTO tb_tmp_user SET ";
    $que .= "cellphone = '{$_POST['cellphone']}' ";
//echo $que;
    $res = sql_query($que);
    $tmp_seq = mysqli_insert_id($connection);

    $dermatosis     = ($_POST['dermatosis']=='')?0:$_POST['dermatosis'];
    $heart_trouble  = ($_POST['heart_trouble']=='')?0:$_POST['heart_trouble'];
    $marking        = ($_POST['marking']=='')?0:$_POST['marking'];
    $mounting       = ($_POST['mounting']=='')?0:$_POST['mounting'];


    //펫 기본 정보 업데이트
    $sql  = "INSERT INTO tb_mypet SET ";
    $sql .= "tmp_seq        = '{$tmp_seq}', ";
    $sql .= "customer_id    = '{$data["customer"]["id"]}', ";
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
    $sql .= "tmp_seq        = '{$tmp_seq}', ";
    $sql .= "customer_id    = '{$data["customer"]["id"]}', ";
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

    $product .= $_POST['hair_type'] . '|';
    $w4 = explode(":", $_POST['hair_type']);
    $total_price += $w4[1];


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

} else {
    /*echo "고양이";
    print_r($_POST);*/

    //미용 무게 추가가 선택되었을때
    if(isset($_POST['cat_weight'])){
        $product .= $_POST['cat_weight'].':'.$_POST['hair_kg'].'|';
    } else {
        $product .= 'all:0|';
    }
    //미용 단모/장모
    $product .= $_POST['hair'].'|';
    //발톱
    if(!empty($_POST['cat_toenail'])){
        $product .= $_POST['cat_toenail'].'|';
    } else {
        $product .= '|';
    }
    //단모/장모 목욕
    $bath = explode(":",$_POST['cat_bath']);
    if($bath[0]=='단모'){
        $product .= $bath[1].'|';
    } else if($bath[0]=='장모'){
        $product .= $bath[1].'|';
    } else {
        $product .= '|';
    }
    //추가 서비스
    $etc_cnt = count($_POST['cat_etc']);
    $product .= $etc_cnt.'|';
    for($i=0;$i<count($_POST['cat_etc']);$i++){
        $product .= $_POST['cat_etc'][$i].'|';
    }
}


$product .= '0|'; //제품구매수
$product .= '0|'; //쿠폰구매수






$que = "INSERT INTO tb_payment_log SET ";
$que .= "pet_seq            = '{$pet_seq}', ";
$que .= "session_id         = '" . session_id() . "', ";
$que .= "customer_id        = '{$_POST['customer_id']}', ";
$que .= "artist_id          = '{$_SESSION['gobeauty_user_id']}', ";
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

$to_hour = date('H', $_POST['to_time']);
$to_min = date('i', $_POST['to_time']);
$que .= "to_hour            = '{$to_hour}', ";
$que .= "to_minute          = '{$to_min}', ";
$que .= "product_type       = 'B', ";
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



?>
<script>location.href = '../<?php echo $_SESSION['backurl1'];?>';</script>
