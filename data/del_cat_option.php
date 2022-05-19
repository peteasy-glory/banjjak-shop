<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);

//고양이 옵션 데이터 초기화
$json['flag'] = true;
$que = "UPDATE tb_product_cat SET addition_option_product = '', addition_work_product = '', toenail_price = '', hair_clot_price='',ferocity_price='',tick_price='' WHERE customer_id = '{$user_id}' AND first_type = '고양이' AND second_type = '미용'";
//echo $que."<p>";
$res = mysqli_query($connection, $que);
if(!$res){
    $json['flag'] = false;
}

echo json_encode($json);
?>
