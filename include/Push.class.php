<?php
class Push {
        // 조회
        function is_push ($customer_id) {
            global $connection;
                $sql = "select * from tb_customer where id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if ($row = mysqli_fetch_object($result)) {
			if ($row->push_option == 1) {
	                        return 1;
			}
                }
                return 0;
        }

        // db 에 저장
        function set_push ($customer_id) {
            global $connection;
                $sql = "update tb_customer set push_option = 1 where id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows() > 0) {
                        return 1;
                } else {
                        return 0;
                }
        }

	// 삭제 
	function unset_push ($customer_id) {
        global $connection;
                $sql = "update tb_customer set push_option = 0 where id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows() > 0) {
                        return 1;
                } else {
                        return 0;
                }
	}
}
?>
