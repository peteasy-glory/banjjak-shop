<?php include "../include/top.php"; ?>
<?php include "../include/App.class.php"; ?>

<?php
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
        $is_android = 1;
	$login_insert_sql = "update tb_customer set token = '' where id = '".$_SESSION['gobeauty_user_id']."';";
	$result = mysql_query($login_insert_sql);
}

    session_start();
    session_destroy();

	//쿠키 전체 삭제(2019-06-21 hue)
	$past = time() - 3600;
	foreach ($_COOKIE as $key => $value)
	{
		setcookie($key, '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
	}
?>
<?php include "../include/bottom.php"; ?>

				
<script>
	window.webkit.messageHandlers.onAppLogout.postMessage('a');
</script>


<script>
	window.Android.onAppLogout('a');
</script>

<script>
$.MessageBox({
    buttonDone      : "확인",
    message         : "로그아웃 되었습니다. 메인페이지로 이동합니다."
}).done(function(){
	setCookie("order_num", "", -1); 
	setCookie("order_step", "", -1);
    location.href="<?=$login_directory?>/index_pc.php";
});

// 쿠키 생성
function setCookie(cName, cValue, cDay){
	var expire = new Date();
	expire.setDate(expire.getDate() + cDay);
	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; SameSite=None; Secure';
	document.cookie = cookies;
}
</script>

