<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];
$get_pl_seq = $_GET['get_pl_seq'];
$get_tc_id = $_GET['get_tc_id'];

$artist_name = "";

$re_sql ="";

//$payment_log_sql = "UPDATE tb_payment_log SET approval=2 WHERE payment_log_seq='{$get_pl_seq}'";
// 12시간 지난 자동 취소시 첫이용상담 중복 요청 허용 수정으로 인해 해당 고객의 모든 첫이용상담 데이터 update by.glory 20220902
$payment_log_sql = "UPDATE tb_payment_log SET approval=2 WHERE artist_id = '{$user_id}' AND customer_id = '{$get_tc_id}' AND approval != 1 AND product_type = 'B' and data_delete = 0";
$payment_log_result = mysqli_query($connection, $payment_log_sql);
// error_log('----- $payment_log_sql : ' . $payment_log_sql);

$shop_info_sql = "SELECT * FROM tb_shop WHERE customer_id = '{$user_id}';";
// error_log('----- $customer_info_sql : ' . $customer_info_sql);
$shop_info_result = mysqli_query($connection, $shop_info_sql);
if ($shop_info_rows = mysqli_fetch_object($shop_info_result)) {
    $artist_name = $shop_info_rows->name;
}

if ($get_tc_id != null && $get_tc_id != "") {
    $message = "이제 [".$artist_name."]에서 원하시는 시간에 예약하실 수 있습니다.";
    $path = "https://customer.banjjakpet.com/reserve_view?artist_id=".$user_id;
    $image = "";
    a_push($get_tc_id, "반짝, 상담 신청 결과 알림", $message, $path, $image, "customer");
}
?>

<script>
    location.href = "../reserve_advice_list_1";
</script>
