<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Allimtalk.class.php");


$user_id = $_SESSION['gobeauty_user_id'];
//print_r($_POST);

$fname = $_POST['filename'];


// 샵 기본 정보
$query = "SELECT * FROM tb_shop WHERE customer_id = '{$user_id}'";
$result = mysqli_query($connection,$query);
$data = mysqli_fetch_object($result);
$shop_name = $data->name;

$work_list = $work_nick = $empty_date = $empty_time = array();

$que = "SELECT a.empty_date, a.worker, a.empty_datetime, (SELECT nicname FROM tb_artist_list WHERE name = a.worker GROUP BY name) as nicname FROM tb_sale_free_time_temp a WHERE a.artist_id = '{$user_id}'  ORDER BY a.worker ASC, CONCAT(a.empty_date,' ',a.empty_datetime) ASC";
//echo $que;
$sftt = sql_fetch_array($que);

if(count($sftt)>0){
    foreach($sftt as $rs){
        $tmp = explode('|',$rs['empty_date']);
        $work_list[] = $rs['worker'];
        $work_nick[] = $rs['nicname'];
        $ed[$rs['worker']]['date'][] = $rs['empty_date'];
        $ed[$rs['worker']][$rs['empty_date']][] = $rs['empty_datetime'];
    }
}

$work_list = array_unique($work_list);
$work_list = array_values($work_list);

$work_nick = array_unique($work_nick);
$work_nick = array_values($work_nick);

$r = array();
for($i=0;$i<count($work_list);$i++){
    $r = array_unique($ed[$work_list[$i]]['date']);
    $arr = array_values($r);
    sort($arr);
    $ed[$work_list[$i]]['date'] = $arr;
}
/*$empty_date = array_unique($empty_date);
$empty_date = array_values($empty_date);*/

//print_r($ed);
$prev_date = array();
$pcnt = 0;


$time_zone = $_SESSION['week_type'];

// mesaage insert

$msg_que = "
    insert into tb_partner_message set 
        msg_type_idx = {$_POST['msg_type_idx']},
        message = '{$_POST['sel_msg']}',
        reg_date = NOW(),
        is_delete = 0
";
$msg_res = sql_query($msg_que);
if ($msg_res) {
    $msg_id = mysqli_insert_id($connection);
}

$que = "INSERT INTO tb_sale_free_time_mgr SET ";
$que .= "msg_idx    = {$msg_id}, ";
$que .= "message    = '{$_POST['sel_msg']}', ";
$que .= "artist_id  = '{$user_id}',";
$que .= "sale_kind  = {$_POST['sel_sale']}, ";
$que .= "kind_value = {$_POST['price']}, ";
$que .= "photo_path = '{$fname}' ";
//echo $que . '<p>';

$res = sql_query($que);
if ($res) {
    $id = mysqli_insert_id($connection);
}


$pr = explode("|",$_POST['person']);

for($ii=0;$ii<count($pr);$ii++) {

    if(ctype_alnum($pr[$ii])){
        $sql1  = "INSERT INTO tb_sale_free_time_tmp_customer SET ";
        $sql1 .= "tmp_seq = {$pr[$ii]}, ";
    } else {
        $sql1 = "INSERT INTO tb_sale_free_time_customer SET ";
        $sql1 .= "customer_id = '{$pr[$ii]}', ";
    }
    $sql1 .= "mgr_idx = {$id}, ";
    $sql1 .= "send_type = 1, is_delete = 0";
    //echo $sql1."<p>";
    sql_query($sql1);
}
$phone_array = "";
for($jj=0;$jj<count($pr);$jj++) {

    if(ctype_alnum($pr[$jj])){
        $sql_phone  = "select cellphone from tb_tmp_user where  ";
        $sql_phone .= "tmp_seq = {$pr[$jj]} ";
    } else {
        $sql_phone = "select cellphone from tb_customer where  ";
        $sql_phone .= "id = '{$pr[$jj]}' ";
    }
    //echo $sql_phone."<br>";
    $phone_num = sql_fetch_array($sql_phone);
    $phone_array .= $phone_num[0]['cellphone']."|";
}
$phone_array = substr($phone_array, 0, -1);


