<?php
	if(!$_SESSION['gobeauty_user_id']){
		echo "<script>location.href = '/pet/login/index_pc.php';</script>";
		return false;
	}
?>