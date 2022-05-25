<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$user_id = $_SESSION['gobeauty_user_id'];

	$type = $_POST['type'];
	$artist_seq = $_POST['artist_seq'];
	$artist_id = $_POST['artist_id'];
	$artist_name = $_POST['artist_name'];

	$sql = "";
	$message = "";
    $json['flag'] = true;
	if($type == "write"){
		$sql = "INSERT INTO tb_shop_artist(customer_id, artist_id, name) VALUES('{$user_id}', '{$artist_id}', '{$artist_name}')";
	}else if($type == "modify"){
		$sql = "UPDATE tb_shop_artist SET name = '{$artist_name}' WHERE artist_seq = '{$artist_seq}' and del_yn = 'N'";
	}else if($type == "delete"){
		$sql = "UPDATE tb_shop_artist SET del_yn = 'Y' WHERE artist_seq = '{$artist_seq}'";
	}

	//echo $sql;
	$result = sql_query($sql);


	//실제 회원 정보에 반영
	if($result){
		if($type == "write"){
			$customer_sql = "UPDATE tb_customer SET artist_flag = 1 WHERE id = '{$artist_id}'"; 
			$customer_result = sql_query($customer_sql);
		}else if($type == "delete"){
			$customer_sql = "UPDATE tb_customer SET artist_flag = 0 WHERE id = '{$artist_id}'";
			//echo $customer_sql;
			$customer_result = sql_query($customer_sql);
		}
	} else {
	    $json['flag'] = false;
	    echo json_encode($json);
	    exit;
    }
    echo json_encode($json);
    exit;

?>