for ($i = 0; $i < count($work_list); $i++) {//미용사별
    for ($j = 0; $j < count($ed[$work_list[$i]]['date']); $j++) {//일자별

        unset($prev_date);
        $pcnt = 0;
        $start = '';
        $start_cnt = 0;
        //echo count($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]]).'<br>';
        $is_same = 0;
        $grow = array();
        for($k=0;$k<count($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]]);$k++){


            //echo $work_list[$i].' - '.$ed[$work_list[$i]]['date'][$j].' - '.$ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k].'<br>';
            $empty_time_cnt = count($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]]);
            if ($empty_time_cnt == 1) {
                $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0];
                $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i', strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800);
                //echo $start . ' : ' . $end . '<p>';

                $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                $grow = sql_fetch_array($que);
                if(!$grow[0]['cnt']) {
                    $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                    //echo $que . "<p>";
                    sql_query($que);
                }
            } else if ($empty_time_cnt == 2) {
                //echo (strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800) . ' : ' . strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1]) . '<br>';
                if ($k == 0) {
                    //echo (strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800).'---'.strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1]).'<br>';
                    if (strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800 != strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1])) {
                        $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0];
                        $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i', strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800);
                        //echo $start . ' : ' . $end . '<p>';
                        $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                        $qrow = sql_fetch_array($que);
                        if(!$grow[0]['cnt']) {
                            $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                            //echo $que . "<p>";
                            sql_query($que);
                        }
                    } else if (strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0]) + 1800 == strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1])) {
                        $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0];
                    }
                } else {
                    $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i', strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1]) + 1800);
                    $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                    $grow = sql_fetch_array($que);
                    if(!$grow[0]['cnt']) {
                        $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                        //echo $que . "<p>";
                        sql_query($que);
                    }

                }
            } else {
                //echo 'pcnt->' . $pcnt . '<br>';
                //echo 'k 2 이상'."<br>";
                if ($k == 0) {
                    $prev_date[$pcnt] = strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k]) + 1800;
                    //echo '시작값 -> '.$prev_date[0] . '<br>';
                    $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0];
                    if(strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0])+1800 != strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][1])){
                        $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i',strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][0])+1800);
                        $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                        $grow = sql_fetch_array($que);
                        if(!$grow[0]['cnt']) {
                            $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                            //echo $que . "<p>";
                            sql_query($que);
                            unset($start);
                        }
                    }
                } else if($k > 0){
                    //echo 'k가 0 이상'.'<br>';
                    if(empty($start)){
                        if($is_same==0){
                            $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k];
                        } else {
                            $start = $ed[$work_list[$i]]['date'][$j] . ' ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k-1];
                        }

                    }

                    if((strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k-1])+1800) != strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k])){
                        //echo '같지않다.<br>';
                        //echo 'k->'.date('H:i',(strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k-1])+1800)) .'!='. date('H:i',strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k])).'<br>';

                        //echo 'is_same->'.$is_same.'<br>';
                        if($is_same==0){
                            $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i',strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k])+1800);
                        } else {
                            $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i',strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k-1])+1800);
                        }

                        $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                        $grow = sql_fetch_array($que);
                        if(!$grow[0]['cnt']) {
                            $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                            //echo $que . "<p>";
                            sql_query($que);
                            unset($start);
                        }
                        //$is_same = 0;
                    } else {
                        //echo '같다<br>';
                        $is_same++;
                        if($empty_time_cnt == ($k+1)){
                            $end = $ed[$work_list[$i]]['date'][$j] . ' ' . date('H:i',strtotime($ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k])+1800);
                            $que = "SELECT COUNT(*) AS cnt FROM tb_sale_free_time_product WHERE mgr_idx = {$id} AND time_zone = '{$time_zone}' AND worker = '{$work_list[$i]}' AND st_date = '{$start}' AND fi_date = '{$end}'";
                            $qrow = sql_fetch_array($que);
                            if(!$grow[0]['cnt']) {
                                $que = "INSERT INTO tb_sale_free_time_product SET mgr_idx = {$id}, time_zone = '{$time_zone}', worker = '{$work_list[$i]}', st_date = '{$start}', fi_date = '{$end}' ";
                                //echo $que . "<p>";
                                sql_query($que);
                                unset($start);
                            }
                        }
                    }
                }
                //echo 'pcnt->' . $pcnt . '<br>';
                $pcnt++;
                //echo $ed[$work_list[$i]]['date'][$j] . ' / ' . $ed[$work_list[$i]][$ed[$work_list[$i]]['date'][$j]][$k] . '<br>';
            }
        }
    }
}

$select_temp = "SELECT DATE_FORMAT(empty_date,'%m-%d') date FROM tb_sale_free_time_temp WHERE artist_id = '{$user_id}' GROUP BY empty_date";
$temp_array = sql_fetch_array($select_temp);
$allim_date = "";
foreach($temp_array as $index=>$value){
    if($index == 0){
        $allim_date .= $value['date'];
    }else{
        $allim_date .= " / ".$value['date'];
    }
}

$que = "DELETE FROM tb_sale_free_time_temp WHERE artist_id = '{$user_id}'";
sql_query($que);

// 알림톡발송
$talk = new Allimtalk();

$allim_talk = explode("|",$phone_array);

for($ai=0;$ai<count($allim_talk);$ai++) {

    $talk->cellphone = $allim_talk[$ai];
    $talkCustomerName = substr($allim_talk[$ai], -4);
    $talkBtnLink = "https://customer.banjjakpet.com/allim/empty_info?no=".$id;
    $talkResult = $talk->sendEmptytimeReservation($talkCustomerName, $shop_name, $allim_date, $talkBtnLink);

}



$_SESSION['empty_reg'] = 'Y';
?>
<script>
    location.href = '../<?php echo $_SESSION['backurl1'];?>';
</script>