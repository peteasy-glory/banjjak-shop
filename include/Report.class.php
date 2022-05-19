<?php
class Report {
	public $review_customer_id;
	public $report_customer_id;
        public $order_id;
        public $review_seq;
	public $report;
	public $is_delete = false;
	public $update_time;

        // 조회
        function is_report ($review_seq, $review_customer_id) {
                $sql = "select * from tb_usage_reviews where customer_id = '".$review_customer_id."' and review_seq = '".$review_seq."';";
                $result = mysql_query($sql);
                if ($row = mysql_fetch_object($result)) {
			if ($row->is_report == 1) {
	                        return 1;
			}
                }
                return 0;
        }

        // Report글 쓰기 
        function set_report ($review_seq, $review_customer_id, $report_customer_id, $report) {
                $sql = "update tb_usage_reviews set is_report = 1 where review_seq = '".$review_seq."' and customer_id = '".$review_customer_id."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
			$ssql = "insert into tb_usage_reviews_report (review_seq, review_customer_id, report_customer_id, report, is_delete, update_time) values ('".$review_seq."', '".$review_customer_id."', '".$report_customer_id."', '".$report."', 0, now());";
			$sresult = mysql_query($ssql);
                        return 1;
                } else {
                        return 0;
                }
        }

	// report 삭제 - 해제하기
	function delete_report ($review_seq, $review_customer_id) {
                $sql = "delete from tb_usage_reviews_report where review_customer_id = '".$review_customer_id."' and review_seq = '".$review_seq."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
			$ssql = "update tb_usage_reviews set is_report = 0 where review_seq = '".$review_seq."' and customer_id = '".$review_customer_id."';";
                        $sresult = mysql_query($ssql);
                        return 1;
                } else {
                        return 0;
                }
	}

        // 원본글 삭제하기
        function delete_review ($review_seq, $review_customer_id) {
                //$sql = "update tb_usage_reviews_report set is_delete = 1 where review_customer_id = '".$review_customer_id."' and order_id = '".$order_id."';";
                $sql = "delete from tb_usage_reviews_report where review_customer_id = '".$review_customer_id."' and review_seq = '".$review_seq."';";
                $result = mysql_query($sql);
                if (mysql_affected_rows() > 0) {
                        $ssql = "update tb_usage_reviews set is_delete = 1 where review_seq = '".$review_seq."' and customer_id = '".$review_customer_id."';";
                        $sresult = mysql_query($ssql);
                        return 1;
                } else {
                        return 0;
                }
        }

	// 값 가져오기
        function get_report ($review_seq, $review_customer_id) {
                $sql = "select * from tb_usage_reviews_report where review_seq = '".$review_seq."' and review_customer_id = '".$review_customer_id."';";
                $result = mysql_query($sql);
                if ($row = mysql_fetch_object($result)) {
			$this->review_customer_id = $row->review_customer_id;
			$this->report_customer_id = $row->report_customer_id;
                        $this->order_id = $row->order_id;
                        $this->review_seq = $row->review_seq;
			$this->report = $row->report;
			$this->is_delete = $row->is_delete;
			$this->update_time = $row->update_time;
                        return 1;
                } else {
                        return 0;
                }
        }
}
?>
