<?php
include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");

$que = "
    UPDATE tb_payment_log SET
    is_cancel = 1,
    cancel_time = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')
    WHERE is_reserve_pay = 1
    AND payment_log_seq > 600000 #부하 덜기 위함
    AND reserve_pay_yn = 0
    AND reserve_pay_deadline< DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')
";
//$result = mysqli_query($connection,$que);

?>