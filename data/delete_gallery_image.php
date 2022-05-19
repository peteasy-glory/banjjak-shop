<?php

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$user_id = $_SESSION['gobeauty_user_id'];
$idx = $_POST['idx'];
$path = $_POST['path'];
$localImage = $upload_static_directory2 . $path;

@unlink($path);
$check_delete_old_image = "true";
$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$s3->deleteFileToS3(substr($path, 1));

$sql = "DELETE FROM tb_mypet_beauty_photo WHERE idx = " . $idx . ";";
$result = mysqli_query($connection, $sql);
if ($result) {
    echo "사진이 삭제되었습니다.";
} else {
    echo "사진 삭제에 실패했습니다.";
}



?>
