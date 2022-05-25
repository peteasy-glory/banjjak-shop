<?php
class PetShopOpen {
        // db 에 저장
        function set_region ($top, $middle) {
                $sql = "update tb_shop set enable_flag = 1 where customer_id = '".$middle."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_region ($top, $middle) {
                $sql = "update tb_shop set enable_flag = 0 where customer_id = '".$middle."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
