<?php
//include "../include/configure.php";

//include "../include/session.php";
//include "../include/db_connection.php";
//include "../include/php_function.php";
//include "../include/Crypto.class.php";
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


$crypto = new Crypto();

$auth_code = $_POST["gobeauty_2_check_cellphone"];
$enc_cellphone = $_SESSION["gobeauty_regist_cellphone"];
$cellphone = $crypto->decode($enc_cellphone, $access_key, $secret_key);
$user_id = $_SESSION['gobeauty_user_id'];

if (($_SESSION["gobeauty_certification_number"] == $auth_code) && ($cellphone != NULL && $cellphone != "")) {
    //회원정보에 핸드폰 번호 넣기
    $customer_update_query = "UPDATE tb_customer SET cellphone = '{$enc_cellphone}' WHERE id = '{$user_id}' AND (cellphone IS NULL OR cellphone = '')";
    $customer_result = mysqli_query($connection,$customer_update_query);

    if ($customer_result) {
        //주문에 아이디 넣기
        $payment_update_query = "UPDATE tb_payment_log SET customer_id = '{$user_id}' WHERE cellphone = '{$cellphone}' AND (customer_id IS NULL OR customer_id = '')";
        mysqli_query($connection,$payment_update_query);

        //임시회원 찾기
        $tmp_user_query = "SELECT * FROM tb_tmp_user WHERE cellphone = '{$cellphone}'";
        $tmp_user_result = mysqli_query($connection,$tmp_user_query);

        while ($tmp_user_data = mysqli_fetch_object($tmp_user_result)) {
            $tmp_seq = $tmp_user_data->tmp_seq;
            //펫 소유 변경하기
            $pet_update_query = "UPDATE tb_mypet SET customer_id = '{$user_id}', tmp_yn = 'N' WHERE tmp_seq = '{$tmp_seq}'";
            $result1 = mysqli_query($connection,$pet_update_query);

            //쿠폰 소유 변경하기
            $coupon_update_query = "UPDATE tb_user_coupon SET customer_id = '{$user_id}' WHERE tmp_seq = '{$tmp_seq}'";
            $result2 = mysqli_query($connection,$coupon_update_query);

            $history_update_query = "UPDATE tb_coupon_history SET customer_id = '{$user_id}' WHERE tmp_seq = '{$tmp_seq}'";
            $result3 = mysqli_query($connection,$history_update_query);

            //임시회원 삭제하기
            if ($result1 && $result2 && $result3) {
                $delete_query = "DELETE FROM tb_tmp_user WHERE tmp_seq = '{$tmp_seq}' AND cellphone = '{$cellphone}'";
                $delete_result = mysqli_query($connection,$delete_query);

                if ($delete_result) {
                    //임시회원 흔적 삭제하기
                    $tmp_query = "UPDATE tb_mypet SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection,$tmp_query);

                    $tmp_query = "UPDATE tb_user_coupon SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection,$tmp_query);

                    $tmp_query = "UPDATE tb_coupon_history SET tmp_seq = NULL WHERE tmp_seq = '{$tmp_seq}'";
                    mysqli_query($connection,$tmp_query);
                }
            }
        }
    }
    ?>
    <script language="javascript">
        location.href = "<?php echo "../home_main" ?>/";
    </script>
<?php
} else {
    ?>
    <script language="javascript">
      alert("인증에 실패했습니다. 재시도해주세요.");
      location.href = "naver_cellphone.php";
    </script>
<?php
}
?>
