<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$artist_flag = (isset($_SESSION['artist_flag'])) ? $_SESSION['artist_flag'] : "";

if ($artist_flag === true) {
    $artist_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
    $user_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
    $user_name = (isset($_SESSION['shop_user_nickname'])) ? $_SESSION['shop_user_nickname'] : "";
} else {
    $artist_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
    $user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";
}

print_r($_POST);

$r_artist_id = $artist_id;
$ymd    = explode("-", $_POST['log_date']);
$his    = explode(":", $_POST['log_start_time']);

$r_year = $ymd[0];
$r_month = $ymd[1];
$r_day = $ymd[2];
$r_h = $his[0];
$r_m = $his[0];


$where_qy = "";

if($r_artist_id != ""){
    $where_qy .= " AND artist_id = '".$r_artist_id."' ";
}

if($r_year != ""){
    $where_qy .= " AND year = '".$r_year."' ";
}

if($r_month != ""){
    $where_qy .= " AND month = '".$r_month."' ";
}

if($r_day != ""){
    $where_qy .= " AND day = '".$r_day."' ";
}

if($r_h != "" && $r_m != ""){
    $where_qy .= " AND to_hour <= '".$r_h."' and  to_minute <= '".$r_h."'";
}

/*


       
        "status":"POS",
        "top_region":null,
        "middle_region":null,
        "bottom_region":null,
        
        "worker":"강현주",
        "year":"2022",
        "month":"1",
        "day":"11",
        "hour":"12",
        "minute":"30",
        "to_hour":"14",
        "to_minute":"30",
        

        "to_year":null,
        "to_month":null,
        "to_day":null,
        
        

        */
echo $where_qy;
exit;

if($where_qy != ""){
    $sql = "
        SELECT *
            , SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',1),'|',-1) AS pet_name
            , SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',5),'|',-1) AS service
        FROM tb_payment_log
        WHERE is_cancel = '0'
            ".$where_qy."
        ORDER BY worker ASC, year ASC, month ASC, day ASC, hour ASC, minute ASC
    ";
    echo $sql;
    $result = mysqli_query($connection, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }

    $return_data = array("code" => "000000", "data" => $data, "total_cnt" => mysqli_num_rows($result)); // , "sql" => $sql
}else{
    $return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");			
}


echo json_encode($_POST);
?>