<?php
class PetProduct {
	public $customer_id;
	public 
        // 조회
        function set ($customer_id, $artist_id) {
                $sql = "select * from tb_like_artist where customer_id = '".$customer_id."' and artist_id = '".$artist_id."';";
                $result = mysql_query($sql);
                if ($row = mysql_fetch_object($result)) {
                        return true;
                } else {
	                return false;
		}
        }

        // db 에 저장
        function set_heart ($customer_id, $artist_id) {
                $sql = "insert into tb_like_artist (customer_id, artist_id, update_time) value ('".$customer_id."', '".$artist_id."', now());";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_heart ($customer_id, $artist_id) {
		$sql = "delete from tb_like_artist where customer_id = '".$customer_id."' and artist_id = '".$artist_id."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
