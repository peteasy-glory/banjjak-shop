<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);

//고양이 옵션 데이터 초기화
$json['flag'] = true;
$que = "DELETE FROM tb_product_dog_common WHERE customer_id = '{$user_id}' AND first_type = '개' AND second_type = '추가공통옵션'";
//echo $que."<p>";
$res = mysqli_query($connection, $que);
if(!$res){
    $json['flag'] = false;
    echo json_encode($json);
    exit;
}
$que = "DELETE FROM tb_product_common_option WHERE customer_id = '{$user_id}' AND type = '목욕' ";
//echo $que."<p>";
$res = mysqli_query($connection, $que);

echo json_encode($json);
?>
