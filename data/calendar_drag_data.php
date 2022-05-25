<?php
ini_set('display_errors',1);
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");

$user_id = $_SESSION['gobeauty_user_id'];

print_r($_POST);

?>

