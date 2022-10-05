<?php
include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");

$que = "
    UPDATE tb_payment_log SET
    is_cancel = 1,
    cancel_time = NOW()
    WHERE is_reserve_pay = 1
    AND payment_log_seq > 600000 #부하 덜기 위함
    AND reserve_pay_yn = 0
    AND reserve_pay_deadline < NOW()
";
$result = mysqli_query($connection,$que);

?>