<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
/*print_r($_SESSION);
print_r($_POST);*/
$user_id = $_SESSION['gobeauty_user_id'];

$total_price = 0;
$clear = array();
if(ctype_digit($_POST['seq'])){
    $clear['seq'] = $_POST['seq'];
}
$que = "SELECT * FROM tb_payment_log WHERE payment_log_seq = '{$clear['seq']}'";
echo $que;
$res = sql_query($que);
$row = sql_fetch($res);
$rs = explode("|",$row['product']);
$is_vat = $row['is_vat'];

$option_name = array('bath_price'=>'목욕',
    'part_price'=>'부분미용',
    'bath_part_price'=>'부분+목욕',
    'sanitation_price'=>'위생',
    'sanitation_bath_price'=>'위생+목욕',
    'all_price'=>'전체미용',
    'spoting_price'=>'스포팅',
    'scissors_price'=>'가위컷',
    'summercut_price'=>'썸머컷');

$product  = '';

$que = "SELECT * FROM tb_mypet WHERE pet_seq = {$row['pet_seq']}";
echo $que;
$pet = sql_fetch_array($que);

$que = "SELECT * FROM tb_shop WHERE customer_id = '{$user_id}'";
echo $que;
$shop = sql_fetch_array($que);
print_r($pet);
if($pet[0]['type']=='dog') {
    $product .= $pet[0]['name'] . '|';
    $product .= '개|';
    $product .= $shop[0]['name'] . '|';

//서비스 업데이트를 위해서 다시 만든다.
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

    /*
    $product .= $_POST['hair_type'] . '|';
    $w4 = explode(":", $_POST['hair_type']);
    $total_price += $w4[1];
    */
    // 털특징별 중복선택 가능
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


//    print_r($_POST);


} else {
    /*echo "고양이";
    print_r($_POST);*/

    $product .= $pet[0]['name'] . '|';
    $product .= '고양이|';
    $product .= $shop[0]['name'] . '|';

    //미용 무게 추가가 선택되었을때
    if(isset($_POST['cat_weight'])){
        $product .= $_POST['cat_weight'].':'.$_POST['hair_kg'].'|';
    } else {
        $product .= 'all:0|';
    }
    //미용 단모/장모
    $product .= $_POST['hair'].'|';
    $total_price += explode(':',$_POST['hair'])[1];
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
    $total_price += $bath[1];
    //추가 서비스
    $etc_cnt = count($_POST['cat_etc']);
    $product .= $etc_cnt.'|';
    for($i=0;$i<count($_POST['cat_etc']);$i++){
        $product .= $_POST['cat_etc'][$i].'|';
        $total_price += explode(':',$_POST['cat_etc'][$i])[1];
    }

}

    //쿠폰 내용을 만든다.
    $cp_cnt = count($_POST['cp']);

    $product .= $cp_cnt.'|';

    for($b=0;$b<count($_POST['cp']);$b++) {

        //해당 쿠폰의 정보를 구한다.
        $sql2 = "SELECT * FROM tb_coupon WHERE coupon_seq = '{$_POST['cp'][$b]}'";
        $tbc = sql_fetch_array($sql2);

        if(!$_POST['tmp_seq'])  $_POST['tmp_seq'] = NULL;

        $sql = "SELECT COUNT(*) AS cnt, user_coupon_seq, given  FROM tb_user_coupon WHERE customer_id = '{$_POST['customer_id']}' AND tmp_seq = '{$_POST['tmp_seq']}' AND artist_id = '{$_POST['artist_id']}' AND coupon_seq = '{$_POST['cp'][$b]}' AND type = '{$tbc[0]['type']}'";
        //echo $sql."<br>";
        $row = sql_fetch_array($sql);
    //        print_r($row);
        if ($row[0]['cnt'] > 0) {
            $sql1 = "UPDATE tb_user_coupon SET ";
            $sql1 .= "given             = '".($tbc[0]['given']+$row[0]['given'])."', ";
            $sql1 .= "update_date       = NOW() ";
            $sql1 .= " WHERE customer_id = '{$_POST['customer_id']}' AND tmp_seq = '{$_POST['tmp_seq']}' AND artist_id = '{$_POST['artist_id']}' AND coupon_seq = '{$_POST['cp'][$b]}' AND type = '{$tbc[0]['type']}' ";
        } else {
            $sql1  = "INSERT INTO tb_user_coupon SET ";
            $sql1 .= "customer_id       = '{$_POST['customer_id']}', ";
            $sql1 .= "artist_id         = '{$_POST['artist_id']}', ";
            $sql1 .= "tmp_seq           = '{$_POST['tmp_seq']}', ";
            $sql1 .= "payment_log_seq   = '{$_POST['seq']}', ";
            $sql1 .= "coupon_seq        = '{$_POST['cp'][$b]}', ";
            $sql1 .= "type              = '{$tbc[0]['type']}', ";
            $sql1 .= "price             = '{$tbc[0]['price']}', ";
            $sql1 .= "given             = '{$tbc[0]['given']}', ";
            $sql1 .= "reg_date          = NOW() ";

        }
        //echo $sql1."<p>";
        $result = sql_query($sql1);
        $id = mysqli_insert_id($connection);

        if($result) {
            $sql3 = "INSERT INTO tb_coupon_history SET ";
            $sql3 .= "coupon_seq            = '{$_POST['cp'][$b]}', ";
            $sql3 .= "user_coupon_seq       = '{$id}', ";
            $sql3 .= "payment_log_seq       = '{$_POST['seq']}', ";
            $sql3 .= "amount                = '{$tbc[0]['given']}', ";
            $sql3 .= "balance               = '{$tbc[0]['given']}', ";
            $sql3 .= "customer_id           = '{$_POST['customer_id']}', ";
            $sql3 .= "tmp_seq               = '{$_POST['tmp_seq']}', ";
            $sql3 .= "artist_id             = '{$_POST['artist_id']}', ";
            $sql3 .= "memo                  = '쿠폰구매', ";
            $sql3 .= "type                  = 'N' ";
            //echo $sql3 . "<br>";
            sql_query($sql3);

            $product .= $_POST['cp'][$b] . ':';
            $product .= $tbc[0]['name'] . ':';
            $product .= $tbc[0]['price'] . '|';
            //echo $coupon_data."<br>";

            //$total_price +=$tbc[0]['price'];
        }
    }


