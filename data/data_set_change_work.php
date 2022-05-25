<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");


$artist_flag = (isset($_SESSION['artist_flag'])) ? $_SESSION['artist_flag'] : "";

if ($artist_flag === true) {
    $artist_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
    $user_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
    $user_name = (isset($_SESSION['shop_user_nickname'])) ? $_SESSION['shop_user_nickname'] : "";
} else {
    $artist_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";
}

$json['flag'] = true;
$json['error'] = '';
//print_r($_POST);
$payment_log_seq = $_POST['log_seq'];
$move_date = date('Y-n-j',strtotime($_POST['log_date']));
$que = "SELECT * FROM tb_payment_log WHERE payment_log_seq = {$payment_log_seq}";
$tpl = sql_fetch_array($que);

//해당 날짜에 시작 시간을 가져온다.
$que = "SELECT * FROM tb_payment_log WHERE CONCAT(year,'-',month,'-',day) = '{$move_date}' AND artist_id = '{$user_id}' AND is_cancel = '0' 
        AND worker = '{$_POST['log_worker']}' AND NOT payment_log_seq = '{$payment_log_seq}' ORDER BY CONCAT(year,'-',month,'-',DAY,' ',HOUR,':',minute)
        ";
//echo $que;
$tpl1 = sql_fetch_array($que);

$worker = $_POST['log_worker'];
$split_date = explode("-",$_POST['log_date']);

// 바뀔 시간구하기
$start = strtotime($tpl[0]['hour'].':'.$tpl[0]['minute']);
$end = strtotime($tpl[0]['to_hour'].':'.$tpl[0]['to_minute']);
$time_end = ($end-$start)/1800;
$ed_hour = date('G',strtotime($_POST['log_move_start_time'])+(1800*$time_end));
$ed_min = intval(date('i',strtotime($_POST['log_move_start_time'])+(1800*$time_end)));
$this_start_time = strtotime($_POST['log_move_start_time']); // 바뀔예약 시작시간
$this_end_time = strtotime($ed_hour.":".$ed_min);  // 바뀔예약 종료시간


$result_end_time = 0; // reTime 일때 종료시간
if(count($tpl1)>0){ // 해당 날짜에 예약 있을 때
    foreach($tpl1 as $rs){

        $r_startTime = strtotime($rs['hour'].':'.$rs['minute']);
        $r_endTime = strtotime($rs['to_hour'].':'.$rs['to_minute']);


        for($i=$r_startTime;$i<=$r_endTime;$i+=1800){
            if($r_startTime <= $this_start_time && $r_endTime > $this_start_time || $r_startTime == $this_start_time ) {
                // 기존 예약 시간 사이에 시작시간이 있을때 > 예약 불가
                $json['1'] = "fail";
                break;
            }else if(($r_startTime < $this_end_time && $r_endTime >= $this_end_time) || ($r_startTime > $this_start_time && $r_endTime < $this_end_time)){
                // 바꿀예약 종료 시간이 기존예약 시작 시간과 겹칠때 1 > 종료시간 조정
                // 기존 예약 시작시간 필요
                $json['2'] = "reTime";
                $result_end_time = date('G:i',$r_startTime);
                break;
            }else{
                // 일단 시작시간이 안겹칠때 4=3
                $json['3'] = "keep";
            }
        }
    }
}else{
    $json['4'] = "keep";
}

if($json['1'] == "fail"){
    $json['flag'] = false;
    $json['result'] = "이미 다른 예약이 잡힌 시간이라 변경이 불가합니다.";
}else if($json['2'] == "reTime"){
    $json['result'] = "시간조정";

    $sql  = "UPDATE tb_payment_log SET ";
    $sql .= "worker     = '{$worker}', ";
    $sql .= "month      = '".intval($split_date[1])."', ";
    $sql .= "day        = '".intval($split_date[2])."', ";
    $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
    $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

    $sql .= "to_hour    = '".intval(substr($result_end_time,0,2))."', ";
    $sql .= "to_minute  = '".intval(substr($result_end_time,3,2))."', ";
    $sql .= "reserve_yn = '{$_POST['log_msg_send']}', update_time = NOW()";
    $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
    //echo $sql;
    $res = sql_query($sql);
    if(!$res){
        $json['flag'] = false;
        $json['result'] = '데이터 저장 오류1';
    }

}else if($json['3'] == "keep" || $json['4'] == "keep"){
    $json['result'] = "강고";

    $sql  = "UPDATE tb_payment_log SET ";
    $sql .= "worker     = '{$worker}', ";
    $sql .= "month      = '".intval($split_date[1])."', ";
    $sql .= "day        = '".intval($split_date[2])."', ";
    $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
    $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

    $sql .= "to_hour    = '{$ed_hour}', ";
    $sql .= "to_minute  = '{$ed_min}', ";
    $sql .= "reserve_yn = '{$_POST['log_msg_send']}', update_time = NOW()";
    $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
    //echo $sql;
    $res = sql_query($sql);
    if(!$res){
        $json['flag'] = false;
        $json['result'] = '데이터 저장 오류2';
    }

}else{
    $json['result'] = 'error';
}

