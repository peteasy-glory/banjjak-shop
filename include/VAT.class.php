<?php
class VAT {
        // 조회
        function is_vat ($artist_id) {
            global $connection;
                $sql = "select is_vat from tb_shop where customer_id = '".$artist_id."';";
                $result = mysqli_query($connection, $sql);
                if ($row = mysqli_fetch_object($result)) {
                        return $row->is_vat;
                } else {
	                return false;
		}
        }

        // db 에 저장
        function set_vat ($artist_id) {
            global $connection;
                $sql = "update tb_shop set is_vat = true where customer_id = '".$artist_id."';";
                $result = mysqli_query($connection,$sql);
                if (mysqli_affected_rows($connection) > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_vat ($artist_id) {
        global $connection;
                $sql = "update tb_shop set is_vat = false where customer_id = '".$artist_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows($connection) > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
