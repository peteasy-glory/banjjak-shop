<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/ShopSetting.class.php");


$push = new ShopSetting();
$setting_paytype = $_POST['setting_paytype'];
$user_id = $_SESSION['gobeauty_user_id'];



/*
$is_push = $push->is_push($user_id);
if ($is_push == 1) {
	$push->unset_push($user_id);
} else {
	$push->set_push($user_id);
}
*/

if ($setting_paytype == 1) {
	$push->set_push($user_id);
} else if($setting_paytype == 0) {
	$push->unset_push($user_id);
} else if($setting_paytype == 2){
	$push->set_push_2($user_id);
}
?>