if($json['flag'] == true){
    // 알림톡발송
    if($_POST['log_msg_send'] == "Y"){
        $shop_array = explode("|",$tpl[0]['product']);
        $shopName = $shop_array[2];
        $year               = intval($tpl[0]['year']);
        $month              = intval($tpl[0]['month']);
        $day                = intval($tpl[0]['day']);
        $hour               = intval($tpl[0]['hour']);
        $minute             = intval($tpl[0]['minute']);
        $reservationTime    = strtotime("$year-$month-$day $hour:$minute");
        //$customerName = $shop_data[0]['usr_name'];
        $customerName = substr($tpl[0]['cellphone'], -4);
        $petName = $shop_array[0];
        //echo $shopName = $shop_data[0]['name'];
        $date = date('Y년 m월 d일 H시 i분',$reservationTime);
        $changeDate = date('Y년 m월 d일 H시 i분',strtotime($year.'-'.$month.'-'.intval($split_date[2]).' '.intval(substr($_POST['log_move_start_time'],0,2)).':'.intval(substr($_POST['log_move_start_time'],3,2))));

        $talk = new Allimtalk();
        //$talkBtnLink = "https://shop.gopet.kr/reserve_pay_management_2?payment_log_seq=".$pay[0]['payment_log_seq'];
        $talk->cellphone = $tpl[0]['cellphone'];
        $talkBtnLink = "http://gopet.kr/pet/reservation/?payment_log_seq=".$payment_log_seq;
        $talkResult = $talk->sendUpdateNotice_new($customerName, $petName, $shopName, $date, $changeDate, $talkBtnLink);

    }
}

echo json_encode($json, JSON_UNESCAPED_UNICODE);


