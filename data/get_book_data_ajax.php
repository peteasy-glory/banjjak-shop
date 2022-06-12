<?php
ini_set('display_errors',1);
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();

//$user_id = $_SESSION['gobeauty_user_id'];

if($_SESSION['artist_flag'] === true){
    $user_id = $_SESSION['shop_user_id'];
}else{
    $user_id = $_SESSION['gobeauty_user_id'];
}

$clear = array();
if(ctype_alnum($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

//print_r($_POST);

switch($clear['mode']){
    //품종별 크기선택
    case 'getTypeSize':
        $html = '<div class="grid-layout-cell"><label class="form-toggle-box middle" for="size1"><input type="radio" name="size" id="size1" class="pet-size" value="" checked ><em>선택안함</em></label></div>';
        $json['flag'] = true;
        $json['data'] = '';
        if($_POST['type']=='dog') {
            $que = "SELECT *,
                        if(second_type = '소형견미용', 1, 
                            if(second_type = '중형견미용', 2, 
                                if(second_type = '대형견미용', 3, 
                                    if(second_type = '특수견미용', 4,
                                        if(second_type = '기타공통', 5, 9)
                                )
                            )
                        )
                    ) AS sort 
                    FROM tb_product_dog_static 
                    WHERE customer_id = '{$user_id}' #AND first_type = '개' AND (second_type IN ('소형견미용','중형견미용','대형견미용','특수견미용') OR direct_title != '') AND second_type != '기타공통'  ORDER BY sort ASC, update_time
                    AND second_type != '기타공통'
                    ORDER BY sort ASC, update_time";

            //echo $que;
            $arr = sql_fetch_array($que);
            if (count($arr) > 0) {
                foreach ($arr as $rs) {
                    $secondType = ($rs['second_type']!='직접입력')?$rs['second_type']:$rs['direct_title'];
                    $html .= '<div class="grid-layout-cell"><label class="form-toggle-box middle" for="size2">';
                    $html .= '        <input type="radio" name="size" id="size2" class="pet-size" value="'.$secondType.'" data-firstType="dog" data-secondType="'.$secondType.'">';
                    $html .= '        <em>'.$secondType.'</em>';
                    $html .= '</label>';
                    $html .= '</div>';
                }
                $json['data'] = $html;
            }
        } else {
            $html .= '<div class="grid-layout-cell"><label class="form-toggle-box middle" for="size2">';
            $html .= '        <input type="radio" name="size" id="size2" class="pet-size" value="단모" data-firstType="고양이" data-secondType="단모">';
            $html .= '        <em>단모</em>';
            $html .= '</label>';
            $html .= '</div>';
            $html .= '<div class="grid-layout-cell"><label class="form-toggle-box middle" for="size2">';
            $html .= '        <input type="radio" name="size" id="size2" class="pet-size" value="장모" data-firstType="고양이" data-secondType="장모">';
            $html .= '        <em>장모</em>';
            $html .= '</label>';
            $html .= '</div>';
            $json['data'] = $html;
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    break;
    //서비스 선택
    case 'getBeautyServiceData':
        $json['flag'] = true;
        $json['data'] = '';
        $html = '<div class="grid-layout-cell"><label class="form-toggle-box middle" for="service1"><input type="radio" name="service" class="service-type" id="service1" checked><em>선택안함</em></label></div>';
        $json['flag'] = true;
        $que_pay = "SELECT * FROM tb_payment_log WHERE payment_log_seq = {$_POST['payment_log']}";
        $res_pay = sql_query($que_pay);
        $pay = sql_fetch($res_pay);
        //print_r($_POST);
        //개일경우
        if($_POST['petType']=='dog') {
            $dog_product_title = array();

            $option_name = array('bath_price', 'part_price', 'bath_part_price', 'sanitation_price', 'sanitation_bath_price', 'all_price', 'spoting_price', 'scissors_price', 'summercut_price');
            $productWorkTimeTitle = array("목욕", "부분미용", "부분+목욕", "위생", "위생+목욕","전체미용", "스포팅", "가위컷", "썸머컷");
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
                "목욕" => 'bath',
                "부분미용" => 'part',
                "부분+목욕" => 'bath_part',
                "위생" => 'sanitation',
                "위생+목욕" => 'sanitation_bath',
                "전체미용" => 'all',
                "스포팅" => 'spoting',
                "가위컷" => 'scissors',
                "썸머컷" => 'summercut'
            );
            $beauty = 1;

            $que = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '{$user_id}' ";
            //echo $que;
            $res = mysqli_query($connection, $que);
            $rows = mysqli_fetch_assoc($res);
            for ($i = 0; $i <= 14; $i++) {
                if ($rows['worktime' . $i . '_disp_yn'] == 'y') {
                    if ($i < 10) {
                        array_push($dog_product_title, $dog_product_arr[$i]);
                    } else {
                        if (!empty($rows['worktime' . $i . '_title'])) {
                            $dog_product_seq[$rows['worktime' . $i . '_title']] = 'beauty' . $beauty;
                            $option_name[] = 'beauty' . $beauty . "_price";
                            array_push($dog_product_title, $rows['worktime' . $i . '_title']);
                            $beauty++;
                        }
                    }
                }
                if($i<9) {

                    if ($rows['worktime' . ($i + 1) . '_disp_yn'] == 'y' && !empty($rows['worktime' . ($i + 1)]) && !empty($productWorkTimeTitle[$i])) {
                        $worktime['title'][] = $productWorkTimeTitle[$i];
                        $worktime['time'][] = $rows['worktime' . ($i + 1)];
                    }
                } else if($i>8){
                    if ($rows['worktime' . ($i + 1) . '_disp_yn'] == 'y' && !empty($rows['worktime' . ($i + 1).'_title']) ) {
                        $worktime['title'][] = $rows['worktime' . ($i + 1).'_title'];
                        $worktime['time'][] = $rows['worktime' . ($i + 1)];
                    }
                }
            }
            if($_POST['petType'] == 'dog')  $petType = '개';
            if($_POST['petType'] == 'cat')  $petType = '고양이';
            $sql = '';
            for($i=0;$i<count($worktime['title']);$i++){
                $que_main = "SELECT * FROM tb_product_dog_static 
                    WHERE customer_id = '{$user_id}' AND first_type = '{$petType}' AND (second_type = '{$_POST['serviceType']}' OR direct_title = '{$_POST['serviceType']}')
                    AND ".$dog_product_seq[$worktime['title'][$i]]."_price != ''
                ";
                $sql .= $que_main;

                //echo $que;
                $res_main = sql_query($que_main);
                $row_main = sql_fetch($res_main);
                if(count($row_main)>0){
                    if($_POST['start_time'] == ''){
                        $time_ = strtotime($pay['year'].'-'.$pay['month'].'-'.$pay['day'].' '.$pay['hour'].':'.$pay['minute'].' +'.$worktime['time'][$i].' minute');
                    }else{
                        $time_ = strtotime($_POST['start_time'].' +'.$worktime['time'][$i].' minute');
                    }

                    $html .= '<div class="grid-layout-cell">';
                    $html .= '    <label class="form-toggle-box middle" for="service2">';
                    $html .= '        <input type="radio" name="service" class="service-type" data-time="'.$time_.'" data-time="'.$worktime['time'][$i].'" value="'.$dog_product_seq[$worktime['title'][$i]].'_price">';
                    $html .= '        <em>'.$worktime['title'][$i]."<br>".$worktime['time'][$i].'분</em>';
                    $html .= '    </label>';
                    $html .= '</div>';

                }
            }
            $json['data'] = $html;
            $json['sql'] = $sql;

        } else {

        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
    case 'getBeautyKgsData'://강아지 미용 무게 금액 가져오기
        $json['flag'] = true;
        $json['data'] = '';
        $html = '<div class="grid-layout-cell"><label class="form-toggle-box form-toggle-price middle" for="weight1"><input type="radio" name="weight" id="weight1" value="" class="weight" checked><em><span>선택안함</span></em></label></div>';
        $json['flag'] = true;
        //print_r($_POST);
        //개일경우
        if($_POST['petType']=='dog') { 
            $dog_product_title = array();

            $option_name = array('bath_price', 'part_price', 'bath_part_price', 'sanitation_price', 'sanitation_bath_price', 'all_price', 'spoting_price', 'scissors_price', 'summercut_price');
            $productWorkTimeTitle = array("목욕", "부분미용", "부분+목욕", "위생", "위생+목욕","전체미용", "스포팅", "가위컷", "썸머컷");
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
                "목욕" => 'bath',
                "부분미용" => 'part',
                "부분+목욕" => 'bath_part',
                "위생" => 'sanitation',
                "위생+목욕" => 'sanitation_bath',
                "전체미용" => 'all',
                "스포팅" => 'spoting',
                "가위컷" => 'scissors',
                "썸머컷" => 'summercut'
            );
            $beauty = 1;

            $que = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '{$user_id}' ";
            //echo $que;
            $res = mysqli_query($connection, $que);
            $row = mysqli_fetch_assoc($res);
            for ($i = 1; $i <= 14; $i++) {
                if (!empty($row['worktime' . $i . '_disp_yn'] == 'y')) {
                    if ($i < 10) {
                        array_push($dog_product_title, $dog_product_arr[$i]);
                    } else {
                        if (!empty($row['worktime' . $i . '_title'])) {
                            $dog_product_seq[$row['worktime' . $i . '_title']] = 'beauty' . $beauty;
                            $option_name[] = 'beauty' . $beauty . "_price";
                            array_push($dog_product_title, $row['worktime' . $i . '_title']);
                            $beauty++;
                        }
                    }
                }
            }

            //print_r($option_name);
            if($_POST['petType'] == 'dog')  $petType = '개';
            if($_POST['petType'] == 'cat')  $petType = '고양이';
            $que = "SELECT * FROM tb_product_dog_static 
                                                WHERE customer_id = '{$user_id}' AND first_type = '{$petType}' AND (second_type = '{$_POST['serviceType']}' OR direct_title = '{$_POST['serviceType']}') ";

            //echo $que;
            $res = sql_query($que);
            $row = sql_fetch($res);
            //무게

            $tmp = explode("_",$_POST['service']);
            for($j=0;$j<count($option_name);$j++){
                if($_POST['service'] == $option_name[$j]){
                    $sv[] = explode(",",$row[$_POST['service']]);
                    $cl[] = explode(",",$row['is_consult_'.$tmp[0]]);
                }
            }


            $kgs = explode(",",$row['kgs']);
            $plus_kgs_price = 0;
            for($i=0;$i<count($kgs);$i++){
                $pr1 = ($cl[0][$i]==1)?'상담':$sv[0][$i];
                $pr = ($cl[0][$i]==1)?'상담':number_format($sv[0][$i]).'원';
                if($kgs[$i] != '' && $pr1 != ''){
                    $html .= '<div class="grid-layout-cell"><label class="form-toggle-box form-toggle-price middle" for="weight2">';
                    $html .= '        <input type="radio" name="weight" id="weight2" class="weight" value="'.$kgs[$i].':'.$pr1.'">';
                    $html .= '        <em><span>~'.$kgs[$i].'kg</span><strong>'.$pr.'</strong></em>';
                    $html .= '</label>';
                    $html .= '</div>';

                    $plus_kgs_price = $pr1;
                }
            }
            if($row['what_over_kgs']!='' && $row['over_kgs_price'] > 0){


                $html .= '<div class="grid-layout-cell">';
                $html .= '    <div class="form-toggle-options">';
                $html .= '        <input type="radio" name="weight" id="weight2" class="weight_plus" value="'.$row['what_over_kgs'].':'.($plus_kgs_price+$row['over_kgs_price']).'">';
                $html .= '        <div class="form-toggle-options-data">';
                $html .= '            <div class="options-labels"><span>'.$row['what_over_kgs'].'kg~</span><strong>kg당<br>+'.number_format($row['over_kgs_price']).'원</strong></div>';
                $html .= '            <div class="form-amount-input">';
                $html .= '                <button type="button" class="btn-form-amount-minus" onclick="weight_change('.$row['what_over_kgs'].','.$row['over_kgs_price'].',\'m\');">감소</button>';
                $html .= '                <div class="form-amount-info">';
                $html .= '                    <input type="number" id="weight'.$row['what_over_kgs'].'_cnt" value="'.$row['what_over_kgs'].'" class="form-amount-val">';
                $html .= '                </div>';
                $html .= '                <button type="button" class="btn-form-amount-plus" onclick="weight_change('.$row['what_over_kgs'].','.$row['over_kgs_price'].',\'p\');">증가</button>';
                $html .= '            </div>';
                $html .= '        </div>';
                $html .= '    </div>';
                $html .= '</div>';

            }
            $json['data'] = $html;
        } else {
            
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
    case 'catDel'://고양이 미용 삭제
        $json['flag'] = true;
        $data = explode('|',$_POST['type']);
        $que = "DELETE FROM tb_product_cat WHERE customer_id = '{$user_id}' AND first_type = '{$data[0]}' AND second_type = '{$data[1]}'";
        //echo $que."<p>";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            exit;
        }
        echo json_encode($json);
        break;
    //쿠폰
    case 'getCoupon':
        $select1 = '<option value="">쿠폰선택</option>';
        $json['flag'] = true;
        $json['select_name'] = '';
        $que = "SELECT * FROM (SELECT * FROM tb_coupon_history WHERE customer_id = '{$_POST['cid']}' AND artist_id = '{$user_id}' AND balance > 0 AND cancel_yn = 'N' ORDER BY history_seq DESC LIMIT 18446744073709551615) AS ch LEFT JOIN tb_coupon b ON ch.coupon_seq = b.coupon_seq GROUP BY ch.coupon_seq";
        //echo $que;
        $arr = sql_fetch_array($que);
        if(count($arr)>0){
            foreach($arr as $rs){
                $select1 .= '<option value="'.$rs['history_seq'].'">' . $emoji->emojiDBToStr($rs['name']) . '</option>';
            }
        }
        $json['select_name'] = $select1;
        $json['cnt'] = count($arr);
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
    //쿠폰 balance
    case 'getCouponBalance':
        $select1 = $select2 = '';
        $json['flag'] = true;
        $json['select_name'] = '';
        $json['select_use'] = '';
        //$que = "SELECT * FROM tb_coupon_history a LEFT JOIN tb_coupon b ON a.coupon_seq = b.coupon_seq WHERE history_seq = {$_POST['cid']}";
        //$que = "SELECT * FROM tb_coupon_history a LEFT JOIN tb_coupon b ON a.coupon_seq = b.coupon_seq WHERE a.user_coupon_seq = {$_POST['cid']} ORDER BY a.history_seq DESC LIMIT 1";
        if($_POST['cid'] != ''){
            $que = "SELECT * FROM tb_coupon_history a LEFT JOIN tb_coupon b ON a.coupon_seq = b.coupon_seq WHERE a.user_coupon_seq = {$_POST['cid']} ORDER BY a.history_seq DESC LIMIT 1";
        }else{
            $que = "SELECT * FROM tb_coupon_history a LEFT JOIN tb_coupon b ON a.coupon_seq = b.coupon_seq WHERE a.payment_log_seq = {$_POST['payment_log']} ORDER BY a.history_seq DESC LIMIT 1";
        }
        //echo $que;
        $res = sql_query($que);
        $row = sql_fetch($res);
        if($row['history_seq']){
            //echo $row['balance'];
            if($row['type'] == 'C') {
                for ($i = 0; $i <= 20; $i++) {
                    $selected = '';
                    if ($i == $row['balance']) $selected = 'selected';
                    $select1 .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                }
                for($i=0;$i<=$row['balance'];$i++){
                    $select2 .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                }
            } else {
                for ($i = 0; $i <= 1500000; $i+=1000) {
                    $selected = '';
                    if ($i == $row['balance']) $selected = 'selected';
                    $select1 .= '<option value="' . $i . '" ' . $selected . '>' . number_format($i) . '</option>';
                }
                for($i=0;$i<=$row['balance'];$i+=1000){
                    $select2 .= '<option value="' . $i . '" ' . $selected . '>' . number_format($i) . '</option>';
                }
            }
        }
        $json['select_name'] = $select1;
        $json['select_use'] = $select2;
        $json['user_coupon_seq'] = $row['user_coupon_seq'];
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;


    //쿠폰사용
    case 'useCoupon':
        $json['flag'] = true;
//        print_r($_POST);
        $que = "SELECT * FROM tb_coupon_history WHERE user_coupon_seq = {$_POST['cid']} ORDER BY history_seq DESC LIMIT 1";
        $res = sql_query($que);
        $row = sql_fetch($res);

        $balance = $row['balance'] - $_POST['cnt'];
        $que  = "INSERT INTO tb_coupon_history SET ";
        $que .= "coupon_seq         = '{$row['coupon_seq']}', ";
        $que .= "user_coupon_seq    = '{$row['user_coupon_seq']}', ";
        $que .= "payment_log_seq    = '{$_POST['payment_log']}', ";
        $que .= "amount             = '-{$_POST['cnt']}', ";
        $que .= "balance            = '{$balance}', ";
        $que .= "customer_id        = '{$row['customer_id']}', ";
        $que .= "tmp_seq            = '{$row['tmp_seq']}', ";
        $que .= "artist_id          = '{$row['artist_id']}', ";
        $que .= "memo               = '매장 접수', ";
        $que .= "type               = 'U' ";
        $res = sql_query($que);
        $cid = mysqli_insert_id($connection);
        if(!$res){
            $json['flag'] = false;
        }

        //쿠폰내역 업데이트
        $que = "UPDATE tb_coupon SET price = price - {$_POST['cnt']}, update_date = NOW() WHERE coupon_seq = {$row['coupon_seq']} ";
        sql_query($que);

        $json['cid'] = $cid;
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
        
    //할인금액 저장
    case 'saveDiscount':
        $json['flag'] = true;
        $que = "UPDATE tb_payment_log SET discount_num = {$_POST['num']}, discount_type = '{$_POST['type']}' WHERE payment_log_seq = {$_POST['seq']}";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    break;

    //적립금 사용
    case 'useReserve':
        sql_query('BEGIN');
        $json['flag'] = true;
        $json['error'] = '';
        $que  = "INSERT INTO tb_user_reserve_log SET ";
        $que .= "customer_id        = '{$_POST['cid']}', ";
        $que .= "artist_id          = '{$_POST['aid']}', ";
        $que .= "tmp_seq            = '{$_POST['tid']}', ";
        $que .= "cellphone          = '{$_POST['phone']}', ";
        $que .= "payment_log_seq    = '{$_POST['seq']}', ";
        $que .= "service_type       = '{$_POST['type']}', ";
        $que .= "type               = 'U', ";
        $que .= "memo               = '적립금사용', ";
        $que .= "use_reserve        = '{$_POST['price']}', ";
        $que .= "reg_dt             = NOW() ";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[99]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }
        $que = "UPDATE tb_user_reserve SET reserve = reserve - {$_POST['price']} WHERE customer_id = '{$_POST['cid']}' AND artist_id = '{$_POST['aid']}' AND tmp_seq = '{$_POST['tid']}' AND cellphone = '{$_POST['phone']}'";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[90]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        $que = "UPDATE tb_payment_log SET reserve_point = '{$_POST['price']}' WHERE payment_log_seq = '{$_POST['seq']}'";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[100]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        $que = "SELECT * FROM tb_user_reserve  WHERE customer_id = '{$_POST['cid']}' AND artist_id = '{$_POST['aid']}' AND tmp_seq = '{$_POST['tid']}' AND cellphone = '{$_POST['phone']}' ";
        $res = sql_query($que);
        $row = sql_fetch($res);
        $json['cur_price'] = $row['reserve'];
        $json['price'] = $_POST['price'];
        sql_query('COMMIT');


        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    //적립금 취소
    case 'useReserveCancel':
        sql_query('BEGIN');
        $json['flag'] = true;
        $json['error'] = '';

        $que  = "INSERT INTO tb_user_reserve_log SET ";
        $que .= "customer_id        = '{$_POST['cid']}', ";
        $que .= "artist_id          = '{$_POST['aid']}', ";
        $que .= "tmp_seq            = '{$_POST['tid']}', ";
        $que .= "cellphone          = '{$_POST['phone']}', ";
        $que .= "payment_log_seq    = '{$_POST['seq']}', ";
        $que .= "service_type       = '{$_POST['type']}', ";
        $que .= "type               = 'R', ";
        $que .= "memo               = '적립금 사용 재설정', ";
        $que .= "use_reserve        = '{$_POST['price']}', ";
        $que .= "reg_dt             = NOW() ";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[99]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }
        $que = "UPDATE tb_user_reserve SET reserve = reserve + {$_POST['price']} WHERE customer_id = '{$_POST['cid']}' AND artist_id = '{$_POST['aid']}' AND tmp_seq = '{$_POST['tid']}' AND cellphone = '{$_POST['phone']}'";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[90]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        $que = "UPDATE tb_payment_log SET reserve_point = '' WHERE payment_log_seq = '{$_POST['seq']}'";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '적립금 입력시 오류가 발생했습니다.[100]';
            sql_query('ROLLBACK');
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        $que = "SELECT * FROM tb_user_reserve  WHERE customer_id = '{$_POST['cid']}' AND artist_id = '{$_POST['aid']}' AND tmp_seq = '{$_POST['tid']}' AND cellphone = '{$_POST['phone']}' ";
        $res = sql_query($que);
        $row = sql_fetch($res);
        $json['cur_price'] = $row['reserve'];
        $json['price'] = $_POST['price'];
        sql_query('COMMIT');


        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    //결제내용저장하기
    case 'savePayResult':
        $json['flag'] = true;
        $json['error'] = '최종결제액이 적용되었습니다. 최종 결제액을 확인해주세요.';
        $card = str_replace(",","",$_POST['card']);
        $cash = str_replace(",","",$_POST['cash']);
        $total = str_replace(",","",$_POST['total']);
        if(!$card){
            $card = 0;
        }

        if(!$cash){
            $cash = 0;
        }
        $que = "UPDATE tb_payment_log SET ";
        $que .= "local_price          = '{$card}', ";
        $que .= "local_price_cash     = '{$cash}', ";
        $que .= "update_time        = NOW() ";
        $que .= " WHERE payment_log_seq = '{$_POST['seq']} '";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '최종결제 금액 저장시 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

    //결제완료처리
    case 'savePayConfirm':
        $json['flag'] = true;
        if($_POST['con']==1) {
            $json['error'] = '결제완료처리가 되었습니다.';
        } else {
            $json['error'] = '결제완료처리가 취소 되었습니다.';
        }
        $card = str_replace(",","",$_POST['card']);
        $cash = str_replace(",","",$_POST['cash']);
        if(!$card){
            $card = 0;
        }

        if(!$cash){
            $cash = 0;
        }
        $que = "UPDATE tb_payment_log SET ";
        $que .= "is_confirm         = '{$_POST['con']}', ";
        $que .= "confirm_dt         = NOW() ";
        $que .= " WHERE payment_log_seq = '{$_POST['seq']}' ";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '결제완료 처리시 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        $que = "SELECT * FROM tb_payment_log WHERE payment_log_seq = '{$_POST['seq']}' ";
        $row = sql_fetch_array($que);
        if($_POST['con']==1) {
            $json['time'] = $row[0]['confirm_dt'];
        } else {
            $json['time'] = '';
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;


    //예약변경하기
    case 'changeRev':
        if(!isset($_SESSION['payment_seq'])){

        } else {
            $sql = "SELECT * FROM tb_payment_log WHERE payment_log_seq = '{$_SESSION['payment_seq']}'";
            $res = sql_query($sql);
            $row = sql_fetch($res);
            $seq = $_SESSION['payment_seq'];

            $start_time = $_POST['date']." ".$row['hour'].':'.$row['minute'];
            $end_time = $_POST['date']." ".$row['to_hour'].':'.$row['to_minute'];
            $time = strtotime($end_time) - strtotime($start_time);

            //시작 일자
            $dt = explode("-",$_POST['date']);
            //시작 시간
            $st = explode(":",$_POST['time']);

            $tmp = explode(":",date('H:i',strtotime($_POST['date'].' '.$_POST['time'].' +120 minute')));


            $sql = "SELECT * FROM tb_artist_list WHERE name = '{$_POST['worker']}' AND artist_id = '{$user_id}' ORDER BY seq ASC LIMIT 1";
            //echo $sql;
            $al = sql_fetch_array($sql);


            $que  = "UPDATE tb_payment_log SET ";
            $que .= "year           = '{$dt[0]}', ";
            $que .= "month          = '".intval($dt[1])."', ";
            $que .= "day            = '".intval($dt[2])."', ";
            $que .= "hour           = '".intval($st[0])."', ";
            $que .= "minute         = '".intval($st[1])."', ";
            $que .= "to_hour        = '".intval($tmp[0])."', ";
            $que .= "to_minute      = '".intval($tmp[1])."', ";
            $que .= "worker         = '{$al[0]['name']}',";
            $que .= "update_time    = NOW() ";
            $que .= " WHERE payment_log_seq = '{$seq}'";
            //echo $que;
            $res = sql_query($que);

            // 알림톡발송
            if($_POST['msg_send'] == "y"){
                $shop_array = explode("|",$row['product']);
                $shopName = $shop_array[2];

                $year               = $dt[0];
                $month              = intval($dt[1]);
                $day                = intval($dt[2]);
                $hour               = intval($st[0]);
                $minute             = intval($st[1]);
                $reservationTime    = strtotime("$year-$month-$day $hour:$minute");
                //$customerName = $shop_data[0]['usr_name'];
                $customerName = substr($row['cellphone'], -4);
                $petName = $shop_array[0];
                //echo $shopName = $shop_data[0]['name'];
                $date = date('Y년 m월 d일 H시 i분',strtotime($start_time));
                $changeDate = date('Y년 m월 d일 H시 i분',strtotime($year.'-'.$month.'-'.$day.' '.$hour.':'.$minute));

                $talk = new Allimtalk();
                //$talkBtnLink = "https://shop.gopet.kr/reserve_pay_management_2?payment_log_seq=".$pay[0]['payment_log_seq'];
                $talk->cellphone = $row['cellphone'];
                $talkBtnLink = "https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$seq;
                $talkResult = $talk->sendUpdateNotice_new($customerName, $petName, $shopName, $date, $changeDate, $talkBtnLink);
            }

            if($res){
                unset($_SESSION['payment_seq']);
                echo '<script>location.href="../reserve_main_week?ch=week&yy='.$row['year'].'&mm='.$row['month'].'&&dd='.$row['day'].'&worker='.$al[0]['name'].'";</script>';
            }
        }

        break;
    //즉시예약하기
    case 'directRev':
        if(!isset($_SESSION['direct_pay_seq'])){

        } else {
            //print_r($_POST);
            $sql = "SELECT * FROM tb_payment_log WHERE payment_log_seq = '{$_SESSION['direct_pay_seq']}'";
            //echo $sql;
            $res = sql_query($sql);
            $row = sql_fetch($res);
            $seq = $_SESSION['direct_pay_seq'];

            $start_time = $_POST['d_date']." ".$row['hour'].':'.$row['minute'];
            $end_time = $_POST['date']." ".$row['to_hour'].':'.$row['to_minute'];
            $time = strtotime($end_time) - strtotime($start_time);

            //시작 일자
            $dt = explode("-",$_POST['date']);
            //시작 시간
            $st = explode(":",$_POST['time']);
            $tmp = explode(":",date('H:i',strtotime($_POST['date'].' '.$_POST['time'].' +120 minute')));


            $sql = "SELECT * FROM tb_artist_list WHERE name = '{$_POST['worker']}' AND artist_id = '{$user_id}' ORDER BY seq ASC LIMIT 1";
            //echo $sql;
            $al = sql_fetch_array($sql);

            $pay_data = json_encode($_POST, JSON_UNESCAPED_UNICODE);

            $que  = "UPDATE tb_payment_log SET ";
            $que .= "year           = '{$dt[0]}', ";
            $que .= "month          = '".intval($dt[1])."', ";
            $que .= "day            = '".intval($dt[2])."', ";
            $que .= "hour           = '".intval($st[0])."', ";
            $que .= "minute         = '".intval($st[1])."', ";
            $que .= "to_hour        = '".intval($tmp[0])."', ";
            $que .= "to_minute      = '".intval($tmp[1])."', ";
            $que .= "reserve_yn     = '{$_POST['msg_send']}',";
            $que .= "a_day_ago_yn   = '{$_POST['msg_send1']}',";
            $que .= "worker         = '{$al[0]['name']}',";
            $que .= "status         = 'POS',";
            $que .= "pay_type       = 'pos-card',";
            $que .= "pay_data       = '{$pay_data}',";
            $que .= "update_time    = NOW() ";
            $que .= " WHERE payment_log_seq = '{$seq}'";
            //echo $que;
            $res = sql_query($que);

            // 알림톡발송
            if($_POST['msg_send'] == "Y"){
                $shop_array = explode("|",$row['product']);
                $shopName = $shop_array[2];

                $year               = $dt[0];
                $month              = intval($dt[1]);
                $day                = intval($dt[2]);
                $hour               = intval($st[0]);
                $minute             = intval($st[1]);
                //$customerName = $shop_data[0]['usr_name'];
                $customerName = substr($row['cellphone'], -4);
                $petName = $shop_array[0];
                //echo $shopName = $shop_data[0]['name'];
                $date = date('Y년 m월 d일 H시 i분',strtotime($year.'-'.$month.'-'.$day.' '.$hour.':'.$minute));

                $talk = new Allimtalk();
                //$talkBtnLink = "https://shop.gopet.kr/reserve_pay_management_2?payment_log_seq=".$pay[0]['payment_log_seq'];
                $talk->cellphone = $row['cellphone'];
                $talkBtnLink = "https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$seq;
                $talkResult = $talk->sendReservationNotice_new($customerName, $petName, $shopName, $date, $talkBtnLink);
            }

            if($res){
                unset($_SESSION['direct_pay_seq']);
                unset($_SESSION['pet_name']);
                echo '<script>location.href="../reserve_main_week?ch=week&yy='.$row['year'].'&mm='.$row['month'].'&&dd='.$row['day'].'&worker='.$al[0]['name'].'";</script>';
            }
        }

        break;

    //펫정보수정
    case 'petModify':
        $year = (isset($_POST['pet_year']))? $_POST['pet_year'] : 0;
        $month = (isset($_POST['pet_month']))? $_POST['pet_month'] : 0;
        $day = (isset($_POST['pet_day']))? $_POST['pet_day'] : 0;
        $neutral = (isset($_POST['neutral']))? $_POST['neutral'] : 0;
        $dermatosis = (isset($_POST['dermatosis']))? $_POST['dermatosis'] : 0;
        $heart_trouble = (isset($_POST['heart_trouble']))? $_POST['heart_trouble'] : 0;
        $marking = (isset($_POST['marking']))? $_POST['marking'] : 0;
        $mounting = (isset($_POST['mounting']))? $_POST['mounting'] : 0;
        $weight = $_POST['pet_weight1'].".".$_POST['pet_weight2'];

        $que  = "UPDATE tb_mypet SET ";
        $que .= "name               = '{$_POST['pet_name']}', ";
        $que .= "type               = '{$_POST['type']}', ";
        if($_POST['type'] == 'cat'){
            $que .= "pet_type           = '{$_POST['pet_type_cat']}', ";
        } else {
            $que .= "pet_type           = '{$_POST['pet_type']}', ";
        }
        
        $que .= "year               = '{$year}', ";
        $que .= "month              = '{$month}', ";
        $que .= "day                = '{$day}', ";
        $que .= "gender             = '{$_POST['pet_gender_m']}', ";
        $que .= "neutral            = '{$neutral}', ";
        $que .= "weight             = '{$weight}', ";
        $que .= "vaccination        = '{$_POST['vaccination']}', ";
        $que .= "beauty_exp         = '{$_POST['beauty_cnt']}', ";
        $que .= "bite               = '{$_POST['bite']}', ";
        $que .= "luxation           = '{$_POST['luxation']}', ";
        $que .= "dermatosis         = '{$dermatosis}', ";
        $que .= "heart_trouble      = '{$heart_trouble}', ";
        $que .= "marking            = '{$marking}', ";
        $que .= "mounting           = '{$mounting}', ";
        $que .= "etc                = '{$_POST['memo']}' ";
        $que .= " WHERE pet_seq = '{$_POST['pet_seq']}' ";
        //echo $que;
        sql_query($que);
        //$url = "../".$_POST['backurl'].'?payment_log_seq='.$_POST['payment_seq'];
        if($_POST['mode_1'] == "view"){
            $url = "../".$_POST['backurl'].'?customer_cellphone='.$_POST['cellphone']."&pet_seq=".$_POST['pet_seq'];
        }else {
            $url = "../" . $_POST['backurl'] . '?payment_log_seq=' . $_POST['payment_seq'];
        }
        echo '<script>location.href="'.$url.'"</script>';
        break;
        
    //신규고객 연락처 검색하기
    case 'chkPhone':
        $json['flag'] = true;
        $phone = $_POST['cellphone'];
        $pet_list = '';

        $que = "SELECT COUNT(*) AS cnt FROM tb_customer_family WHERE to_cellphone = '{$phone}' OR from_cellphone = '{$phone}'";
        $row = sql_fetch_array($que);
        if($row[0]['cnt']>0){
            $json['flag'] = false;
        }

        $que = "SELECT COUNT(*) AS cnt FROM tb_tmp_user WHERE cellphone = '{$phone}'";
        //echo $que;
        $row1 = sql_fetch_array($que);
        if($row1[0]['cnt']>0){
            $json['flag'] = false;
        }

        $que = "SELECT COUNT(*) AS cnt FROM tb_customer WHERE cellphone = '{$phone}'";
        //echo $que;
        $row1 = sql_fetch_array($que);
        if($row1[0]['cnt']>0){
            $json['flag'] = false;
        }

        if($json['flag']==false) {
            $que = "SELECT *
					FROM tb_payment_log
					WHERE cellphone = '" . $phone . "'	
					AND artist_id = '{$user_id}'					
						AND data_delete = '0' ORDER BY CONCAT(year,'-',month,'-',day) DESC LIMIT 1";
            //echo $que;
            $pet = sql_fetch_array($que);


            if(empty($pet[0]['pet_seq'])) {
                $que = "SELECT *
					FROM tb_payment_log
					WHERE cellphone = '" . $phone . "'	
					AND artist_id != '{$user_id}'					
						AND data_delete = '0' GROUP BY pet_seq ";
                //echo $que;
                $pet1 = sql_fetch_array($que);
                if(count($pet1)>0){
                    foreach($pet1 as $pet1){
                        $que = "SELECT * FROM tb_mypet WHERE pet_seq = '{$pet1['pet_seq']}'";
                        $pet_info = sql_fetch_array($que);
                        if(!empty($pet_info[0]['name'])) {
                            $pet_list .= '<div class="grid-layout-cell flex-auto">
                                        <label class="form-toggle-box"><input name="pet_no" class="pet-no" type="radio" value="' . $pet1['pet_seq'] . '" data-no="' . $pet1['pet_seq'] . '"><em>' . $pet_info[0][name] . '</em></label>
                                    </div>';
                        }

                    }
                    $json['pet_data'] = $pet_info;
                    $json['pet_list'] = $pet_list;
                }
            } else {
                $json['flag'] = 'already';
            }

            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo json_encode($json);
        break;

    //미용사 순서 변경
    case 'setBeautyChangeGrade':
        $json['flag'] = true;
        for($i=0;$i<count($_POST['grade']);$i++){
            $que = "UPDATE tb_artist_list SET sequ_prnt = '{$i}' WHERE artist_id = '{$_POST['artist_id'][$i]}' AND name = '{$_POST['name'][$i]}'";
            echo $que."\n";
            sql_query($que);
        }

        break;

        //등급저장
    case 'setShopGrade':
        $json['flag'] = true;

        $que = "UPDATE tb_grade_of_shop SET grade_name = '{$_POST['g1']}' WHERE grade_ord = 1 AND artist_id = '{$user_id}'";
        sql_query($que);
        $que = "UPDATE tb_grade_of_shop SET grade_name = '{$_POST['g2']}' WHERE grade_ord = 2 AND artist_id = '{$user_id}'";
        sql_query($que);
        $que = "UPDATE tb_grade_of_shop SET grade_name = '{$_POST['g3']}' WHERE grade_ord = 3 AND artist_id = '{$user_id}'";
        sql_query($que);


        echo json_encode($json);
        break;

    //예약승인/취소
    case 'reserveStatusChange':
        $json['flag'] = true;
        if($_POST['type'] == 2){
            $json['error'] = '예약이 확정되었습니다.';
        } else if($_POST['type']==3){
            $json['error'] = '예약신청이 취소되었습니다.';
        }

        $que = "UPDATE tb_grade_reserve_approval_mgr SET is_approve = '{$_POST['type']}' WHERE idx = {$_POST['no']} ";
        $res = sql_query($que);
        if($_POST['type'] == '3'){
            $que = "
                UPDATE tb_payment_log SET
                is_cancel = 1
                WHERE payment_log_seq IN (
                    SELECT payment_log_seq FROM tb_grade_reserve_approval_mgr WHERE idx = {$_POST['no']}
                )
            ";
            $res = sql_query($que);
        }
        if(!$res){
            $json['flag'] = false;
            $json['error'] = '업데이트시 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
            echo json_encode($json);
            break;
        }

        echo json_encode($json);
        break;

    //미용예약취소
    case 'cancelRev':
        sql_query('BEGIN');
        $json['flag'] = true;

        //결제 데이터를 구한다.
        $que = "SELECT * FROM tb_payment_log WHERE payment_log_seq = '{$_POST['payment_log_seq']}'";
        //echo $que;
        $pay = sql_fetch_array($que);

        //마이펫
        $que = "SELECT * FROM tb_mypet WHERE pet_seq = {$pay[0]['pet_seq']}";
        $pet = sql_fetch_array($que);


        //사용한 쿠폰이 있는지 확인한다.
        $que = "SELECT * FROM tb_coupon_history a LEFT JOIN tb_coupon b ON a.coupon_seq = b.coupon_seq WHERE a.payment_log_seq = '{$_POST['payment_log_seq']}'";
        $cu = sql_fetch_array($que);
        if(!empty($cu[0]['payment_log_seq'])){
            if($cu[0]['type']=='U'){
                $balance = $cu[0]['balance'] + $cu[0]['amount'];
                $que  = "INSERT INTO tb_coupon_history SET ";
                $que .= "coupon_seq         = '{$cu[0]['coupon_seq']}', ";
                $que .= "user_coupon_seq    = '{$cu[0]['user_coupon_seq']}', ";
                $que .= "payment_log_seq    = '0', ";
                $que .= "amount             = '{$cu[0]['amount']}', ";
                $que .= "balance            = '{$balance}', ";
                $que .= "customer_id        = '{$cu[0]['customer_id']}', ";
                $que .= "tmp_seq            = '{$cu[0]['tmp_seq']}', ";
                $que .= "artist_id          = '{$cu[0]['artist_id']}', ";
                $que .= "memo               = '예약취소', ";
                $que .= "type               = 'N' ";
                $res = sql_query($que);
                if(!$res){
                    $json['flag'] = false;
                    sql_query('ROLLBACK');
                    echo json_encode($json);
                    break;
                }

                //쿠폰내역 업데이트
                $que = "UPDATE tb_coupon SET price = price - {$cu[0]['amount']}, update_date = NOW() WHERE coupon_seq = {$cu[0]['coupon_seq']} ";
                $res = sql_query($que);
                if(!$res){
                    $json['flag'] = false;
                    sql_query('ROLLBACK');
                    echo json_encode($json);
                    break;
                }
            }
        }


        //결제 페이지 취소 플래그 업데이트
        $que = "UPDATE tb_payment_log SET is_cancel = '1', cancel_time = NOW() WHERE payment_log_seq = '{$pay[0]['payment_log_seq']}'";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            sql_query('ROLLBACK');
            echo json_encode($json);
            break;
        }


        if($pay[0]['reserve_point']>0) { //지급된 포인트가 존재할 경우만
            if(!empty($_POST['cid'])){
                $que = "UPDATE tb_user_reserve SET reserve = reserve - '{$pay[0]['reserve_point']}' WHERE customer_id = '{$pay[0]['customer_id']}' AND artist_id = '{$pay[0]['artist_id']}' AND cellphone = '{$pay[0]['cellphone']}'";
            } else {
                $que = "UPDATE tb_user_reserve SET reserve = reserve - '{$pay[0]['reserve_point']}' WHERE tmp_seq = '{$_POST['tid']}' AND artist_id = '{$pay[0]['artist_id']}' AND cellphone = '{$pay[0]['cellphone']}'";
            }
            $res1 = sql_query($que);
            if(!$res1){
                $json['flag'] = false;
                sql_query('ROLLBACK');
                echo json_encode($json);
                break;
            }

            $que  = "INSERT INTO tb_user_reserve_log SET ";
            $que .= "customer_id        = '{$pay['cid']}', ";
            $que .= "artist_id          = '{$pay['aid']}', ";
            $que .= "tmp_seq            = '{$_POST['tid']}', ";
            $que .= "cellphone          = '{$pay['phone']}', ";
            $que .= "payment_log_seq    = '{$pay['seq']}', ";
            $que .= "service_type       = '{$pay['type']}', ";
            $que .= "type               = 'U', ";
            $que .= "memo               = '예약취소 적립금 회수', ";
            $que .= "use_reserve        = '{$pay['price']}', ";
            $que .= "reg_dt             = NOW() ";
            $res2 = sql_query($que);
            if(!$res2){
                $json['flag'] = false;
                sql_query('ROLLBACK');
                echo json_encode($json);
                break;
            }
        }

        $allim_chk = $_POST['talk'];
        // 취소 알림톡
        $shop_sql = "SELECT s.name as name FROM tb_customer as c LEFT OUTER JOIN tb_shop s ON s.customer_id = c.id WHERE c.id = '".$pay[0]['artist_id']."'";
        //$shop_result = sql_query($shop_sql);
        $shop_data = sql_fetch_array($shop_sql);
        $shopName = $shop_data[0]['name'];

        $now = DATE("Y-m-d H:i");
        $nowTime = date("Y-m-d H:i", strtotime($now));

        $year               = $pay[0]['year'];
        $month              = $pay[0]['month'];
        $day                = $pay[0]['day'];
        $hour               = $pay[0]['hour'];
        $minute             = $pay[0]['minute'];
        $reservationTime    = strtotime("$year-$month-$day $hour:$minute");

        if ($allim_chk == "Y") { // 발송을 눌렀을때 발송하기
            $talk = new Allimtalk();

            $cellphone = $pay[0]['cellphone'];

            $week_arr = ['일', '월', '화', '수', '목', '금', '토'];
            $talkCustomerName = substr($cellphone, -4);
            //$talkDate = date("Y년 m월 d일 H시 i분", $reservationTime);
            $talkDate = date("Y년 m월 d일", $reservationTime);
            $talkDate .= "(".$week_arr[date("w", $reservationTime)].") ";
            $talkDate .= date("H시 i분", $reservationTime);

            $cancelTime = strtotime($nowTime);
            $cancelDate = date("Y년 m월 d일", $cancelTime);
            $cancelDate .= "(".$week_arr[date("w", $cancelTime)].") ";
            $cancelDate .= date("H시 i분", $cancelTime);
            $talk->cellphone = $cellphone;
            $talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=".$pay[0]['payment_log_seq'];
            //$talkBtnLink = "https://partner.banjjakpet.com";
            $talkResult = $talk->sendScheduleCancelNoticeNow_new($talkCustomerName, $pet[0]['name'], $shopName, $cancelDate, $talkDate, $talkBtnLink);
        }



        sql_query('COMMIT');
        echo json_encode($json);
        break;
    //예약변경 알림톡
    case 'changeReserve':
        $allim_chk = $_POST['talk'];
        //print_r($_POST);
        $json['flag'] = true;
        $json['error'] = '처리되었습니다.';
        $que = "SELECT a.*, b.name FROM tb_payment_log a LEFT JOIN tb_mypet b ON a.pet_seq = b.pet_seq WHERE payment_log_seq = '{$_POST['payment_log_seq']}' ";
        $pay = sql_fetch_array($que);

        $shop_sql = "SELECT s.name as name FROM tb_customer as c LEFT OUTER JOIN tb_shop s ON s.customer_id = c.id WHERE c.id = '".$pay[0]['artist_id']."'";
        //echo $shop_sql;
        $shop_data = sql_fetch_array($shop_sql);
        $shopName = $shop_data[0]['name'];

        $now = DATE("Y-m-d H:i");
        $nowTime = date("Y-m-d H:i", strtotime($now));

        $year               = $pay[0]['year'];
        $month              = $pay[0]['month'];
        $day                = $pay[0]['day'];
        $hour               = $pay[0]['hour'];
        $minute             = $pay[0]['minute'];
        $reservationTime    = strtotime("$year-$month-$day $hour:$minute");
        //$customerName = $shop_data[0]['usr_name'];
        $customerName = substr($shop_data[0]['usr_name'], -4);
        $petName = $pay[0]['name'];
        //echo $shopName = $shop_data[0]['name'];
        $date = date('Y년 m월 d일 H시 i분',strtotime($_POST['org_date']));
        $changeDate = date('Y년 m월 d일 H시 i분',strtotime($year.'-'.$month.'-'.$day.' '.$hour.':'.$minute));

        $talk = new Allimtalk();
        $talk->cellphone = $pay[0]['cellphone'];
        //$talkBtnLink = "https://shop.gopet.kr/reserve_pay_management_2?payment_log_seq=".$pay[0]['payment_log_seq'];
        $talkBtnLink = "https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$pay[0]['payment_log_seq'];
        $talkResult = $talk->sendUpdateNotice_new($customerName, $petName, $shopName, $date, $changeDate, $talkBtnLink);
        echo json_encode($json);
    break;
    //부가세설정
    case 'vat':
        $json['flag'] = true;
        $json['error'] = '처리되었습니다.';
        $que = "UPDATE tb_shop SET is_vat = '{$_POST['type']}' WHERE customer_id = '{$_POST['artist_id']}' ";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            break;
        }
        echo json_encode($json);
        break;

        //패밀리 전화번호
    case 'getFamilyPhone':
        $que = "SELECT * FROM tb_customer_family WHERE artist_id = '{$_POST['artist_id']}' AND to_cellphone = '{$_POST['phone']}' AND is_delete = 0";
        //echo $que;
        $arr = sql_fetch_array($que);
        if(count($arr)>0){
            foreach($arr as $fam){
                $tel['tel'] = $fam['from_cellphone'];
                $data[] = $tel;
            }
        }

        echo json_encode($data);
        break;
    //주 단위 빈시간 판매 데이터 업데이트
    case 'awaitAdd':
        //빈시간 판매 데이터를 삭제한다.

        // 빈시간 삭제 다시 by.glory_20220528
        /*
        for($i=0;$i<count($_POST['data']);$i++){
            $date[] = substr($_POST['data'][$i],0,10);
        }

        $date = array_unique($date);
        $date = array_values($date);
        //print_r($date);
        $del_date = implode("','",$date);
        */
        $del_date = $_POST['del_date'];


        $worker = array();
        $dt = array();
        $dtworker = array();
        for($i=0;$i<count($_POST['data']);$i++){
            $worker[0] = $_POST['worker'];
            $worker[1] = $_POST['data'][$i];
            $dt[$worker[0]][] = $worker[1];
            $dtworker[] = $worker[0];
            $dtdate[] = substr($worker[1],0,10);
            $dttime[] = substr($worker[1],-5);
        }

        $que1 = "DELETE FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}' AND empty_date IN ('{$del_date}') AND worker = '{$_POST['worker']}'";
        //echo $que;
        sql_query($que1);
        for($i=0;$i<count($dtworker);$i++) {
            $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}' AND worker = '{$dtworker[$i]}' AND empty_date = '{$dtdate[$i]}' AND empty_datetime = '{$dttime[$i]}' ";
            //echo $que."<br>";
            $row = sql_fetch_array($que);
            if(!$row[0]['cnt']){
                $sql = "INSERT INTO tb_sale_free_time_temp SET ";
                $sql .= "artist_id          = '{$_POST['artist_id']}', ";
                $sql .= "worker             = '{$dtworker[$i]}', ";
                $sql .= "empty_date         = '{$dtdate[$i]}', ";
                $sql .= "empty_datetime     = '{$dttime[$i]}', ";
                $sql .= "reg_dt             = NOW() ";
                //echo $sql;
                sql_query($sql);
            }
        }

        $total_cnt = 0;
        $que = "SELECT * FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}'";
        $sftt = sql_fetch_array($que);
        if(count($sftt)>0){
            foreach($sftt as $rs){
                $tmp = explode('|',$rs['empty_date']);
                for($i=0;$i<count($tmp);$i++) {
                    if(!empty($tmp[$i])) {
                        $ed[$rs['worker']][] = $tmp[$i];
                        $total_cnt++;
                    }
                }
            }
        }
        $json['cnt'] = $total_cnt;
        $json['sql'] = $que1;
        echo json_encode($json);
        break;
    //빈 시간 판매시 세션에 저장한다.

    //일 단위 빈시간 판매 데이터 업데이트
    case 'awaitAddDay':
        //빈시간 판매 데이터를 삭제한다.
        //print_r($_POST);
        $sel_dt = trim($_POST['sel_date']);
        $ex = explode('.',$sel_dt);
        $year = $ex[0];
        $month = sprintf('%02d',$ex[1]);
        $day = sprintf('%02d',$ex[2]);
        $sel_dt = $year.'-'.$month.'-'.$day;
        //$sel_dt = str_replace(".","-",$_POST['sel_date']);
        $worker = array();
        $dt = array();
        $dtworker = array();
        for($i=0;$i<count($_POST['data']);$i++){
            $worker = explode(",",$_POST['data'][$i]);
            $dt[$worker[0]][] = $worker[1];
            $dtworker[] = $worker[0];
            $dtdate[] = substr($worker[1],0,10);
            $dttime[] = substr($worker[1],-5);
        }

        $que = "DELETE FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}' AND empty_date = '{$sel_dt}'";
        //echo $que;
        sql_query($que);
        for($i=0;$i<count($dtworker);$i++) {
            $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}' AND worker = '{$dtworker[$i]}' AND empty_date = '{$dtdate[$i]}' AND empty_datetime = '{$dttime[$i]}' ";
            //echo $que."<br>";
            $row = sql_fetch_array($que);
            if(!$row[0]['cnt']){
                $sql = "INSERT INTO tb_sale_free_time_temp SET ";
                $sql .= "artist_id          = '{$_POST['artist_id']}', ";
                $sql .= "worker             = '{$dtworker[$i]}', ";
                $sql .= "empty_date         = '{$dtdate[$i]}', ";
                $sql .= "empty_datetime     = '{$dttime[$i]}', ";
                $sql .= "reg_dt             = NOW() ";
                //echo $sql;
                sql_query($sql);
            }
        }

        $total_cnt = 0;
        $que = "SELECT * FROM tb_sale_free_time_temp WHERE artist_id = '{$_POST['artist_id']}'";
        $sftt = sql_fetch_array($que);
        if(count($sftt)>0){
            foreach($sftt as $rs){
                $tmp = explode('|',$rs['empty_date']);
                for($i=0;$i<count($tmp);$i++) {
                    if(!empty($tmp[$i])) {
                        $ed[$rs['worker']][] = $tmp[$i];
                        $total_cnt++;
                    }
                }
            }
        }
        $json['cnt'] = $total_cnt;
        echo json_encode($json);
        break;

        // 일 전체선택 해제
    case 'noAllChk':

        $sel_dt = trim($_POST['data']);
        $ex = explode('.',$sel_dt);
        $year = $ex[0];
        $month = sprintf('%02d',$ex[1]);
        $day = sprintf('%02d',$ex[2]);
        $sel_dt = $year.'-'.$month.'-'.$day;
        $que = "DELETE FROM tb_sale_free_time_temp WHERE artist_id = '{$user_id}' AND empty_date = '{$sel_dt}'";
        //echo $que;
        sql_query($que);
        $total_cnt = 0;
        $que = "SELECT * FROM tb_sale_free_time_temp WHERE artist_id = '{$user_id}'";
        $sftt = sql_fetch_array($que);
        if(count($sftt)>0){
            foreach($sftt as $rs){
                $tmp = explode('|',$rs['empty_date']);
                for($i=0;$i<count($tmp);$i++) {
                    if(!empty($tmp[$i])) {
                        $ed[$rs['worker']][] = $tmp[$i];
                        $total_cnt++;
                    }
                }
            }
        }
        $json['cnt'] = $total_cnt;
        echo json_encode($json);
        break;

        //임시휴일 삭제
    case 'privateDel':
        $json['flag'] = true;
        $que = "DELETE FROM tb_private_holiday WHERE ph_seq = '{$_POST['seq']}'";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            break;
        }
        echo json_encode($json);
        break;
    //최종 금액 실시간 반영하기
    case 'changeTotalPrice':
        $price = trim(str_replace('원','',str_replace(',','',$_POST['price'])));
        $que = "UPDATE tb_payment_log SET local_price = {$price} WHERE payment_log_seq = {$_POST['seq']}";
        //echo $que;
        sql_query($que);
        break;
        //등급변경
    case 'gradeChange':
        $json['flag'] = true;
        $que = "SELECT COUNT(*) AS cnt FROM tb_grade_of_customer WHERE customer_id = '{$_POST['id']}' AND grade_idx = '{$_POST['org_grade']}'";
        //echo $que;
        $row = sql_fetch_array($que);
        if($row[0]['cnt']>0) {
            $que = "UPDATE tb_grade_of_customer SET grade_idx = '{$_POST['grade']}' WHERE customer_id = '{$_POST['id']}' AND grade_idx = '{$_POST['org_grade']}'";
        } else {
            //$que = "INSERT INTO tb_grade_of_customer SET grade_idx = '{$_POST['grade']}',  customer_id = '{$_POST['id']}'";
            $que = "INSERT INTO `tb_grade_of_customer` (`grade_idx`, `customer_id`, `is_delete`) VALUES ('{$_POST['grade']}', '{$_POST['id']}', 0);";
        }
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            break;
        }
        echo json_encode($json);
        break;

        // 전체등급변경시 customer_id 및 tmp_seq 가져오기
    case 'getAllGrade':
        $json['flag'] = true;
        $que = "SELECT cellphone FROM tb_payment_log WHERE artist_id = '{$_POST['id']}' GROUP BY cellphone";
        //echo $que;
        $row = sql_fetch_array($que);

        foreach($row as $rs){
            // 정회원 여부
            $cus_sql = "select * from tb_customer where cellphone = '{$rs['cellphone']}'";
            $cus_res = mysqli_query($connection,$cus_sql);
            $cus_pay = mysqli_fetch_assoc($cus_res);

            if($cus_pay['id'] != ''){ // 정회원일시
                $grade_id[] = $cus_pay['id'];
            }else{ //정회원 아이디가 없으면 가회원으로 취급
                $tmp_sql = "select * from tb_tmp_user where cellphone = '{$rs['cellphone']}'";
                $tmp_res = mysqli_query($connection,$tmp_sql);
                $tmp_pay = mysqli_fetch_assoc($tmp_res);
                $grade_id[] = $tmp_pay['tmp_seq'];
            }
        }
        if(!$row){
            $json['flag'] = false;
            $json['sql'] = $que;
            echo json_encode($json);
            break;
        }else{
            $json['sql'] = $grade_id;
            echo json_encode($json);
            break;
        }

    case 'gradeChangeAll':

        $json['flag'] = true;
        $que = "
            SELECT * FROM tb_grade_of_customer a
            INNER JOIN tb_grade_of_shop b ON a.grade_idx = b.idx
            WHERE a.customer_id = '{$_POST['grade_id']}'
            AND b.artist_id = '{$_POST['shop_id']}'
        ";
        //echo $que;
        $row = sql_fetch_array($que);
        if(count($row[0]['grade_idx'])>0) {
            $que = "UPDATE tb_grade_of_customer SET grade_idx = '{$_POST['grade']}' WHERE customer_id = '{$_POST['grade_id']}' AND grade_idx = '{$row[0]['grade_idx']}'";
        } else {
            //$que = "INSERT INTO tb_grade_of_customer SET grade_idx = '{$_POST['grade']}',  customer_id = '{$_POST['id']}'";
            $que = "INSERT INTO `tb_grade_of_customer` (`grade_idx`, `customer_id`, `is_delete`) VALUES ('{$_POST['grade']}', '{$_POST['grade_id']}', 0);";
        }
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            break;
        }
        echo json_encode($json);
        break;

    case 'noshowReset':
        $json['flag'] = true;
        $que = "UPDATE tb_payment_log SET is_no_show = '0' WHERE artist_id = '{$user_id}' AND pet_seq = '{$_POST['pet_seq']}'";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            break;
        }
        echo json_encode($json);
        break;
    case 'familyAddTel':
        $json['flag'] = true;
        $json['error'] = '등록되었습니다.';
        $name = $_POST['name'];
        $tel = str_replace('-','',$_POST['tel']);
        $mphone = $_POST['mphone'];
        $customer_id = $_POST['customer_id'];
        $que = "SELECT COUNT(*) AS cnt FROM tb_customer_family WHERE artist_id = '{$user_id}' AND to_cellphone = '{$mphone}' AND from_cellphone = '{$tel}' AND is_delete = 0";
        //echo $que;
        $row = sql_fetch_array($que);
        if(!$row[0]['cnt']) {
            $que = "INSERT INTO tb_customer_family SET ";
            $que .= "artist_id          = '{$user_id}', ";
            $que .= "to_cellphone       = '{$mphone}', ";
            $que .= "to_customer_id     = '{$customer_id}', ";
            $que .= "from_nickname      = '{$name}', ";
            $que .= "from_cellphone     = '{$tel}', ";
            $que .= "from_customer_id   = '', ";
            $que .= "reg_dt             = NOW() ";
            //echo $que;
            $res = sql_query($que);
            if (!$res) {
                $json['flag'] = false;
                $json['error'] = '등록시 오류가 발생했습니다.';
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                break;
            }
        } else {
            $json['flag'] = false;
            $json['error'] = '이미 등록된 번호 입니다.';
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
    case 'modifyTel':
        $json['flag'] = true;
        $json['error'] = '수정되었습니다.';
        $tel = str_replace('-','',$_POST['tel']);

        $que = "UPDATE tb_tmp_user SET ";
        $que .= "cellphone     = '{$tel}' ";
        $que .= "WHERE cellphone = '{$_POST['org']}' ";
        //echo $que;
        $res = sql_query($que);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '수정시 오류가 발생했습니다.';
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        //발신자 테이블내 고객 번호 변경.
        $que = "UPDATE tb_sent_cell_id SET cellphone = '{$tel}' WHERE cellphone = '{$_POST['org']}' AND artist_id = '{$user_id}'";
        sql_query($que);

        //결제내역 번호를 변경한다.
        $que = "UPDATE tb_payment_log SET cellphone = '{$tel}' WHERE cellphone = '{$_POST['org']}'";
        sql_query($que);

        //대표 번호를 변경한다.
        $que = "UPDATE tb_customer_family SET to_cellphone = '{$tel}' WHERE  artist_id = '{$user_id}' AND to_cellphone = '{$_POST['org']}' AND from_cellphone = '{$tel}'";
        sql_query($que);

        $json['tel'] = $tel;
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

        //대표번호 변경
    case 'changeMainPhone':
        $json['flag'] = true;
        $json['error'] = '대표번호가 변경되었습니다.';
        $tel = str_replace('-','',$_POST['tel']);

        $que = "UPDATE tb_tmp_user SET ";
        $que .= "cellphone     = '{$tel}' ";
        $que .= "WHERE cellphone = '{$_POST['org']}' ";
        //echo $que;
        $res = sql_query($que);
        if (!$res) {
            $json['flag'] = false;
            $json['error'] = '수정시 오류가 발생했습니다.';
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            break;
        }

        //결제정보 휴대폰 번호를 모두 변경한다.
        $que = "UPDATE tb_payment_log SET cellphone = '{$tel}' WHERE cellphone = '{$_POST['org']}'";
        //echo $que;
        sql_query($que);

        //기존 가족 번호를 삭제한다.
        $que = "UPDATE tb_customer_family SET is_delete = 1 WHERE artist_id = '{$user_id}' AND to_cellphone = '{$_POST['org']}' AND from_cellphone = '{$tel}' AND is_delete = 0";
        //echo $que;
        sql_query($que);

        //대표 번호를 변경한다.
        $que = "UPDATE tb_customer_family SET to_cellphone = '{$tel}' WHERE  artist_id = '{$user_id}' AND to_cellphone = '{$_POST['org']}' ";
        //echo $que;
        sql_query($que);

        //이전 대표 번호를 등록한다.
        $que = "INSERT INTO tb_customer_family SET ";
        $que .= "artist_id          = '{$user_id}', ";
        $que .= "to_cellphone       = '{$tel}', ";
        $que .= "from_nickname      = '', ";
        $que .= "from_cellphone     = '{$_POST['org']}', ";
        $que .= "from_customer_id   = '', ";
        $que .= "reg_dt             = NOW() ";
        //echo $que;
        sql_query($que);



        $json['tel'] = $tel;
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
        //신규등록 기존고객 / 펫
    case 'registNew':
        $json['flag'] = true;
        $json['error'] = '';
        $que = "SELECT * FROM tb_mypet WHERE pet_seq = '{$_POST['pet_seq']}'";
        $pet = sql_fetch_array($que);
        $pet_type = ($pet[0]['type']=='cat')? "고양이" : "개";

        $que = "INSERT INTO tb_payment_log SET ";
        $que .= "pet_seq            = '{$_POST['pet_seq']}', ";
        $que .= "session_id         = '" . session_id() . "', ";
        $que .= "customer_id        = '{$_POST['customer_id']}', ";
        $que .= "artist_id          = '{$user_id}', ";
        $que .= "order_id           = '" . str2hex($_POST['customer_id'] . "_" . rand_id()) . "', ";
        $que .= "cellphone          = '{$_POST['cellPhone']}', ";
        $que .= "approval           = '1', ";
        $sql = "SELECT is_vat, name FROM tb_shop WHERE customer_id = '{$user_id}'";
        $r = sql_query($sql);
        $rw = sql_fetch($r);
        $que .= "product            = '".$_POST['pet_name']."|".$pet_type."|".$rw['name']."|||:0|||||||||0|0|0|0|0|0', ";
        $que .= "is_vat             = '{$rw['is_vat']}', ";
        $que .= "buy_time           = NOW() ";
        //echo $que . "<br>";
        $res = sql_query($que);
        $id = mysqli_insert_id($connection);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $json['pay'] = $id;
        $json['pet_name'] = $_POST['pet_name'];
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

        //이전 미용 특이사항 가져오기
    case 'getPrevInfo':
        $ct = 0;
            $sql = "
                SELECT *
                FROM tb_payment_log 
                WHERE artist_id = '" . $_SESSION['gobeauty_user_id'] . "' 
                    AND is_no_show = 0 
                    AND is_cancel = 0 
                    AND is_cancel = 0
                    AND approval = 1
                    AND etc_memo != ''
                    AND cellphone = '{$_POST['cellphone']}'
                    AND pet_seq = '{$_POST['pet']}'  
                    AND CONCAT(year,'-',month,'-',day) < DATE_FORMAT(NOW(),'%Y-%c-%e')
                    ORDER BY  year desc , month desc , day DESC                                   
            ";
            //echo $sql;
            $arr = sql_fetch_array($sql);
            if(count($arr)>0) {
                foreach($arr as $rs) {
                    $json['memo']['date'][] = $rs['year'] . '-' . $rs['month'] . '-' . $rs['day'];
                    $json['memo']['memo'][] = $rs['etc_memo'];;
                }
                $json['memo']['total'] = count($arr);
            } else {
                $json['memo']['total'] = 0;
            }

        $tp_cnt = 0;
        $que = "SELECT * FROM tb_payment_log WHERE pet_seq = {$_POST['pet']} AND artist_id = '{$_SESSION['gobeauty_user_id']}' AND cellphone = '{$_POST['cellphone']}' 
                                       AND is_no_show = 0 AND data_delete = 0 AND is_cancel = 0 AND approval = 1 
                                       AND CONCAT(year,'-',month,'-',day) < DATE_FORMAT(NOW(),'%Y-%c-%e') ORDER BY  year desc , month desc , day DESC ";
        //echo $que;
        $arr = sql_fetch_array($que);
        $cnt =  count($arr);
        if($cnt>0) {
            foreach ($arr as $arr) {
                $que = "SELECT * FROM tb_mypet WHERE pet_seq = {$arr['pet_seq']}";
                $pet = sql_fetch_array($que);
                $date = $arr['year'] . '-' . $arr['month'] . '-' . $arr['day'];
                $prev_beauty = explode("|", $arr['product']);
                $kg = explode(":", $prev_beauty[5]);
                $json['beauty']['info'][] = date('Y.m.d',strtotime($date)) .'/'. $pet[0]["name"].'/'.$prev_beauty[4] .'/'. $kg[0] .'Kg / '.number_format($kg[1]).'원';
                $json['beauty']['pay']['pay'] = $arr['payment_log_seq'];
            }
            $json['beauty']['total'] = $cnt;
        } else {
            $json['beauty']['total'] = 0;
        }
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;

        //카드 금액 변경시
    case 'changeCardPrice':
        $price = str_replace(",","",$_POST['price']);
        $que = "UPDATE tb_payment_log SET add_price = '{$price}' WHERE payment_log_seq = '{$_POST['seq']}'";
        //echo $que;
        sql_query($que);
        break;

    //추가연락처 삭제
    case 'extraTelDel':
        $json['flag'] = true;
        $que = "UPDATE tb_customer_family SET is_delete = 1 WHERE family_seq = '{$_POST['seq']}'";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        break;
}
?>

