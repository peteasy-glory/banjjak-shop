<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include ($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$r_ba_seq = $_POST['ba_seq'];
$r_cellphone = $_POST['cellphone'];
$resultData = array();

$query = "
	SELECT ba.*, mp.name as petName, sh.name as shopName
    FROM tb_beauty_agree AS ba
		INNER JOIN tb_mypet AS mp ON mp.pet_seq = ba.pet_id
		INNER JOIN tb_shop AS sh ON sh.customer_id = ba.artist_id
	WHERE ba_seq = '{$r_ba_seq}'
";
$result = mysqli_query($connection,$query);
$data = mysqli_fetch_object($result);

if(isset($data) && $data != null && $data->is_notice != "Y"){
    $talk = new Allimtalk();

    $talk->cellphone = $r_cellphone;

    $talkCustomerName = $data->customer_name;
    $talkPetName = $data->petName;
    $talkShopName = $data->shopName;
	$talkDate = DATE("y년 m월 d일 H시 i분", STRTOTIME($data->reg_date));
	$btnLink = "https://gopet.kr/pet/docba/?k=".$r_ba_seq;

    $result = $talk->sendBeautyAgree_new($talkCustomerName, $talkPetName, $talkShopName, $talkDate, $btnLink);
    $resultData["result"] = $result;

    if($result){
        $update = "UPDATE tb_beauty_agree SET is_notice = 'Y', cellphone = '".$r_cellphone."' WHERE ba_seq = '".$r_ba_seq."'";
        mysqli_query($connection,$update);
    }
}else{
    $resultData["result"] = false;
}

echo json_encode($resultData, JSON_UNESCAPED_UNICODE);
?>
