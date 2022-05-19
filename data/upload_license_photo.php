<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

// 사용자 이미지 업로드 디렉토리 생성
make_user_directory($upload_static_directory2.$upload_directory2, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');
 
// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

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
 
// 파일 이동
$upload_direcoty_full_path = $upload_directory2."/".$new_filename;
move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory2.$upload_direcoty_full_path);

$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$s3->resizeImage($upload_static_directory2.$upload_direcoty_full_path, $upload_static_directory2.$upload_direcoty_full_path);
$s3->fileToS3($upload_static_directory2.$upload_direcoty_full_path, $upload_directory."/".$new_filename);


?>
{"upfilename": "<?=$upload_direcoty_full_path?>","allpath": "<?="https://image.banjjakpet.com".$upload_direcoty_full_path?>","msg": ""}

