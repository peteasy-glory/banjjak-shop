<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);
//echo "aaa->".$part_price;
//이미 등록된 데이터가 있는지 확인한다.
$que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_common WHERE customer_id = '{$user_id}' AND first_type = '개' AND second_type = '추가공통옵션'";
//echo $que."<p>";
$res = mysqli_query($connection, $que);
$row = mysqli_fetch_assoc($res);
//echo $row['cnt'];



//얼굴 만들기
$addition_face = array();
for($i=0;$i<count($_POST['face_add']);$i++){
    if(!empty($_POST['face_add'][$i]) && !empty($_POST['p_fave_price'][$i])) {
        $addition_face[] = $_POST['face_add'][$i] . ":" . $_POST['p_fave_price'][$i];
    }
}
$face = implode(",",$addition_face);

//현장 만들기
$addition_work = array();
for($i=0;$i<count($_POST['out_text']);$i++){
    if(!empty($_POST['out_text'][$i]) && !empty($_POST['out_price'][$i])) {
        $double_array[] = $_POST['out_text'][$i] . ":" . $_POST['out_price'][$i];
    }
}
$work = implode(",",$double_array);


//털길이
$addition_hair = array();
for($i=0;$i<count($_POST['p_beauty_length']);$i++){
    if(!empty($_POST['p_beauty_length'][$i]) && !empty($_POST['p_beauty_length_price'][$i])) {
        $hair_array[] = $_POST['p_beauty_length'][$i] . ":" . $_POST['p_beauty_length_price'][$i];
    }
}
$addition_hair = implode(",",$hair_array);


//기타 만들기
$addition_etc = array();

for($i=0;$i<count($_POST['etc_leg_text']);$i++){
    if(!empty($_POST['etc_leg_text'][$i]) && !empty($_POST['etc_leg_price'][$i])) {
        $etc_array1[] = $_POST['etc_leg_text'][$i] . ":" . $_POST['etc_leg_price'][$i];
    }
}
$etc1 = implode(",",$etc_array1);

for($i=0;$i<count($_POST['spa_text']);$i++){
    if(!empty($_POST['spa_text'][$i]) && !empty($_POST['spa_price'][$i])) {
        $etc_array2[] = $_POST['spa_text'][$i] . ":" . $_POST['spa_price'][$i];
    }
}
//print_r($etc_array2);
$etc2 = implode(",",$etc_array2);

for($i=0;$i<count($_POST['color_text']);$i++){
    if(!empty($_POST['color_text'][$i]) && !empty($_POST['color_price'][$i])) {
        $etc_array3[] = $_POST['color_text'][$i] . ":" . $_POST['color_price'][$i];
    }
}
$etc3 = implode(",",$etc_array3);

for($i=0;$i<count($_POST['etc_text']);$i++){
    if(!empty($_POST['etc_text'][$i]) && !empty($_POST['etc_price'][$i])) {
        $etc_array4[] = $_POST['etc_text'][$i] . ":" . $_POST['etc_price'][$i];
    }
}
$etc4 = implode(",",$etc_array4);



