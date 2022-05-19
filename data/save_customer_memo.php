<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$r_scm_seq = ($_POST["scm_seq"] && $_POST["scm_seq"] != "")? $_POST["scm_seq"] : "";
$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
$r_tmp_seq = ($_POST["tmp_seq"] && $_POST["tmp_seq"] != "")? $_POST["tmp_seq"] : "";
$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
$r_memo = ($_POST["memo"] != "")? $_POST["memo"] : "";

if($r_artist_id != "" && $r_cellphone != "" && ($r_customer_id != "" || $r_tmp_seq != "")){
    if($r_scm_seq != ""){ // update
        $sql = "
						UPDATE tb_shop_customer_memo SET
							memo = '".addslashes($r_memo)."',
							update_dt = NOW()
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
							AND scm_seq = '".$r_scm_seq."'
					";
    }else{
        $sql = "
						SELECT *
						FROM tb_shop_customer_memo
						WHERE artist_id = '".$r_artist_id."'
							AND customer_id = '".$r_customer_id."'
							AND tmp_seq = '".$r_tmp_seq."'
							AND is_delete = '2'
					";

        $result = mysqli_query($connection,$sql);
        $memo_cnt = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);

        if($memo_cnt == 0){
            $sql = "
							INSERT INTO tb_shop_customer_memo (
								`customer_id`, `tmp_seq`, `cellphone`, `artist_id`, `memo`
							) VALUES (
								'".$r_customer_id."', '".$r_tmp_seq."', '".$r_cellphone."', '".$r_artist_id."', '".$r_memo."'
							)
						";
        }else{
            $sql = "
							UPDATE tb_shop_customer_memo SET
								memo = '".addslashes($r_memo)."',
								update_dt = NOW()
							WHERE is_delete = '2'
								AND artist_id = '".$r_artist_id."'
								AND scm_seq = '".$row["scm_seq"]."'
						"; // 회원 정보가 있지만 seq값이 없을때 조회하여 update 처리
        }
    }
    $result = mysqli_query($connection,$sql);
    if($result){
        $scm_seq = ($r_scm_seq != "")? $r_scm_seq : mysqli_insert_id();
        $return_data = array("code" => "000000", "data" => $scm_seq);
    }else{
        $return_data = array("code" => "000001", "data" => "메모가 적용되지 않았습니다.".$sql);
    }
}else{
    $return_data = array("code" => "999997", "data" => "중요 데이터가 누락되었습니다.".$r_artist_id."/".$r_customer_id."/".$r_tmp_seq."/".$r_cellphone);
}

if($result) {
    echo "ok";
}
?>
