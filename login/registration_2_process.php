<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


$email_id = $_REQUEST["gobeauty_4_check_email"];
$email_id = strtolower($email_id);

$pwd_1 = $_REQUEST["gobeauty_pwd"];
$pwd_2 = $_REQUEST["gobeauty_pwd_ck"];
$nickname = $_REQUEST["gobeauty_nickname"];



$random_number = sprintf("%06d", rand(000000, 999999));
$id_pos = strpos($email_id, "@");
if ($id_pos > 5) {
    $nickname = substr($email_id, 0, $id_pos - 3) . "_" . $random_number;
} else {
    $nickname = substr($email_id, 0, $id_pos) . "_" . $random_number;
}
$_SESSION["gobeauty_login_id_t"] = $email_id;

//if (!$email_id || !$pwd_1 || !$pwd_2 || !$nickname || !$_SESSION["gobeauty_certification_number"]) {

include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

if (!$email_id || !$pwd_1 || !$pwd_2 || !$nickname) {
?>
    <script language="javascript">
        popalert.back('firstRequestMsg1',"값을 확인후, 재시도해주세요.");
    </script>
<?php
	exit;
}

if (strcmp($pwd_1, $pwd_2)) {
?>
    <script language="javascript">
        popalert.back('firstRequestMsg1',"패스워드가 다릅니다. 값을 확인후 재시도해주세요.");
        
    </script>
<?php
	exit;
}

$login_insert_sql = "select * from tb_customer where id = '" . $email_id . "'";
$result = mysqli_query($connection, $login_insert_sql);
if ($result_datas = mysqli_fetch_object($result)) {
?>
    <script language="javascript">
	    popalert.back('firstRequestMsg1',"이미 가입된 아이디 입니다. 값을 확인후 재시도해주세요.");

    </script>
    <?php
	    exit;
} else {

    $login_insert_sql = "INSERT INTO tb_customer (
        id,
        password,
        nickname,
        last_login_time,
        registration_time,
        push_option,
        cellphone
        ) values (
            '{$email_id}',
            '" . make_password_hash($pwd_1) . "',
            '{$nickname}',
            '" . date('Y-m-d  H:i:s') . "',
            '" . date('Y-m-d  H:i:s') . "',
            1, 
            '" . $_SESSION["gobeauty_regist_cellphone"] . "'
            )";
    $result = mysqli_query($connection, $login_insert_sql);

    if (!$result) {
    ?>
        <script language="javascript">
	        popalert.open('firstRequestMsg1',"가입 실패. 값을 확인후 재시도해주세요.");
        </script>
    <?php
	    exit;
    } else {
        $crypto = new Crypto();

        $cellphone = $crypto->decode(trim($_SESSION["gobeauty_regist_cellphone"]), $access_key, $secret_key);

        //주문에 아이디 넣기
        $payment_update_query = "UPDATE tb_payment_log SET customer_id = '{$email_id}' WHERE cellphone = '{$cellphone}' AND (customer_id IS NULL OR customer_id = '')";
        mysqli_query($connection, $payment_update_query);

        //임시회원 찾기
        $tmp_user_query = "SELECT * FROM tb_tmp_user WHERE cellphone = '{$cellphone}'";
        $tmp_user_result = mysqli_query($connection, $tmp_user_query);

        while ($tmp_user_data = mysqli_fetch_object($tmp_user_result)) {
            $tmp_seq = $tmp_user_data->tmp_seq;
            //펫 소유 변경하기
            $pet_update_query = "UPDATE tb_mypet SET customer_id = '{$email_id}', tmp_yn = 'N' WHERE tmp_seq = '{$tmp_seq}'";
            $result1 = mysqli_query($connection, $pet_update_query);

            //쿠폰 소유 변경하기
            $coupon_update_query = "UPDATE tb_user_coupon SET customer_id = '{$email_id}' WHERE tmp_seq = '{$tmp_seq}'";
            $result2 = mysqli_query($connection, $coupon_update_query);

            $history_update_query = "UPDATE tb_coupon_history SET customer_id = '{$email_id}' WHERE tmp_seq = '{$tmp_seq}'";
            $result3 = mysqli_query($connection, $history_update_query);

            //임시회원 삭제하기
            if ($result1 && $result2 && $result3) {
                $delete_query = "DELETE FROM tb_tmp_user WHERE tmp_seq = '{$tmp_seq}' AND cellphone = '{$cellphone}'";
                $delete_result = mysqli_query($connection, $delete_query);

                if ($delete_result) {
                    //임시회원 흔적 삭제하기
                    $tmp_query = "UPDATE tb_mypet SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection, $tmp_query);

                    $tmp_query = "UPDATE tb_user_coupon SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection, $tmp_query);

                    $tmp_query = "UPDATE tb_coupon_history SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection, $tmp_query);
                }
            }
        }
    ?>
        <script language="javascript">
            location.href = "/join4";
        </script>
<?php
    }
}


include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer.php");
?>