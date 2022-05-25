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
		location.href="/login_shop";
	</script>

<?php
		exit;
	}elseif(isset($_SESSION['gobeauty_user_id']) && $_SESSION['my_shop_flag']!='1' && $_SESSION['artist_flag']!=true) {
		?>
		<script>
		location.href="/join5";
		</script>
		<?
		exit;
	}
?>
