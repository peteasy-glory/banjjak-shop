<?php
class Operator {
        // db 에 저장
        function set_region ($top, $middle) {
                $sql = "update tb_customer set operator_flag = 1 where id = '".$middle."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_region ($top, $middle) {
                $sql = "update tb_customer set operator_flag = 0 where id = '".$middle."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
