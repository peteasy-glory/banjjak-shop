<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

function strToImoji($arg){
    $arg = preg_replace_callback('/[\x{010000}-\x{10ffff}]/u', function($v){
        return ''.current(unpack('N',iconv('UTF-8', 'UCS-4', $v[0]))).';';
    }, $arg);
    return addslashes($arg);
}
	$user_id = $_SESSION['gobeauty_user_id'];
	make_user_directory ($upload_static_directory2.$upload_directory2, $user_id);

	$license_photo = $_POST["license_photo"];
	$license_name = strToImoji($_POST["license_name"]);
//	$license_issue_date = $_POST["license_issue_date"];
	$license_issue_place=strToImoji($_POST["license_issue_place"]);
	$new_filename = $_POST["filepath"];

	$s_year = $_POST["s_year"];
	$s_month = $_POST["s_month"];
	$s_day = $_POST["s_day"];
	$license_issue_date = $s_year."-".$s_month."-".$s_day;

	$upload_direcoty_full_path = $new_filename;

	$sql = "insert into tb_award (customer_id, photo, name, issue_date, issue_place, update_time, enable_flag) values ('".$user_id."', '".$upload_direcoty_full_path."', '".$license_name."', '".$license_issue_date."', '".$license_issue_place."', now(), true);";
	//echo $sql;

	$result = mysqli_query($connection, $sql);
?>
<script language="javascript">
		location.href="../shop_management";
	</script>
