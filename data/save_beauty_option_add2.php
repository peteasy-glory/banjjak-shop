<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);
//echo "aaa->".$part_price;
//이미 등록된 데이터가 있는지 확인한다.
$que = "SELECT COUNT(*) AS cnt FROM tb_product_cat WHERE customer_id = '{$user_id}' AND first_type = '고양이' AND second_type = '미용'";
//echo $que."<p>";
$res = mysqli_query($connection, $que);
$row = mysqli_fetch_assoc($res);
//echo $row['cnt'];

//추가상품 만들기
$addition_array = array();
for($i=0;$i<count($_POST['extra_text']);$i++){
    if(!empty($_POST['extra_text'][$i]) && !empty($_POST['extra_price'][$i])) {
        $addition_array[] = $_POST['extra_text'][$i] . ":" . $_POST['extra_price'][$i];
    }
}
$addition = implode(",",$addition_array);

//현장판매 만들기
$out_array = array();
for($i=0;$i<count($_POST['out_text']);$i++){
    if(!empty($_POST['out_text'][$i]) && !empty($_POST['out_price'][$i])) {
        $out_array[] = $_POST['out_text'][$i] . ":" . $_POST['out_price'][$i];
    }
}
//print_r($out_array);
$outprice = implode(",",$out_array);

if($row['cnt']>0){//있으면 업데이트 한다.
    $sql  = "UPDATE tb_product_cat SET ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "toenail_price              = '{$_POST['p_toenail_price']}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price']}', ";
    $sql .= "ferocity_price             = '{$_POST['p_ferocity_price']}', ";
    $sql .= "tick_price                 = '{$_POST['p_tick_price']}', ";
    $sql .= "addition_option_product    = '{$addition}', ";
    $sql .= "addition_work_product      = '{$outprice}', ";
    $sql .= "add_comment                = '{$_POST['add_comment']}', ";
    $sql .= "update_time                = NOW() ";
    $sql .= "WHERE customer_id          = '{$user_id}' AND first_type = '고양이' AND second_type = '미용' ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
} else {
    $sql  = "INSERT INTO tb_product_cat SET ";
    $sql .= "customer_id                = '{$user_id}', ";
    $sql .= "first_type                 = '고양이', ";
    $sql .= "second_type                = '미용', ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "toenail_price              = '{$_POST['p_toenail_price']}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price']}', ";
    $sql .= "ferocity_price             = '{$_POST['p_ferocity_price']}', ";
    $sql .= "tick_price                 = '{$_POST['p_tick_price']}', ";
    $sql .= "addition_option_product    = '{$addition}', ";
    $sql .= "addition_work_product      = '{$outprice}', ";
    $sql .= "add_comment                = '{$_POST['add_comment']}', ";
    $sql .= "update_time                = NOW() ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
}
$returl_url = 'set_beauty_management_time';
if($_POST['backurl'])   $returl_url = $_POST['backurl'];

?>
<script>location.href = '../<?php echo $returl_url;?>';</script>
