<?php
include "../include/top.php";

	$id=$_POST["gobeauty_id"];
	$phone=$_POST["gobeauty_phone"];
	$pwd=$_POST["gobeauty_pwd"];
	$name=$_POST["gobeauty_name"];

	if ($id == "") {
?>
<script>
$.MessageBox({
    buttonDone      : "확인",
    message         : "잘못된 접근입니다. 메인페이지로 이동합니다."
}).done(function(){
    location.href="<?=$mainpage_directory?>/index.php";
});
</script>
<?php
	} else {
	
		// 선가입 확인
		$login_insert_sql = "select * from tb_customer where id = '".$id."'";
		$result = mysql_query($login_insert_sql);

		if ($result_datas = mysql_fetch_object($result)) {
?>
			<script>
$.MessageBox({
    buttonDone      : "확인",
    message         : "이미 가입된 아이디입니다."
}).done(function(){
    location.href="<?=$login_directory?>/registration.php";
});
			</script>
<?php		
		}
		else
		{
			// 가입 처리
			$login_insert_sql = "insert into tb_customer (id,password,nickname,last_login_time,registration_time,push_option) values ('".$id."','".make_password_hash($pwd)."','".$name."','".date('Y-m-d  H:i:s')."','".date('Y-m-d  H:i:s')."',1)";
			$result = mysql_query($login_insert_sql);
		}
		closeDB();
?>

	<script language="javascript">
		<?if(!$result){?>
			location.href="registration_failure.php";
		<?}else{?>
			location.href="registration_success.php";
		 <?}?>
	</script>

<?php
	}
?>

<?php
include "../include/bottom.php";
?>
