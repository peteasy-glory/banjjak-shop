<?php
	if(!isset($_SESSION['gobeauty_user_id']) || !isset($_SESSION['gobeauty_user_nickname'])) {
?>

	<script>
		/*
		$.MessageBox({
			buttonDone      : "확인",
			message         : "<center><b>로그인이 필요한 메뉴입니다</b><br>(로그인 페이지로 이동합니다)</center>"
		}).done(function(){
			location.href="<?=$login_directory?>/index.php";
		});
		*/
		//location.href="/login";
		location.href="/mypage_nomember";
	</script>

<?php
		exit;
	}
?>
