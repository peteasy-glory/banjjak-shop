<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
//include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$return_data = array();

$return_data['post'] = $_POST;
$return_data['get'] = $_GET;

$ph_seq = $_POST['idx'];
$artist_id = $_POST['aid'];
$worker_id = $_POST['wid'];

$json['flag'] = true;
$json['error'] = '예약금지설정이 해지 되었습니다.';
if ( !$ph_seq ) {
    $json['flag'] = false;
    $json['error'] = '삭제할 대상이 선택하지 않습니다.';
} else {
    $sql = "DELETE FROM `tb_private_holiday` WHERE ph_seq={$ph_seq}";
    $res = mysqli_query($connection, $sql);
    if(!$res){
        $json['flag'] = false;
        $json['error'] = '예약금지설정 해지시 오류가 발생했습니다.[99]';
    }
}

// $return_data['res'] = false;

echo json_encode($json,JSON_UNESCAPED_UNICODE);
