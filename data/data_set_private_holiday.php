<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$crypto = new Crypto();

$return_data = array();

list($_POST['ph_start_hour'],$_POST['ph_start_minute']) = explode(':',$_POST['ph_start_time']);
list($_POST['ph_end_hour'],$_POST['ph_end_minute']) = explode(':',$_POST['ph_end_time']);

$return_data['post'] = $_POST;
$return_data['get'] = $_GET;

$artist_flag = $_SESSION['artist_flag'];

$user_id = ($artist_flag)?$_SESSION['shop_user_id']:$_SESSION['gobeauty_user_id'];
$worker = $_POST['ph_worker'];
$type = $_POST['ph_type'];
$start_year = (int)$_POST['ph_start_year'];
$start_month = (int)$_POST['ph_start_month'];
$start_day = (int)$_POST['ph_start_day'];
$start_hour = (int)$_POST['ph_start_hour'];
$start_minute = (int)$_POST['ph_start_minute'];
$end_year = (int)$_POST['ph_start_year'];
$end_month = (int)$_POST['ph_start_month'];
$end_day = (int)$_POST['ph_start_day'];
$end_hour = (int)$_POST['ph_end_hour'];
$end_minute = (int)$_POST['ph_end_minute'];

$sql_pay = "SELECT 
        * 
    FROM 
        `tb_payment_log` pay
    WHERE 
        artist_id = '{$user_id}'
        AND worker = '{$worker}'
        AND DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) >= DATE_FORMAT( '{$start_year}-{$start_month}-{$start_day} {$start_hour}:{$start_minute}:00' , '%Y-%m-%d %H:%i' )
        AND DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.to_hour,':',IFNULL(pay.to_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) <= DATE_FORMAT( '{$end_year}-{$end_month}-{$end_day} {$end_hour}:{$end_minute}:00' , '%Y-%m-%d %H:%i' )
";
$return_data['SQL'][] = $sql_pay;

$sql_holiday = "SELECT 
        * 
    FROM 
        `tb_private_holiday` holiday
    WHERE 
        customer_id = '{$user_id}'
        AND worker = '{$worker}'
        AND DATE_FORMAT( CONCAT( holiday.start_year,'-',holiday.start_month,'-',holiday.start_day,' ',holiday.start_hour,':',IFNULL(holiday.start_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) >= DATE_FORMAT( '{$start_year}-{$start_month}-{$start_day} {$start_hour}:{$start_minute}:00' , '%Y-%m-%d %H:%i' )
        AND DATE_FORMAT( CONCAT( holiday.end_year,'-',holiday.end_month,'-',holiday.end_day,' ',holiday.end_hour,':',IFNULL(holiday.end_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) <= DATE_FORMAT( '{$end_year}-{$end_month}-{$end_day} {$end_hour}:{$end_minute}:00' , '%Y-%m-%d %H:%i' )
";
//echo $sql_holiday;
$return_data['SQL'][] = $sql_holiday;

if ( $result_pay = mysqli_query($connection, $sql_pay) || $result_holiday = mysqli_query($connection, $sql_holiday) ) {
    if ( $rows = mysqli_num_rows($result_pay) ) {
        $return_data['res'] = false;
        $return_data['error'] = '고객이 예약한 시간은 쉴 수 없습니다.';
        echo json_encode($return_data);
        exit;
    } else if ( $rows = mysqli_num_rows($result_holiday) ) {
        $return_data['res'] = false;
        $return_data['error'] = '이미 쉬는 시간이 적용되어 있습니다1.';
        echo json_encode($return_data);
        exit;
    } else {
        $return_data['res'] = true;
    }
}
if ( $result_holiday = mysqli_query($connection, $sql_holiday) ) {
    if ( $rows = mysqli_num_rows($result_holiday)  ) {
        $return_data['res'] = false;
        $return_data['error'] = '이미 쉬는 시간이 적용되어 있습니다2.';
        echo json_encode($return_data);
        exit;
    } else {
        $return_data['res'] = true;
    }
} else {
    $return_data['res'] = false;
    $return_data['error'] = mysqli_error($connection);
    $return_data['errno'] = mysqli_errno($connection);
    echo json_encode($return_data);
    exit;
}

$sql = "INSERT 
    INTO 
        `tb_private_holiday` 
    (
        customer_id, worker, 
        type, 
        start_year, start_month, start_day, start_hour, start_minute, 
        end_year, end_month, end_day, end_hour, end_minute, 
        update_time
    ) values (
        '{$user_id}', '{$worker}',
        '{$type}',
        {$start_year}, {$start_month}, {$start_day}, {$start_hour}, {$start_minute},
        {$end_year}, {$end_month}, {$end_day}, {$end_hour}, {$end_minute}, 
        now()
    )
";
$return_data['SQL'][] = $sql;
if ( $result = mysqli_query($connection, $sql) ) {
    $rows = mysqli_affected_rows($connection);
    $return_data['res'] = true;
    $return_data['rows'] = $rows;
} else {
    $return_data['res'] = false;
    $return_data['error'] = mysqli_error($connection);
    $return_data['errno'] = mysqli_errno($connection);
}

echo json_encode($return_data,JSON_UNESCAPED_UNICODE);
?>