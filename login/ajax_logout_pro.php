<?php include "../include/configure.php"; ?>

<?php include "../include/session.php"; ?>
<?php include "../include/db_connection.php"; ?>
<?php include "../include/php_function.php"; ?>
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

<script>
	window.webkit.messageHandlers.onAppLogout.postMessage('a');
</script>


<script>
	window.Android.onAppLogout ('a');
</script>

<script>
$.MessageBox({
    buttonDone      : "확인",
    message         : "로그아웃 되었습니다. 메인페이지로 이동합니다."
}).done(function(){
    location.href="<?=$mainpage_directory?>/index.php";
});
</script>