<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$user_id = $_SESSION['gobeauty_user_id'];
//print_r($_POST);
$sql = "
            SELECT COUNT(*) AS cnt
            FROM tb_working_schedule
            WHERE customer_id = '".$user_id."'
        ";
//echo $sql;
$row = sql_fetch_array($sql);
if($row[0]['cnt']>0) {
    $que = "UPDATE tb_working_schedule SET ";
    $que .= "working_start = '{$_POST['day_start']}', ";
    $que .= "working_end = '{$_POST['day_end']}', ";
    if(!isset($_POST['rest_public_holiday'])) {
        $que .= "rest_public_holiday = '1', ";
    } else {
        $que .= "rest_public_holiday = '{$_POST['rest_public_holiday']}', ";
    }
    $que .= "update_time = NOW() ";
    $que .= "WHERE customer_id = '{$user_id}'";
} else {
    $que = "INSERT INTO tb_working_schedule SET ";
    $que .= " customer_id = '{$user_id}', ";
    $que .= "working_start = '{$_POST['day_start']}', ";
    $que .= "working_end = '{$_POST['day_end']}', ";
    if(!isset($_POST['rest_public_holiday'])) {
        $que .= "rest_public_holiday = '1', ";
    } else {
        $que .= "rest_public_holiday = '{$_POST['rest_public_holiday']}', ";
    }
    $que .= "update_time = NOW()";
}
//echo $que;
sql_query($que);


//휴게시간 설정
$btime = implode(",",$_POST['break_time']);
$sql = "
            SELECT COUNT(*) AS cnt
            FROM tb_time_off
            WHERE customer_id = '".$user_id."'
        ";
//echo $sql;
$row = sql_fetch_array($sql);
if($row[0]['cnt']>0) {
    $que = "UPDATE tb_time_off SET ";
    $que .= "res_time_off = '{$btime}', ";
    $que .= "res_time_cnt = '', ";
    $que .= "res_time_off_yn = 'y', ";
    $que .= "update_date = NOW() ";
    $que .= "WHERE customer_id = '{$user_id}'";
} else {
    $que = "INSERT INTO tb_time_off SET ";
    $que .= " customer_id = '{$user_id}', ";
    $que .= "res_time_off = '{$btime}', ";
    $que .= "res_time_cnt = '', ";
    $que .= "res_time_off_yn = 'y', ";
    $que .= "reg_date = NOW(), update_date = NOW()";
}
//echo $que;
sql_query($que);

//예약 스케줄 운영방식 선택
//스케줄 타입 업데이트
$time_type = ($_POST['time2'] != '')? $_POST['time2'] : '1';
$que = "UPDATE tb_shop SET is_time_type = '{$time_type}' WHERE customer_id = '{$user_id}'";
sql_query($que);


$cnt = 0;
$que = " SELECT * FROM `tb_artist_list` WHERE artist_id='{$user_id}' AND is_view = '2' AND is_out = '2' GROUP BY name ORDER BY sequ_prnt asc, is_main DESC, nicname ASC";
//echo $que;
$rs = sql_fetch_array($que);
foreach($rs as $rs){
    $schedule[$cnt] = implode(',',$_POST['artist_break_time_'.$cnt]);
    if(!empty($schedule[$cnt])) {
        $sql = "
						SELECT COUNT(*) AS cnt
						FROM tb_time_schedule
                        WHERE artist_id = '".$user_id."'
						AND artist_name = '" . $rs['name'] . "';
					"; // 타임제 스케줄
        //echo $sql."<p>";
        $row = sql_fetch_array($sql);
        if ($row[0]['cnt'] > 0) {
            $que = "UPDATE tb_time_schedule SET ";
            $que .= "artist_id          = '{$user_id}', ";
            $que .= "artist_name        = '{$rs['name']}', ";
            $que .= "res_time_off       = '{$schedule[$cnt]}', ";
            $que .= "res_time_cnt       = '', ";
            $que .= "update_date        = NOW() ";
            $que .= "WHERE artist_id    = '{$user_id}' AND artist_name = '{$rs['name']}'";
        } else {
            $que = "INSERT INTO tb_time_schedule SET ";
            $que .= "artist_id      = '{$user_id}', ";
            $que .= "artist_name    = '{$rs['name']}', ";
            $que .= "res_time_off   = '{$schedule[$cnt]}', ";
            $que .= "res_time_cnt   = '', ";
            $que .= "reg_date       = NOW()";
        }
        //echo $que . "<p>";
        sql_query($que);

    }
    $cnt++;
}

//정기휴일설정
$sql = "
            SELECT COUNT(*) AS cnt
            FROM tb_regular_holiday
            WHERE customer_id = '".$user_id."'
        ";
//echo $sql;
$row = sql_fetch_array($sql);

$btime = ($btime)? $btime : 0;
$is_monday = ($_POST['is_monday'])? $_POST['is_monday'] : 0;
$is_tuesday = ($_POST['is_tuesday'])? $_POST['is_tuesday'] : 0;
$is_wednesday = ($_POST['is_wednesday'])? $_POST['is_wednesday'] : 0;
$is_thursday = ($_POST['is_thursday'])? $_POST['is_thursday'] : 0;
$is_friday = ($_POST['is_friday'])? $_POST['is_friday'] : 0;
$is_saturday = ($_POST['is_saturday'])? $_POST['is_saturday'] : 0;
$is_sunday = ($_POST['is_sunday'])? $_POST['is_sunday'] : 0;
$is_time = ($_POST['time4'])? $_POST['time4'] : 1;

if($row[0]['cnt']>0) {
    $que = "UPDATE tb_regular_holiday SET ";
    $que .= "is_monday          = '{$is_monday}', ";
    $que .= "is_tuesday         = '{$is_tuesday}', ";
    $que .= "is_wednesday       = '{$is_wednesday}', ";
    $que .= "is_thursday        = '{$is_thursday}', ";
    $que .= "is_friday          = '{$is_friday}', ";
    $que .= "is_saturday        = '{$is_saturday}', ";
    $que .= "is_sunday          = '{$is_sunday}', ";
    $que .= "week_type          = '{$is_time}', ";
    $que .= "update_time        = NOW() ";
    $que .= "WHERE customer_id  = '{$user_id}'";
} else {
    $que = "INSERT INTO tb_regular_holiday SET ";
    $que .= "customer_id        = '{$user_id}', ";
    $que .= "is_monday          = '{$is_monday}', ";
    $que .= "is_tuesday         = '{$is_tuesday}', ";
    $que .= "is_wednesday       = '{$is_wednesday}', ";
    $que .= "is_thursday        = '{$is_thursday}', ";
    $que .= "is_friday          = '{$is_friday}', ";
    $que .= "is_saturday        = '{$is_saturday}', ";
    $que .= "is_sunday          = '{$is_sunday}', ";
    $que .= "week_type          = '{$is_time}', ";
    $que .= "update_time        = NOW() ";
}
//echo $que;
sql_query($que);
?>

<script>parent.call_back();</script>
