<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

$clear = array();
if(ctype_alnum($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}


switch($clear['mode']){
    //노쇼등록
    case 'regNoshow':
        $json['flag'] = true;
        $que = "UPDATE tb_payment_log SET is_no_show = 1 WHERE payment_log_seq = {$_POST['seq']}";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    break;
    //특이사항 등록
    case 'etc1':
        $json['flag'] = true;
        $que = "UPDATE tb_payment_log SET etc_memo = '{$_POST['memo']}' WHERE payment_log_seq = {$_POST['seq']}";
        //echo $que;
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        break;
    //점주특이사항 등록
    case 'etc2':
        $json['flag'] = true;
        $que = "SELECT COUNT(*) AS cnt FROM tb_shop_customer_memo WHERE customer_id='{$_POST['cid']}' AND artist_id = '{$_POST['aid']}' AND cellphone = '{$_POST['phone']}' AND is_delete = 2";
        $row = sql_fetch_array($que);
        if($row[0]['cnt']>0){
            $sql = "UPDATE tb_shop_customer_memo SET memo = '{$_POST['memo']}', update_dt = NOW() WHERE customer_id='{$_POST['cid']}' AND artist_id = '{$_POST['aid']}'AND cellphone = '{$_POST['phone']}' ";
            //echo $sql;
            $res = sql_query($sql);
            if(!$res){
                $json['flag'] = false;
            }
        } else {
            $sql = "INSERT INTO tb_shop_customer_memo SET customer_id='{$_POST['cid']}', tmp_seq = '{$_POST['tseq']}', memo = '{$_POST['memo']}', artist_id = '{$_POST['aid']}', cellphone = '{$_POST['phone']}', reg_dt = NOW()";
            //echo $sql;
            $res = sql_query($sql);
            if(!$res){
                $json['flag'] = false;
            }
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        break;
    //점주특이사항 등록
    case 'onlyTimeChange':
        $json['flag'] = true;
        $que = "SELECT CONCAT(year,'-',month,'-',day,' ',hour,':',minute) AS org_date FROM tb_payment_log WHERE payment_log_seq = {$_POST['seq']}";
        $pay = sql_fetch_array($que);
        $json['orgDate'] = $pay[0]['org_date'];


        $start = date('YmdHis',$_POST['startTime']);
        $end = date('YmdHis',$_POST['endTime']);
        $sql = "UPDATE tb_payment_log SET 
                    year = '".substr($start,0,4)."',
                    month = '".intval(substr($start,4,2))."',
                    day = '".intval(substr($start,6,2))."',
                    hour = '".intval(substr($start,8,2))."',
                    minute = '".intval(substr($start,10,2))."',
                    
                    to_hour = '".intval(substr($end,8,2))."',
                    to_minute = '".intval(substr($end,10,2))."',
                    update_time = NOW()
                WHERE payment_log_seq ='{$_POST['seq']}' ";
        //echo $sql;
        $res = sql_query($sql);
        if(!$res) {
            $json['flag'] = false;
        }

        $json['seq'] = $_POST['seq'];
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        break;

}
?>

