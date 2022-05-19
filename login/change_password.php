<?php
include "../include/top.php";

/*echo $_REQUEST['customer_id'];
echo $_REQUEST['gobeauty_4_check_email'];
echo $_REQUEST['gobeauty_pwd'];
echo $_REQUEST['gobeauty_pwd_ck'];
*/

if (trim($_REQUEST['customer_id']) != trim($_REQUEST['gobeauty_4_check_email'])) {
?>
	<script>
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "아이디를 확인해주세요."
                }).done(function(){
			location.href = 'find_id_password.php';
                });
	</script>
<?
	return;
}

$sql = "update tb_customer set password = '".make_password_hash($_REQUEST['gobeauty_pwd'])."' where id = '".$_REQUEST['customer_id']."';";
$result = mysql_query($sql);
?>
        <script>
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "변경되었습니다."
                }).done(function(){
                        location.href = 'index.php';
                });
        </script>
<?
include "../include/bottom.php";
?>
