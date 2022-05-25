<?php
	include "../include/top.php";
	
	$r_email = ($_GET["email"] && $_GET["email"] != "")? $_GET["email"] : "";
	$r_sub = ($_GET["sub"] && $_GET["sub"] != "")? $_GET["sub"] : "";
	$r_status = ($_GET["status"] && $_GET["status"] != "")? $_GET["status"] : "";
	$r_claims = ($_GET["result"] && $_GET["result"] != "")? $_GET["result"] : "";
?>

<div>
	<input type="text" name="apple_email" value="<?=$r_email ?>" />
	<input type="text" name="sub" value="<?=$r_sub ?>" />
	<input type="text" name="status" value="<?=$r_status ?>" />
	<input type="text" name="result" value="<?=$r_claims ?>" />
</div>