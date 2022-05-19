<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);

$json['flag'] = true;

//고양이 옵션 데이터 초기화
$json['flag'] = true;
$que = "UPDATE tb_product_dog_static SET add_comment = '' WHERE customer_id = '{$user_id}' AND first_type = '개' AND second_type = '기타공통'";
//echo $que."<p>";
$res = sql_query($que);
if(!$res){
    $json['flag'] = false;
}

echo json_encode($json);
?>
