<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];
$seq = intval($_POST['idx']);

$qry = "UPDATE tb_blog SET del_yn = 'Y'  WHERE customer_id = '{$user_id}' AND blog_seq= {$seq}";
$result = mysqli_query($connection, $qry);
if($result){
    echo true;
}else{
    echo false;
}
?>
