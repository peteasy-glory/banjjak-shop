<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$user_id = $_SESSION['gobeauty_user_id'];
//print_r($_POST);
//종일쉴경우 날짜로 처리한다.
if($_POST['time2']==1){
    $s_year = date('Y',$_POST['start_date']);
    $s_month = date('n',$_POST['start_date']);
    $s_day = date('j',$_POST['start_date']);
    $s_hour = '9';
    $s_min = '0';

    $e_year = date('Y',$_POST['end_date']);
    $e_month = date('n',$_POST['end_date']);
    $e_day = date('j',$_POST['end_date']);
    $e_hour = '23';
    $e_min = '0';

    for($i=0;$i<count($_POST['artist_id']);$i++) {
        $que = "INSERT INTO tb_private_holiday SET ";
        $que .= "customer_id    = '{$user_id}', ";
        $que .= "worker         = '{$_POST['artist_id'][$i]}', ";
        $que .= "type           = 'all', ";
        $que .= "start_year     = '{$s_year}', ";
        $que .= "start_month    = '{$s_month}', ";
        $que .= "start_day      = '{$s_day}', ";
        $que .= "start_hour     = '{$s_hour}', ";
        $que .= "start_minute   = '{$s_min}', ";

        $que .= "end_year       = '{$e_year}', ";
        $que .= "end_month      = '{$e_month}', ";
        $que .= "end_day        = '{$e_day}', ";
        $que .= "end_hour       = '{$e_hour}', ";
        $que .= "end_minute     = '{$e_min}', ";

        $que .= "update_time    = NOW() ";

        //echo $que."<p>";
        sql_query($que);
    }
} else {

    $s_hour = $_POST['break_type_time_hour'];
    $s_min = '0';

    $e_hour = $_POST['break_type_time_min'];
    $e_min = '0';

    for($i=0;$i<count($_POST['artist_id']);$i++) {
        $que = "INSERT INTO tb_private_holiday SET ";
        $que .= "customer_id    = '{$user_id}', ";
        $que .= "worker         = '{$_POST['artist_id'][$i]}', ";
        $que .= "type           = 'notall', ";
        $que .= "start_year     = '{$_POST['break_type_time_year']}', ";
        $que .= "start_month    = '{$_POST['break_type_time_month']}', ";
        $que .= "start_day      = '{$_POST['break_type_time_day']}', ";
        $que .= "start_hour     = '{$s_hour}', ";
        $que .= "start_minute   = '{$s_min}', ";

        $que .= "end_year       = '{$_POST['break_type_time_year']}', ";
        $que .= "end_month      = '{$_POST['break_type_time_month']}', ";
        $que .= "end_day        = '{$_POST['break_type_time_day']}', ";
        $que .= "end_hour       = '{$e_hour}', ";
        $que .= "end_minute     = '{$e_min}', ";

        $que .= "update_time    = NOW() ";

        //echo $que."<p>";
        sql_query($que);
    }
}
?>
<script>location.href='../set_schedule_modify_1'</script>

