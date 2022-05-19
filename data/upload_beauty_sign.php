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

if($r_sign_image != ""){
	// folder create
	$mkdir = $upload_static_directory2.$sign_directory2."/".$r_artist_id."/";
	if (!is_dir($mkdir)) {
		mkdir($mkdir);
		chmod($mkdir, 0777);
	}

	$sign_image_url = $sign_directory2."/".$r_artist_id."/".$filename.".png"; // 확장자 고정 저장
	$img = base64_decode(str_replace('data:image/png;base64,', '', $r_sign_image));
	file_put_contents($upload_static_directory2.$sign_image_url, $img);

	$return_data = $sign_image_url;
}

echo $return_data;

?>