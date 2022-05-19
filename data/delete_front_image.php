<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];
$path = $_POST['path'];

$old_image_path = $upload_static_directory2 . $path;
// error_log('----- $old_image_path : '.$old_image_path);

$check_delete_image = false;
$check_delete_db = false;
$check_front_image = false; //----- 대표 사진 삭제 여부
$front_image;

$check_front_sql = "select front_image from tb_shop where customer_id='{$user_id}'";
$check_front_result = mysqli_query($connection, $check_front_sql);
if ($check_front_datas = mysqli_fetch_object($check_front_result)) {
    $front_image = $check_front_datas->front_image;
}

if($front_image == $path){
    $check_front_image = "true";
}

if(!$check_front_image){
    if (@unlink($old_image_path)) {
        $check_delete_old_image = "true";
        $s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
        $s3->deleteFileToS3(substr($path, 1));
    }
    
    $sql = "DELETE FROM tb_shop_frontimage WHERE customer_id = '" . $user_id . "' AND image = '" . $path . "';";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $check_delete_db = "true";
        echo "사진이 삭제되었습니다.";
    } else {
        echo "사진 삭제에 실패했습니다.";
    }
}else{
    echo "대문 사진은 삭제할 수 없습니다.";
}
?>
<!-- {
    "check_delete_image":"<?=$check_delete_image?>",
    "check_delete_db":"<?=$check_delete_db?>",
    "old_image_path":"<?=$old_image_path?>"
} -->