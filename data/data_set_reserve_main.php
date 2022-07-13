<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$data = array();
$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

// 카테고리 리스트 가져오기
if($customer_id){
    $reserve_main = ($_POST["reserve_main"] && $_POST["reserve_main"] != "")? $_POST["reserve_main"] : 0;

    $sql = "
        UPDATE tb_shop SET 
        reserve_main = {$reserve_main}
        WHERE customer_id = '{$customer_id}'
    ";
    $result = mysqli_query($connection,$sql);
    if($result){
        $return_data = array("code" => "000000", "data" => "ok");
    }else{
        $return_data = array("code" => "000001", "data" => "no");
    }
}else{
    $return_data = array("code" => "000007", "data" => "중요 데이터 누락");
}

echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
