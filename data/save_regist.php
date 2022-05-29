<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
//include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

if($_POST["is_personal"] && $_POST["is_personal"] != "" && $_POST["is_personal"] == 1){
    $is_business = 0;
    $is_personal = 1;
}else{
    $is_business = 1;
    $is_personal = 0;
}
$business_number = ($_POST["business_number"] && $_POST["business_number"] != "")? $_POST["business_number"] : "";
$business_photo = ($_POST["business_photo"] && $_POST["business_photo"] != "")? $_POST["business_photo"] : "";

$name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
$cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
$top_region = ($_POST["top_region"] && $_POST["top_region"] != "")? $_POST["top_region"] : "";
$middle_region = ($_POST["middle_region"] && $_POST["middle_region"] != "")? $_POST["middle_region"] : "";
$offline = ($_POST["offline"] && $_POST["offline"] != "" && $_POST["offline"] == 1)? $_POST["offline"] : "";
$zipcode = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "";
$lat = ($_POST["lat"] && $_POST["lat"] != "")? $_POST["lat"] : "";
$lng = ($_POST["lng"] && $_POST["lng"] != "")? $_POST["lng"] : "";
$region = ($_POST["shop_area"] && $_POST["shop_area"] != "")? $_POST["shop_area"] : "";
$business_addr = ($_POST["business_addr"] && $_POST["business_addr"] != "")? $_POST["business_addr"] : "";
$business_addr_detail = ($_POST["business_addr_detail"] && $_POST["business_addr_detail"] != "")? $_POST["business_addr_detail"] : "";
$offline_shop_name = ($_POST["shop_name"] && $_POST["shop_name"] != "")? $_POST["shop_name"] : "";
$offline_shop_phonenumber = ($_POST["shop_cellphone"] && $_POST["shop_cellphone"] != "")? $_POST["shop_cellphone"] : "";

$cate1 = ($_POST["cate1"] && $_POST["cate1"] != "")? "1," : "";
$cate2 = ($_POST["cate2"] && $_POST["cate2"] != "")? "2," : "";
$cate3 = ($_POST["cate3"] && $_POST["cate3"] != "")? "3," : "";
$cate = substr($cate1.$cate2.$cate3 , 0, -1);

$working_years = 0;


$shop_address = $zipcode."|".$business_addr." ".$business_addr_detail;
//make_user_directory ($upload_static_directory2.$upload_directory2, $user_id);
//
//$license_photo = $_POST["license_photo"];
//$license_name = $_POST["license_name"];
////	$license_issue_date = $_POST["license_issue_date"];
//$license_issue_place=$_POST["license_issue_place"];
//$new_filename = $_POST["filepath"];
//
//$s_year = $_POST["s_year"];
//$s_month = $_POST["s_month"];
//$s_day = $_POST["s_day"];
//$license_issue_date = $s_year."-".$s_month."-".$s_day;
//
//$upload_direcoty_full_path = $new_filename;

$sql = "
    INSERT INTO `tb_request_artist` 
        (`customer_id`, `step`, `name`, `cellphone`, `is_personal`, `is_business`, `business_number`, `business_license`, `region`, `professional`, `is_got_offline_shop`, 
         `offline_shop_name`, `offline_shop_phonenumber`, `offline_shop_address`, `update_time`, `working_years`, `lat`, `lng`, `enter_path`, `choice_service`) 
    VALUES 
        ('".$user_id."', 5, '".$name."', '".$cellphone."', '".$is_personal."', '".$is_business."', '".$business_number."', '".$business_photo."', '".$top_region.":".$middle_region."', '', '".$offline."', 
         '".$offline_shop_name."', '".$offline_shop_phonenumber."', '".$shop_address."', NOW(), '".$working_years."', '".$lat."', '".$lat."', '', '".$cate."');

";

$result = mysqli_query($connection, $sql);
if($result){
    echo "success";
}else{
    echo $sql;
}
?>

