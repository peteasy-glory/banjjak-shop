<?php

ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$pet_seq = $_POST['pet_seq'];

$check_query = "SELECT * FROM tb_mypet WHERE pet_seq = '{$pet_seq}'";
$check_result = mysqli_query($connection, $check_query);
$check_data = mysqli_fetch_object($check_result);

$user_id = "";
if($check_data->tmp_yn == "Y"){
	$user_id = "tmp_".$check_data->tmp_seq;
}else{
	$user_id = $check_data->customer_id;
}

make_user_directory($upload_static_directory2.$upload_directory2, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');


// 변수 정리
$filepath_appuploaded = "";
if($_FILES['myfile']['name']) { // ios/pc용 
    $error = $_FILES['myfile']['error'];
    $name = $_FILES['myfile']['name'];
} else { // android
	//$_POST['myfile'] = 'uywkx_202203221039312.jpg';
    $name = $_POST['myfile'];
    $name = trim($name);    
    // android app 업로드용 파일 경로
    $filepath_appuploaded = $upload_static_directory . "/upload/appupload/" . $name;
}
//$_POST['filepath'] = 'pettester@peteasy.kr/pet_gallery_1.jpg';
$new_filename = $_POST['filepath'];
$ext = array_pop(explode('.', $name));



// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
 
// 확장자 확인
if( !in_array(strtolower($ext), $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}
 
$upload_direcoty_full_path = $upload_directory."/".$new_filename;

if(file_exists($filepath_appuploaded)) {    // android용

    if(!copy($filepath_appuploaded, $upload_static_directory.'/'.$upload_direcoty_full_path)){
    	echo '파일복사실패';
	} else {
		$s3->resizeImage($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_static_directory.'/'.$upload_direcoty_full_path);
		$s3->fileToS3($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_direcoty_full_path);
		@unlink($filepath_appuploaded);
	}
} else {    // pc / iphone용
	//make_user_directory(upload_static_directory.'/'.$upload_direcoty_full_path, $user_id);
//	make_user_directory($upload_static_directory2.$upload_directory2, );
	move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.'/'.$upload_direcoty_full_path);

	$s3->resizeImage($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_static_directory.'/'.$upload_direcoty_full_path);
	$s3->fileToS3($upload_static_directory.'/'.$upload_direcoty_full_path, $upload_direcoty_full_path);
}

$payment_log_seq = strlen(trim($_POST['payment_log_seq']));
if($payment_log_seq < 1){
	$payment_log_seq = 0;
}

if($pet_seq > 0){   // 이미지 성공 저장
    $que1  = "INSERT INTO tb_mypet_beauty_photo SET ";
    $que1 .= "payment_log_seq 	= '{$payment_log_seq}', ";
	$que1 .= "artist_id 		= '{$_POST['artist_id']}', ";
	$que1 .= "pet_seq 			= '{$_POST['pet_seq']}', ";
	$que1 .= "prnt_title 		= '{$check_data->name}', ";
	$que1 .= "file_path 		= '/{$upload_direcoty_full_path}', ";
	$que1 .= "prnt_yn		 	= 'Y', ";
	$que1 .= "is_tag		 		= '1' ";
	//echo $que1.'<br>';
	mysqli_query($connection, $que1);
    echo $upload_direcoty_full_path;
} else {    // 실패
    echo "Fail";
}
?>
