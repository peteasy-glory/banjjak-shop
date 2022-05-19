<?php
class Region {
        // db 에 저장
        function set_region ($top, $middle) {
                $sql = "update tb_region set open_flag = 1 where TRIM(top) = TRIM('".$top."') and TRIM(middle) = TRIM('".$middle."');";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
        }

	// 삭제 
	function unset_region ($top, $middle) {
                $sql = "update tb_region set open_flag = 0 where TRIM(top) = TRIM('".$top."') and TRIM(middle) = TRIM('".$middle."');";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        return true;
                } else {
                        return false;
                }
	}
}
?>
