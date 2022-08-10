<?php


class TSql
{
    protected $conn = null;

    public function __construct($conn){
        $this->conn = $conn;
    }

}


class TReserveSql extends TSql{
    private $SQL_CANT_RESERVATION_DAY = "SELECT ph_seq, worker
                                             ,CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0'),' ',LPAD(start_hour,'2','0'),':',LPAD(start_minute,'2','0')) as start_datetime
                                             ,CONCAT(end_year,'-',LPAD(end_month,'2','0'),'-',LPAD(end_day,'2','0'),' ',LPAD(end_hour,'2','0'),':',LPAD(end_minute,'2','0')) as end_datetime
                                            FROM tb_private_holiday 
                                            WHERE customer_id = '%s' 
                                              AND '%s' BETWEEN CONCAT(start_year,LPAD(start_month,'2','0'),LPAD(start_day,'2','0')) AND CONCAT(end_year,LPAD(end_month,'2','0'),LPAD(end_day,'2','0'))";

    private $SQL_CANT_RESERVATION_WEEK = "SELECT ph_seq
                                              ,CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0'),' ',LPAD(start_hour,'2','0'),':',LPAD(start_minute,'2','0')) as start_datetime
                                              ,CONCAT(end_year,'-',LPAD(end_month,'2','0'),'-',LPAD(end_day,'2','0'),' ',LPAD(end_hour,'2','0'),':',LPAD(end_minute,'2','0')) as end_datetime
                                             FROM tb_private_holiday 
                                             WHERE customer_id = '%s' AND worker = '%s' 
                                               AND 
                                                    (
                                                        (
                                                            CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0')) >= '%s'
                                                        AND CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0')) <= '%s'
                                                        )
                                                or
                                                    (
                                                            CONCAT(end_year,'-',LPAD(end_month,'2','0'),'-',LPAD(end_day,'2','0')) >= '%s'
                                                        AND CONCAT(end_year,'-',LPAD(end_month,'2','0'),'-',LPAD(end_day,'2','0')) <= '%s'
                                                        )
                                                    )";

    private $SQL_CANT_RESERVATION_WEEK_NOR = "SELECT * FROM tb_regular_holiday 
                                              WHERE customer_id = '%s'" ;

    private $SQL_CANT_RESERVATION_WEEK_OFF = "SELECT * FROM tb_time_off 
                                              WHERE customer_id = '%s'" ;

    private $SQL_GET_RESERVATION_CHK = "SELECT  A.*, B.name AS pet_name, B.pet_type AS pet_type , C.is_approve AS is_await , C.idx AS approve_seq 
                                        , CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) AS reservation_date
                                        FROM tb_payment_log A INNER JOIN tb_mypet B ON A.pet_seq = B.pet_seq
                                                      LEFT JOIN tb_grade_reserve_approval_mgr C ON A.payment_log_seq = C.payment_log_seq
                                        WHERE A.artist_id = '%s' AND A.is_cancel = 0 AND A.data_delete = 0 AND A.worker = '%s' 
                                                AND CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) >= '%s'
                                                AND CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) < '%s'                                    
                                        ORDER BY A.year ASC , A.month ASC , A.day ASC, A.hour ASC, A.minute ASC"; 

    private $SQL_GET_RESERVATION_CHK_MEMO =    "SELECT GROUP_CONCAT(A.grpMemo  separator '') AS before_memo
                                                FROM ( 
                                                    SELECT  CONCAT(year,'-',LPAD(month, '2','0'),'-',LPAD(day, '2','0'),' ',LPAD(hour, '2','0'),':',LPAD(minute, '2','0')
                                                                , '<br>', etc_memo, '<br>') AS grpMemo
                                                    FROM tb_payment_log 
                                                    WHERE cellphone = '%s' AND artist_id = '%s'
                                                        AND CONCAT(year,LPAD(month, '2','0'),LPAD(day, '2','0'),LPAD(hour, '2','0'),LPAD(minute, '2','0')) <= '%s'
                                                        AND TRIM(etc_memo) != ''
                                                    ORDER BY year DESC , month DESC , day DESC, hour DESC, minute DESC LIMIT 3
                                                ) A";//ORDER BY payment_log_seq DESC  LIMIT 3

    private $SQL_GET_RESERVATION_CHK_DAY = "SELECT  A.*, B.name AS pet_name, B.pet_type AS pet_type , C.is_approve AS is_await , C.idx AS approve_seq 
                                        , CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) AS reservation_date
                                        FROM tb_payment_log A INNER JOIN tb_mypet B ON A.pet_seq = B.pet_seq
                                                      LEFT JOIN tb_grade_reserve_approval_mgr C ON A.payment_log_seq = C.payment_log_seq
                                        WHERE A.artist_id = '%s' AND A.is_cancel = 0 AND A.data_delete = 0
                                                AND CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) >= '%s'
                                                AND CONCAT(A.year,LPAD(A.month, '2','0'),LPAD(A.day, '2','0'),LPAD(A.hour, '2','0'),LPAD(A.minute, '2','0')) < '%s'                                    
                                        ORDER BY A.year ASC , A.month ASC , A.day ASC, A.hour ASC, A.minute ASC";

    private $sql;

    public function __construct($conn){
        parent::__construct($conn);
    }

    public function qry_cant_reservation_day($artistId, $selectDate){
        $this->sql = sprintf($this->SQL_CANT_RESERVATION_DAY, $artistId, $selectDate);
        return $this->sql_fetch_array();
    }

    public function qry_cant_reservation_week($artistId, $worker, $firstDate, $lastDate){
        $this->sql = sprintf($this->SQL_CANT_RESERVATION_WEEK, $artistId, $worker, $firstDate, $lastDate, $firstDate, $lastDate);
        return $this->sql_fetch_array();
    }

    public function qry_cant_reservation_week_nor($artistId){
        $this->sql = sprintf($this->SQL_CANT_RESERVATION_WEEK_NOR, $artistId);
        return $this->sql_fetch_array();
    }

    public function qry_cant_reservation_week_off($artistId){
        $this->sql = sprintf($this->SQL_CANT_RESERVATION_WEEK_OFF, $artistId);
        return $this->sql_fetch_array();
    }

    public function qry_get_reservation_chk_day($artistId, $firstDate, $lastDate){
        $this->sql = sprintf($this->SQL_GET_RESERVATION_CHK_DAY, $artistId, $firstDate, $lastDate);
        return $this->sql_fetch_array();
    }

    public function qry_get_reservation_chk($artistId, $worker, $firstDate, $lastDate){
        $this->sql = sprintf($this->SQL_GET_RESERVATION_CHK, $artistId, $worker, $firstDate, $lastDate);
        return $this->sql_fetch_array();
    }


    public function qry_get_reservation_chk_memo($cellphone, $artist_id, $lastDate){
        $this->sql = sprintf($this->SQL_GET_RESERVATION_CHK_MEMO, $cellphone, $artist_id, $lastDate);
        return $this->sql_fetch_array();
    }

    private function sql_fetch_array(){
        $res = mysqli_query($this->conn, $this->sql);
        while($rows = mysqli_fetch_array($res)){
            $data[] = $rows;
        }
        return $data;
    }
}
