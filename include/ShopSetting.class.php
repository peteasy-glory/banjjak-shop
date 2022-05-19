<?php
class ShopSetting {
        // 조회
        function is_push ($customer_id) {
	        	global $connection;
                $sql = "select * from tb_shop where customer_id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if ($row = mysqli_fetch_object($result)) {
			if ($row->is_setting_pay_in_shop == 1) {
	                        return 1;
			}
                }
                return 0;
        }

        // db 에 저장
        function set_push ($customer_id) {
	        global $connection;
                $sql = "update tb_shop set is_setting_pay_in_shop = 1 where customer_id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows($connection) > 0) {
                        return 1;
                } else {
                        return 0;
                }
        }

	// 삭제 
	function unset_push ($customer_id) {
		global $connection;
                $sql = "update tb_shop set is_setting_pay_in_shop = 0 where customer_id = '".$customer_id."';";
                $result = mysqli_query($connection, $sql);
                if (mysqli_affected_rows($connection) > 0) {
                        return 1;
                } else {
                        return 0;
                }
	}
	
	//----- 결제방식 : 매장결제만 활성화
	function set_push_2 ($customer_id) {
		global $connection;
			$sql = "update tb_shop set is_setting_pay_in_shop = 2 where customer_id = '".$customer_id."';";
			$result = mysqli_query($connection, $sql);
			if (mysqli_affected_rows($connection) > 0) {
					return 1;
			} else {
					return 0;
			}
	}
}
?>