//제품구매
$goods_cnt = 0;
for ($i = 0; $i < count($_POST['gd']); $i++) {
    if ($_POST['gd_chkbox'][$i] == 'y') {
        $goods_cnt++;
    }
}
for ($i = 0; $i < count($_POST['snack']); $i++) {
    if ($_POST['snack_chkbox'][$i] == 'y') {
        $goods_cnt++;
    }
}
for ($i = 0; $i < count($_POST['feed']); $i++) {
    if ($_POST['feed_chkbox'][$i] == 'y') {
        $goods_cnt++;
    }
}
for ($i = 0; $i < count($_POST['etc_cnt']); $i++) {
    if ($_POST['et_chkbox'][$i] == 'y') {
        $goods_cnt++;
    }
}

$product .= $goods_cnt . '|';
if ($goods_cnt > 0) {
    for ($i = 0; $i < count($_POST['gd']); $i++) {
        if ($_POST['gd_chkbox'][$i] == 'y') {
            $product .= $_POST['gd_seq'][$i] . ':';
            $product .= $_POST['gd_name'][$i] . ':';
            $product .= $_POST['gd_price'][$i] . ':';
            $product .= $_POST['gd'][$i] . '|';
            $total_price += ($_POST['gd_price'][$i] * $_POST['gd'][$i]);
        }

    }

    for ($i = 0; $i < count($_POST['snack']); $i++) {
        if ($_POST['snack_chkbox'][$i] == 'y') {
            $product .= $_POST['snack_seq'][$i] . ':';
            $product .= $_POST['snack_name'][$i] . ':';
            $product .= $_POST['snack_price'][$i] . ':';
            $product .= $_POST['snack'][$i] . '|';
            $total_price += ($_POST['snack_price'][$i] * $_POST['snack'][$i]);
        }
    }


    for ($i = 0; $i < count($_POST['feed']); $i++) {
        if ($_POST['feed_chkbox'][$i] == 'y') {
            $product .= $_POST['feed_seq'][$i] . ':';
            $product .= $_POST['feed_name'][$i] . ':';
            $product .= $_POST['feed_price'][$i] . ':';
            $product .= $_POST['feed'][$i] . '|';
            $total_price += ($_POST['feed_price'][$i] * $_POST['feed'][$i]);
        }
    }


    for ($i = 0; $i < count($_POST['etc_cnt']); $i++) {
        if ($_POST['et_chkbox'][$i] == 'y') {
            $product .= $_POST['etc_seq'][$i] . ':';
            $product .= $_POST['etc_name'][$i] . ':';
            $product .= $_POST['etc_price'][$i] . ':';
            $product .= $_POST['etc_cnt'][$i] . '|';
            $total_price += ($_POST['etc_price'][$i] * $_POST['etc_cnt'][$i]);
        }
    }
}

    //echo $product."<p>";

    // 최종가격 업데이트(현금결제로 하더라도 일단 카드로 바꾼 후 가격 적용)
    if($is_vat == '1'){
        $total_price = $total_price*1.1;
    }

    $que = "UPDATE tb_payment_log SET ";
    $que .= "local_price        = '{$total_price}', ";
    $que .= "local_price_cash        = '0', ";
    if ($_POST['coupon_use_yn'] == 'y') {
        $que .= "is_coupon          = 'Y', ";
    }
    $que .= "product            = '{$product}',";
    $que .= "update_time        = NOW() ";
    $que .= " WHERE payment_log_seq = {$clear['seq']}";
    echo $que . "<br>";
    $product_res = sql_query($que);
    /*$id = mysqli_insert_id($connection);*/
    $_SESSION['anchor'] = 'Y';
?>

<script>parent.callback();</script>
<!--<script>location.href='../reserve_pay_management_2?payment_log_seq=<?php /*echo $clear['seq'];*/?>#service_tab';</script>-->