/*
if($_POST['log_type']=='week') {
    $que = "SELECT * FROM tb_artist_list WHERE nicname = '{$_POST['log_worker']}' AND is_out = 2 AND is_view = 2 AND artist_id = '{$user_id}' GROUP BY name";
    $tal = sql_fetch_array($que);
    $worker = $tal[0]['name'];
} else {
    $worker = $_POST['log_worker'];
}


if(count($tpl1)>0){
    foreach($tpl1 as $rs){

        $r_startTime = strtotime($rs['hour'].':'.$rs['minute']);
        $r_endTime = strtotime($rs['to_hour'].':'.$rs['to_minute']);

        $endTime = strtotime($_POST['log_move_start_time']);
        for($i=$r_startTime;$i<=$r_endTime;$i+=1800){

            if($_POST['log_type']=='week') {
                if ($i == $endTime) {
                    $json['flag'] = false;
                    $json['error'] = '이미 예약이 등록된 시간입니다.';
                    echo json_encode($json, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {

                if ($i == $endTime && $tpl[0]['worker'] == $worker) {
                    $json['flag'] = false;
                    $json['error'] = '이미 예약이 등록된 시간입니다.';
                    echo json_encode($json, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
        

        if($r_startTime > $endTime){
            //옮기려는 예약시간이 이미 예약된 시간의 시작 시간보다 이전이면
            $n_endTime = strtotime($tpl[0]['to_hour'].':'.$tpl[0]['to_minute']);
            for($j=$endTime;$j<=$n_endTime;$j+=1800){
                //echo date('H:i',$j).''.date('H:i',$r_startTime)."<br>";
                if($j==$r_startTime || $j == $r_endTime){
                    $n_et = $j;
                    $ed_hour = date('G',$n_et);
                    $ed_min = intval(date('i',$n_et));
                    $sql  = "UPDATE tb_payment_log SET ";
                    $sql .= "worker     = '{$worker}', ";
                    $sql .= "month      = '".intval($split_date[1])."', ";
                    $sql .= "day        = '".intval($split_date[2])."', ";
                    $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
                    $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

                    $sql .= "to_hour    = '{$ed_hour}', ";
                    $sql .= "to_minute  = '{$ed_min}', ";
                    $sql .= "reserve_yn = '{$_POST['log_msg_send']}'";
                    $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
                    //echo $sql;
                    $res = sql_query($sql);
                    if(!$res){
                        $json['flag'] = false;
                        $json['error'] = '데이터 저장 오류1';
                        echo json_encode($json, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    echo json_encode($json, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            $start = strtotime($tpl[0]['hour'].':'.$tpl[0]['minute']);
            $end = strtotime($tpl[0]['to_hour'].':'.$tpl[0]['to_minute']);
            $time_end = ($end-$start)/1800;
            $ed_hour = date('G',strtotime($_POST['log_move_start_time'])+(1800*$time_end));
            $ed_min = intval(date('i',strtotime($_POST['log_move_start_time'])+(1800*$time_end)));

            $sql  = "UPDATE tb_payment_log SET ";
            $sql .= "worker     = '{$worker}', ";
            $sql .= "month      = '".intval($split_date[1])."', ";
            $sql .= "day        = '".intval($split_date[2])."', ";
            $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
            $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

            $sql .= "to_hour    = '{$ed_hour}', ";
            $sql .= "to_minute  = '{$ed_min}', ";
            $sql .= "reserve_yn = '{$_POST['log_msg_send']}'";
            $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
            //echo $sql;
            $res = sql_query($sql);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '데이터 저장 오류2';
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $start = strtotime($tpl[0]['hour'].':'.$tpl[0]['minute']);
            $end = strtotime($tpl[0]['to_hour'].':'.$tpl[0]['to_minute']);
            $time_end = ($end-$start)/1800;
            $ed_hour = date('G',strtotime($_POST['log_move_start_time'])+(1800*$time_end));
            $ed_min = intval(date('i',strtotime($_POST['log_move_start_time'])+(1800*$time_end)));

            $sql  = "UPDATE tb_payment_log SET ";
            $sql .= "worker     = '{$worker}', ";
            $sql .= "month      = '".intval($split_date[1])."', ";
            $sql .= "day        = '".intval($split_date[2])."', ";
            $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
            $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

            $sql .= "to_hour    = '{$ed_hour}', ";
            $sql .= "to_minute  = '{$ed_min}', ";
            $sql .= "reserve_yn = '{$_POST['log_msg_send']}'";
            $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
            //echo $sql;
            $res = sql_query($sql);
            if(!$res){
                $json['flag'] = false;
                $json['error'] = '데이터 저장 오류3';
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
} else {

    $start = strtotime($tpl[0]['hour'].':'.$tpl[0]['minute']);
    $end = strtotime($tpl[0]['to_hour'].':'.$tpl[0]['to_minute']);
    $time_end = ($end-$start)/1800;
    $ed_hour = date('G',strtotime($_POST['log_move_start_time'])+(1800*$time_end));
    $ed_min = intval(date('i',strtotime($_POST['log_move_start_time'])+(1800*$time_end)));

    $sql  = "UPDATE tb_payment_log SET ";
    $sql .= "worker     = '{$worker}', ";
    $sql .= "month      = '".intval($split_date[1])."', ";
    $sql .= "day        = '".intval($split_date[2])."', ";
    $sql .= "hour       = '".intval(substr($_POST['log_move_start_time'],0,2))."', ";
    $sql .= "minute     = '".intval(substr($_POST['log_move_start_time'],3,2))."', ";

    $sql .= "to_hour    = '{$ed_hour}', ";
    $sql .= "to_minute  = '{$ed_min}', ";
    $sql .= "reserve_yn = '{$_POST['log_msg_send']}'";
    $sql .= " WHERE payment_log_seq = {$_POST['log_seq']}";
    //echo $sql;
    $res = sql_query($sql);
    if(!$res){
        $json['flag'] = false;
        $json['error'] = '데이터 저장 오류4';
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
    exit;
}
*/
?>
