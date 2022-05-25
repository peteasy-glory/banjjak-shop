<?php
class Point
{
    public $is_load = false;
    public $customer_id;
    public $accumulate_point;
    public $purchase_point;
    public $update_time;

    // 포인트 조회
    function select_point($customer_id)
    {
        global $connection;
        $sql = "select * from tb_point where customer_id = '" . $customer_id . "';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $this->customer_id = $row->customer_id;
            $this->accumulate_point = $row->accumulate_point;
            $this->purchase_point = $row->purchase_point;
            $this->update_time = $row->update_time;
            $this->is_load = true;
            return true;
        } else {
            $sql = "insert into tb_point (customer_id, accumulate_point, purchase_point, update_time) values ('" . $customer_id . "', 0, 0, now());";
            $result = mysqli_query($connection,$sql);
            $this->customer_id = $customer_id;
            $this->accumulate_point = '0';
            $this->purchase_point = '0';
            $this->update_time = date('Y-m-d H:i:s');
            $this->is_load = true;
            return true;
        }
        return false;
    }

    // db 에 저장
    function update_point()
    {
        global $connection;
        $sql = "UPDATE tb_point 
                SET accumulate_point = '" . $this->accumulate_point . "', 
                purchase_point = '" . $this->purchase_point . "', 
                update_time = NOW() 
                WHERE customer_id = '" . $this->customer_id . "';";
        // error_log('----- update_point / $sql : ' . $sql);
        $result = mysqli_query($connection,$sql);
        if (mysqli_affected_rows($connection) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // 이벤트 시에 이미 참가한 이벤트인지 검사한다. 이미 포인트 가능여부 true면 적립, false 면 불가
    function is_able_to_event_point($event_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql = "select * from tb_point_history where customer_id = '" . $this->customer_id . "' and event_name = '" . $event_id . "' and type = 'EVENT';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            return false;
        } else {
            return true;
        }
    }

    // history 저장
    function insert_history($type, $event_name, $payment_log_seq, $order_id = "-", $spending_point, $adding_point, $spending_accumulate_point, $spending_purchase_point)
    {
        global $connection;
        $sql = "insert into tb_point_history (customer_id, event_name, type, spending_point, adding_point, accumulate_point, purchase_point, payment_log_seq, order_id, update_time, spending_accumulate_point, spending_purchase_point) values ('" . $this->customer_id . "', '" . $event_name . "', '" . $type . "', '" . $spending_point . "', '" . $adding_point . "', '" . $this->accumulate_point . "', '" . $this->purchase_point . "', '" . $payment_log_seq . "', '" . $order_id . "', now(), '" . strval($spending_accumulate_point) . "', '" . strval($spending_purchase_point) . "');";
        $result = mysqli_query($connection, $sql);
        if (mysqli_affected_rows($connection) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function print_stdio()
    {
        echo "고객ID : " . $this->customer_id . "<br>";
        echo "적립포인트 : " . floor($this->accumulate_point) . "<br>";
        echo "구매포인트 : " . floor($this->purchase_point) . "<br>";
        echo "적용일시 : " . $this->update_time . "<br>";
        echo "총포인트 : " . (floor($this->accumulate_point) + floor($this->purchase_point)) . "<br>";
    }

    // 포인트 합
    function get_point()
    {
        if ($this->is_load == false) {
            return -1;
        }
        return $this->accumulate_point + $this->purchase_point;
    }

    // 포인트 사용
    function spend_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            return false;
        }
        $spending_accumulate_point = 0;
        $spending_purchase_point = 0;
        $spend_point = $point;
        if ($this->accumulate_point >= $point) {
            $this->accumulate_point -= $point;
            $spending_accumulate_point = $point;
        } else {
            $spend_point = $point - $this->accumulate_point;
            $spending_accumulate_point = $this->accumulate_point;
            $spending_purchase_point = $spend_point;
            $this->accumulate_point = 0;
            if ($spend_point > 0) {
                $this->purchase_point -= $spend_point;
            }
        }

        $this->insert_history("SPEND", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }

    // 취소 포인트
    function cancel_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            return false;
        }
        $spending_accumulate_point = 0;
        $spending_purchase_point = 0;
        $spend_point = $point;
        if ($this->accumulate_point >= $point) {
            $this->accumulate_point -= $point;
            $spending_accumulate_point = $point;
        } else {
            $spend_point = $point - $this->accumulate_point;
            $spending_accumulate_point = $this->accumulate_point;
            $spending_purchase_point = $spend_point;
            $this->accumulate_point = 0;
            if ($spend_point > 0) {
                $this->purchase_point -= $spend_point;
            }
        }

        $this->insert_history("CANCEL", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }


    function cancel_point_new($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            return false;
        }
        $spending_accumulate_point = $point;
        $spending_purchase_point = 0;
        // error_log('----- cancel_point_new / purchase_point : '.$this->purchase_point);
        // error_log('----- cancel_point_new / accumulate_point : '.$this->accumulate_point);

        $this->insert_history("CANCEL", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }

    // 적립으로 인한 증가
    function add_accumulate_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        $this->accumulate_point += $point;

        $this->insert_history("ACCUMLATE", "-", $payment_log_seq, $order_id, "0", $point, 0, 0);

        return $this->update_point();
    }

    // 포인트 구매로 인한 증가
    function add_purchase_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        $this->purchase_point += $point;

        $this->insert_history("BUY", "-", $payment_log_seq, $order_id, "0", $point, 0, 0);

        return $this->update_point();
    }

    // 이벤트로 인한 적립 point 증가
    function add_accumulate_point_by_event($point, $event_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if ($this->is_able_to_event_point($event_id) == false) {
            return false;
        }
        $this->accumulate_point += $point;

        $this->insert_history("EVENT", $event_id, "0", "-", "0", $point, 0, 0);

        return $this->update_point();
    }

    //  퍼센트 적립
    function add_accumulate_percent_point($price, $percent, $payment_log_seq, $order_id)
    {
        $percent_point = (($price * $percent) / 100);
        //echo $percent_point;
        if ($percent_point > 0) {
            return $this->add_accumulate_point($percent_point, $payment_log_seq, $order_id);
        }
        return true;
    }

    function cancel_accumulate($payment_log_seq, $order_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql = "select * from tb_point_history where customer_id = '" . $this->customer_id . "' and payment_log_seq = '" . $payment_log_seq . "' and type = 'ACCUMLATE';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $point = $row->adding_point;
            $this->cancel_point($point, $payment_log_seq, $order_id);
        } else {
            return true;
        }

        return true;
    }

    function cancel_accumulate_new($payment_log_seq, $order_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql =
            "SELECT * 
        FROM tb_point_history 
        WHERE customer_id = '" . $this->customer_id . "' 
        AND payment_log_seq = '" . $payment_log_seq . "' 
        AND type = 'ACCUMLATE';";
        // error_log('----- cancel_accumulate_new/ $sql : '.$sql);
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $point = $row->adding_point;
            $this->cancel_point_new($point, $payment_log_seq, $order_id);
        } else {
            return true;
        }

        return true;
    }
}
