<?php
	include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$user_id = $_SESSION['gobeauty_user_id'];

	//유효성
	if(empty($user_id)){
		//실패
		$arr = array(
			"ret" => false,
			"msg" => "실패",
		);
		echo(json_encode($arr));
		exit();
	}

	//회원정보
	$sql = "UPDATE tb_customer
	        SET enable_flag = 0
		    WHERE id = '{$user_id}'";
	$result = mysqli_query($connection, $sql);

	//[미구현]주문이력

	//[미구현]단골

	//[미구현]쿠폰

	if($result){
		//성공
		$arr = array(
			"ret" => true,
			"msg" => "성공",
		);
		echo(json_encode($arr));
		exit();
	}else{
		//실패
		$arr = array(
			"ret" => false,
			"msg" => "실패",
		);
		echo(json_encode($arr));
		exit();
	}
?>