<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_POST['user_id'];
$path = $_POST['path'];

$old_image_path = $upload_static_directory2.$path;
// error_log('----- $old_image_path : '.$old_image_path);

$check_delete_image = "false";
$check_delete_db = "false";

if(@unlink($old_image_path)){
    $check_delete_old_image = "true";
    $s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
    $s3->deleteFileToS3(substr($path, 1));
}

$sql = "delete from tb_portfolio where customer_id = '".$user_id."' and image = '".$path."';";
// error_log('----- $sql : '.$sql);
if($result = mysqli_query($connection, $sql)){
    $check_delete_db = "true";
    echo "사진이 삭제되었습니다.";
}else{
    echo "사진 삭제에 실패했습니다.";
}

?>

<!-- {
    "check_delete_image":"<?=$check_delete_image?>",
    "check_delete_db":"<?=$check_delete_db?>",
    "old_image_path":"<?=$old_image_path?>"
} -->
