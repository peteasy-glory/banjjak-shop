<?php
ini_set('display_errors',1);
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$artist_id = $_POST['artist_id'];
$is_phone = false;
if(ctype_digit($_POST['search'])){
    $is_phone = true;
    $search = str_replace("-","",$_POST['search']);
} else {
    $is_phone = false;
    $search = str_replace("-","",$_POST['search']);
}

$worker = $_POST['worker'];
$workTime = $_POST['workTime'];
$workDate = $_POST['workDate'];

//print_r($_POST);
$html = '';

$sql = "
        SELECT 
                customer_id, artist_id, pet_seq, sum(if(is_no_show=1,1,0)) AS no_show_cnt, cellphone                
        FROM 
             tb_payment_log 
        WHERE artist_id = '{$artist_id}' 
        AND data_delete = '0' 
		";
if($is_phone ==  true){
    $sql .= "  AND cellphone LIKE '%{$search}%' GROUP BY cellphone ";
} else {
    $sql .= "  AND SUBSTRING_INDEX(product,'|',1) LIKE '%{$search}%' GROUP BY pet_seq ";
}
//echo $sql;
$arr = sql_fetch_array($sql);
if(count($arr)>0){
    foreach($arr as $rs){
//        print_r($rs);
        $que = "SELECT a.*, b.file_path FROM tb_mypet a LEFT JOIN tb_mypet_beauty_photo b ON a.pet_seq = b.pet_seq WHERE a.pet_seq = '{$rs['pet_seq']}' ORDER BY upload_dt DESC LIMIT 1";
        //echo $que."<br>";
        $res = sql_query($que);
        $row = sql_fetch($res);
        if($row['pet_seq']){
            //echo $row['photo'];
            $html .= '<a href="./reserve_accept_1_input?customer_id='.$rs['customer_id'].'&cellPhone='.$rs['cellphone'].'&worker='.$worker.'&workTime='.$workTime.'&workDate='.$workDate.'&no='.$row['pet_seq'].'&searchTxt='.$search.'" class="customer-card-item pet">';
            $html .= '    <div class="item-info-wrap">';
            $html .= '        <div class="item-thumb">';
            if(empty($row['photo']) && empty($row['file_path'])) {
                $html .= '            <!-- 이미지 없을때 -->';
                $html .= '            <div class="user-thumb large" style="background-image: none;">';
                if($row['type'] == 'dog') {
                    $html .= '              <img src="../../static/pub/images/icon/icon-pup-select-off.png" alt=""></div>';
                }else{
                    $html .= '              <img src="../../static/pub/images/icon/icon-cat-select-off.png" alt=""></div>';
                }
            } else if(empty($row['file_path'])) {
                $html .= '            <!-- 이미지 있을때 -->';
                $html .= '            <div class="user-thumb large"><img src="'."https://image.banjjakpet.com".img_link_change($row['photo']).'" alt=""></div>';
            } else {
                $html .= '            <div class="user-thumb large"><img src="'."https://image.banjjakpet.com".img_link_change($row['file_path']).'" alt=""></div>';
            }
            $html .= '        </div>';
            $html .= '        <div class="item-data">';
            $html .= '            <div class="item-data-inner">';
            $html .= '                <div class="item-pet-name">'.$row['name'].'<div class="label label-yellow middle"><strong>'.$row['pet_type'].'</strong></div></div>';
            $html .= '                <div class="item-pet-date">'.$rs['cellphone'].'</div>';
            if($rs['no_show_cnt']>0) {
                $html .= '                <div class="item-pet-point"><div class="label label-outline-pink label-noshow">NO SHOW '.$rs['no_show_cnt'].'회</div></div>';
            }
            $html .= '            </div>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '</a>';

        }
    }
}

echo $html;
?>

