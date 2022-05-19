<?php

class Heart {
    
        // 조회
        function is_like_artist ($customer_id, $artist_id) {
            global $connection;
                $sql = "select * from tb_like_artist where customer_id = '".$customer_id."' and artist_id = '".$artist_id."';";
                $result = mysqli_query($connection,$sql);
                if ($row = mysqli_fetch_object($result)) {
                        return true;
                } else {
	                return false;
		}
        }

        // db 에 저장
        function set_heart ($customer_id, $artist_id) {
            global $connection;
                $sql = "insert into tb_like_artist (customer_id, artist_id, update_time) value ('".$customer_id."', '".$artist_id."', now());";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_heart ($customer_id, $artist_id) {
        global $connection;
		$sql = "delete from tb_like_artist where customer_id = '".$customer_id."' and artist_id = '".$artist_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
