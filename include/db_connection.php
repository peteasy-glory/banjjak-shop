<?php
/*$db_conn = @mysql_connect("localhost","gobeautypet","pebjjdb!0901$") or  die("Can not connect to Database.");
$db = mysql_select_db("gobeautypet",$db_conn);
mysql_query("SET NAMES utf8");

function closeDB()
{
	mysql_close($GLOBALS["db_conn"]);
}*/

$connection = mysqli_connect("175.126.123.165","gobeautypet","pebjjdb!0901$") or die("서버 점검중 입니다.");
mysqli_select_db($connection,"gobeautypet");
mysqli_query($connection, "set names utf8");



?>
