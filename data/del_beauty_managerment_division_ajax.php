<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];


$json['flag'] = true;
$que = "UPDATE tb_product_dog_worktime SET";
for($i=10;$i<=14;$i++) {
    $que .= "worktime{$i}           = NULL, ";
    $que .= "worktime{$i}_title     = NULL, ";
    $que .= "worktime{$i}_disp_yn   = 'n' ";
}
$que .= "   WHERE artist_id = '{$user_id}' ";
$res = mysqli_query($connection, $que);
$rs = mysqli_fetch_assoc($res);


$que = "UPDATE tb_product_dog_worktime SET  worktime{$_POST['no']} = NULL, worktime{$_POST['no']}_title = NULL, worktime{$_POST['no']}_disp_yn = 'n' WHERE artist_id = '{$user_id}' ";
//echo $que."<br>";
$res = mysqli_query($connection, $que);
$rs = mysqli_fetch_assoc($res);

echo json_encode($json);

?>

