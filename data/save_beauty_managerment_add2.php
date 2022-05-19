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


//무게당 추가 금액 설정

sort($_POST['section']);

$section = implode(',',$_POST['section']);
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
for($i=0;$i<count($_POST['p_ferocity_text']);$i++){
    if(!empty($_POST['p_ferocity_text'][$i]) && !empty($_POST['p_ferocity_price'][$i])) {
        $out_array[] = $_POST['p_ferocity_text'][$i] . ":" . $_POST['p_ferocity_price'][$i];
    }
}
//print_r($out_array);
$outprice = implode(",",$out_array);

if($row['cnt']>0){//있으면 업데이트 한다.
    $sql  = "UPDATE tb_product_cat SET ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "short_price                = '{$_POST['p_short_price']}', ";
    $sql .= "long_price                 = '{$_POST['p_long_price']}', ";
    $sql .= "increase_section_price     = '{$_POST['increase_section_price']}', ";
    $sql .= "section                    = '{$section}', ";
    $sql .= "shower_price               = '{$_POST['p_shower_price']}', ";
    $sql .= "shower_price_long          = '{$_POST['p_shower_price_long']}', ";
    $sql .= "toenail_price              = '{$_POST['p_toenail_price'][0]}', ";
    $sql .= "addition_option_product    = '{$addition}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price'][0]}', ";
    $sql .= "ferocity_price             = '', ";
    $sql .= "addition_work_product      = '{$outprice}', ";
    $sql .= "is_use_weight              = '{$_POST['time2']}', ";
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
    $sql .= "short_price                = '{$_POST['p_short_price']}', ";
    $sql .= "long_price                 = '{$_POST['p_long_price']}', ";
    $sql .= "increase_section_price     = '{$_POST['increase_section_price']}', ";
    $sql .= "section                    = '{$section}', ";
    $sql .= "shower_price               = '{$_POST['p_shower_price']}', ";
    $sql .= "shower_price_long          = '{$_POST['p_shower_price_long']}', ";
    $sql .= "toenail_price              = '{$_POST['p_toenail_price'][0]}', ";
    $sql .= "addition_option_product    = '{$addition}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price'][0]}', ";
    $sql .= "ferocity_price             = '', ";
    $sql .= "addition_work_product      = '{$outprice}', ";
    $sql .= "is_use_weight              = '{$_POST['time2']}', ";
    $sql .= "add_comment                = '{$_POST['add_comment']}', ";
    $sql .= "update_time                = NOW() ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
}


$returl_url = 'set_beauty_management_time';
if($_POST['backurl'])   $returl_url = $_POST['backurl'];

?>
<script>location.href = '../<?php echo $returl_url;?>';</script>