if($row['cnt']>0){//있으면 업데이트 한다.
    $sql  = "UPDATE tb_product_dog_common SET ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "basic_face_price           = '{$_POST['p_basic_face_price']}', ";
    $sql .= "broccoli_price             = '{$_POST['p_broccoli_price']}', ";
    $sql .= "highba_price               = '{$_POST['p_highba_price']}', ";
    $sql .= "bear_price                 = '{$_POST['p_bear_price']}', ";
    $sql .= "toenail_price              = '{$_POST['p_toenail_price']}', ";
    $sql .= "trumpet_etc_price          = '{$_POST['p_trumpet_etc_price']}', ";
    $sql .= "bell_price                 = '{$_POST['p_bell_price']}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price']}', ";
    $sql .= "ferocity_price             = '{$_POST['p_ferocity_price']}', ";
    $sql .= "tick_price                 = '{$_POST['p_tick_price']}', ";
    $sql .= "short_hair_price           = '{$_POST['p_short_hair_price']}', ";
    $sql .= "long_hair_price            = '{$_POST['p_long_hair_price']}', ";
    $sql .= "double_hair_price          = '{$_POST['p_double_hair_price']}', ";
    $sql .= "addition_face_product      = '{$face}', ";
    $sql .= "addition_work_product      = '{$work}', ";
    $sql .= "addition_option_product    = '{$etc1}', ";
    $sql .= "spa_option_product         = '{$etc2}', ";
    $sql .= "dyeing_option_product      = '{$etc3}', ";
    $sql .= "etc_option_product         = '{$etc4}', ";
    $sql .= "hair_length_product        = '{$addition_hair}', ";
    $sql .= "add_comment                = '{$_POST['add_comment']}', ";
    $sql .= "update_time                = NOW() ";
    $sql .= "WHERE customer_id          = '{$user_id}' AND first_type = '개' AND second_type = '추가공통옵션' ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
} else {
    $sql  = "INSERT INTO tb_product_dog_common SET ";
    $sql .= "customer_id                = '{$user_id}', ";
    $sql .= "first_type                 = '개', ";
    $sql .= "second_type                = '추가공통옵션', ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "basic_face_price           = '{$_POST['p_basic_face_price']}', ";
    $sql .= "broccoli_price             = '{$_POST['p_broccoli_price']}', ";
    $sql .= "highba_price               = '{$_POST['p_highba_price']}', ";
    $sql .= "bear_price                 = '{$_POST['p_bear_price']}', ";
    $sql .= "hair_clot_price            = '{$_POST['p_hair_clot_price']}', ";
    $sql .= "ferocity_price             = '{$_POST['p_ferocity_price']}', ";
    $sql .= "tick_price                 = '{$_POST['p_tick_price']}', ";
    $sql .= "short_hair_price           = '{$_POST['p_short_hair_price']}', ";
    $sql .= "long_hair_price            = '{$_POST['p_long_hair_price']}', ";
    $sql .= "double_hair_price          = '{$_POST['p_double_hair_price']}', ";
    $sql .= "addition_face_product      = '{$face}', ";
    $sql .= "addition_work_product      = '{$work}', ";
    $sql .= "addition_option_product    = '{$etc1}', ";
    $sql .= "spa_option_product         = '{$etc2}', ";
    $sql .= "dyeing_option_product      = '{$etc3}', ";
    $sql .= "etc_option_product         = '{$etc4}', ";
    $sql .= "hair_length_product        = '{$addition_hair}', ";
    $sql .= "add_comment                = '{$_POST['add_comment']}', ";
    $sql .= "update_time                = NOW() ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
}

//털/목욕이 있으면 기존 데이터를 모두 삭제하고 추가한다.
if(count($_POST['double_hair'])>0){
    $que = "DELETE FROM tb_product_common_option WHERE customer_id = '{$user_id}' AND type = '목욕' ";
    //echo $que."<p>";
    $res = mysqli_query($connection, $que);
}

for($i=0;$i<count($_POST['double_hair']);$i++) {

    if(!empty($_POST['double_hair'][$i])) {
//        print_r($_POST['double_hair']);
        $sql = "INSERT INTO tb_product_common_option SET ";
        $sql .= "customer_id = '{$user_id}', ";
        $sql .= "type = '목욕', ";
        $sql .= "price = '{$_POST['double_hair_price'][$i]}', ";
        $sql .= "title = '{$_POST['double_hair'][$i]}', ";
        $sql .= "reg_date = NOW() ";
        //echo $sql . "<br>";
        mysqli_query($connection, $sql);
    }
}
$returl_url = 'set_beauty_management_time';
if($_POST['backurl'])   $returl_url = $_POST['backurl'];

?>
<script>location.href = '../<?php echo $returl_url;?>';</script>
