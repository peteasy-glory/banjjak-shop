<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$find_passwd = isset($_GET['find_passwd']) ? $_GET['find_passwd'] : "";

$sql = "update tb_customer set password = '".make_password_hash($_REQUEST['gobeauty_pwd'])."' where id = '".$user_id."';";
$result = mysqli_query($connection, $sql);
?>
<script>
    var find_passwd = "<?=$find_passwd?>";
    // $.MessageBox({
    //     buttonDone      : "확인",
    //     message         : "변경되었습니다."
    // }).done(function(){
    //         location.href = 'manage_setting.php';
    // });

    alert("변경되었습니다.");
    if(find_passwd == 1){
        location.href = "../login_shop";
    }else{
        location.href = "../home_main";
    }
</script>
