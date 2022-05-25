<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();

$user_id = $_SESSION['gobeauty_user_id'];

$payment_log_seq = ($_GET["payment_log_seq"]) ? $_GET["payment_log_seq"] : $_POST["payment_log_seq"];
$artist_id = $user_id;

$memo = $_POST["memo"];
$memo = $emoji->emojiStrToDB($memo);



//echo $memo;
$key = ($_GET["key"]) ? $_GET["key"] : $_POST["key"];

if ($key == "delete") {
    //echo "delete";
    $update_sql = "UPDATE tb_usage_reviews set artist_reply = '' 
                    WHERE artist_id = '" . $artist_id . "' 
                    AND payment_log_seq = '" . $payment_log_seq . "';";
    //echo $update_sql;
    $update_result = mysqli_query($connection, $update_sql);
    if ($update_result) {
?>
        <script language="javascript">
            location.href = "../shop_review_list";
        </script>
    <?php
    } else {
    ?>
        <script language="javascript">
            if(confirm("삭제를 하지 못했습니다. 문의 부탁드립니다.")){
                location.href = "../manage_my_postwrite_pc.php";
            }
        </script>
        <?php
    }
} else {
    //echo "update";
    $sql = "SELECT * FROM tb_usage_reviews WHERE artist_id = '" . $artist_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
    //echo $sql;
    $result = mysqli_query($connection, $sql);
    if ($result_datas = mysqli_fetch_object($result)) {
        $update_sql = "UPDATE tb_usage_reviews SET artist_reply = '" . trim($memo) . "' 
                        WHERE artist_id = '" . $artist_id . "' 
                        AND payment_log_seq = '" . $payment_log_seq . "';";
        //echo $update_sql;
        $update_result = mysqli_query($connection, $update_sql);
        ?>
            <script language="javascript">
                location.href = "../shop_review_list";
            </script>

 <?php
    }
}
?>

<?php
include "../include/bottom.php";
?>
