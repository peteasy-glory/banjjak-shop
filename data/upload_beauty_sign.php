<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$r_sign_image = $_POST["data"];
$r_artist_id = $_POST["artist_id"];
$r_customer_id = $_POST["customer_id"];
$r_tmp_seq = $_POST["tmp_seq"];
$r_cellphone = $_POST["cellphone"];
$r_tmp_yn = $_POST["tmp_yn"];

if($r_tmp_yn == "N"){
	$filename = $r_customer_id;
}else{
	$filename = "tmp_".$r_tmp_seq;
}
// 사용자 이미지 업로드 디렉토리 생성
make_user_directory($upload_static_directory2.$sign_directory2, $r_artist_id);

if($r_sign_image != ""){
	// folder create
    /*
	$mkdir = $upload_static_directory2.$sign_directory2."/".$r_artist_id."/";
	if (!is_dir($mkdir)) {
		mkdir($mkdir);
		chmod($mkdir, 0777);
	}
    */

	$sign_image_url = $sign_directory2."/".$r_artist_id."/".$filename.".png"; // 확장자 고정 저장
	$img = base64_decode(str_replace('data:image/png;base64,', '', $r_sign_image));
	file_put_contents($upload_static_directory2.$sign_image_url, $img);

    $s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
    $s3->resizeImage($upload_static_directory2.$sign_image_url, $upload_static_directory2.$sign_image_url);
    $s3->fileToS3($upload_static_directory2.$sign_image_url, $sign_directory."/".$r_artist_id."/".$filename.".png");

	$return_data = $sign_image_url;
}

echo $return_data;

?>
