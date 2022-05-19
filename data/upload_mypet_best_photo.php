<?php

ini_set('memory_limit', -1);
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$app = new App();
$isApp = $app->is_app();

$user_id = $_SESSION['gobeauty_user_id'];
$pet_seq = $_POST['pet_seq'];
$src_file = $_POST['filepath'];
$new_file = $_POST['newfilepath'];


make_user_directory($upload_static_directory.$upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');
$ext = array_pop(explode('.', $src_file));

// 확장자 확인
if( !in_array(strtolower($ext), $allowed_ext) ) {
    echo "허용되지 않는 확장자입니다.";
    exit;
}

$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$upload_direcoty_full_path = $upload_directory."/".$new_file;
if($isApp==1) {    // android용
    $filepath_appuploaded = $upload_static_directory . "/upload/appupload/" . $src_file;
    if(!copy($filepath_appuploaded, $upload_static_directory.'/'.$upload_direcoty_full_path)){
        echo '파일복사실패';
    } else {
        $s3->resizeImage($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_static_directory.'/'.$upload_direcoty_full_path);
        $s3->fileToS3($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_direcoty_full_path);
        @unlink($filepath_appuploaded);
    }
} else {    // pc / iphone용
    move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.'/'.$upload_direcoty_full_path);
    $s3->resizeImage($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_static_directory.'/'.$upload_direcoty_full_path);
    $s3->fileToS3($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_direcoty_full_path);
}

//$payment_log_seq = strlen(trim($_POST['payment_log_seq']));
//if($payment_log_seq < 1){
//    $payment_log_seq = 0;
//}
//
//if($pet_seq > 0){   // 이미지 성공 저장
//    $que1  = "INSERT INTO tb_mypet_beauty_photo SET ";
//    $que1 .= "payment_log_seq 	= '{$payment_log_seq}', ";
//    $que1 .= "artist_id 		= '{$_POST['artist_id']}', ";
//    $que1 .= "pet_seq 			= '{$_POST['pet_seq']}', ";
//    $que1 .= "prnt_title 		= '{$check_data->name}', ";
//    $que1 .= "file_path 		= '/{$upload_direcoty_full_path}', ";
//    $que1 .= "prnt_yn		 	= 'Y', ";
//    $que1 .= "is_tag		 		= '1' ";
//    //echo $que1.'<br>';
//    mysqli_query($connection, $que1);
//    echo $upload_direcoty_full_path;
//} else {    // 실패
//    echo "Fail";
//}





?>