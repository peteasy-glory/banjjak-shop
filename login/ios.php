<?php
include "../include/top.php";

$id = $_REQUEST['id'];
$token = $_REQUEST['token'];

$sql = "update tb_customer set token = '".$token."', is_android = 2 , last_applogin_time=now() where id = '".$id."';"; // 20210701 by migo - last_applogin_time 최신 앱로그인 시각 추가
$result = mysql_query($sql);

?>


<?php
include "../include/bottom.php";
?>