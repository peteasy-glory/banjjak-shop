<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];
$get_pl_seq = $_GET['get_pl_seq'];
$get_tc_id = $_GET['get_tc_id'];

$artist_name = "";

$payment_log_sql = "UPDATE tb_payment_log SET approval=2 WHERE payment_log_seq='{$get_pl_seq}'";
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
    $image = "http://gopet.kr/pet/images/app_logo.png";
    a_push($get_tc_id, "반짝, 상담 신청 결과 알림", $message, $path, $image, "customer");
}
?>

<script>
    location.href = "../reserve_advice_list_1";
</script>
