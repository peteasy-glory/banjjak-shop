<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();

$user_id = $_SESSION['gobeauty_user_id'];


$is_dog = ($_POST['type']=='dog_service_type')?'개':'고양이';

$dog_product_seq = array(
    'kgs',
    'bath_price',
    'part_price',
    'bath_part_price',
    'sanitation_price',
    'sanitation_bath_price',
    'all_price',
    'spoting_price',
    'scissors_price',
    'summercut_price',
    'beauty1_price',
    'beauty2_price',
    'beauty3_price',
    'beauty4_price',
    'beauty5_price'
);

$dog_product_seq_chk = array(

    'bath_chk',
    'part_chk',
    'bath_part_chk',
    'sanitation_chk',
    'sanitation_bath_chk',
    'all_chk',
    'spoting_chk',
    'scissors_chk',
    'summercut_chk',
    'beauty1_chk',
    'beauty2_chk',
    'beauty3_chk',
    'beauty4_chk',
    'beauty5_chk'
);

//print_r($_POST);
$price_array = array();
$counselor_array = array();
for($i=0;$i<count($dog_product_seq);$i++) {
    if(count($_POST[$dog_product_seq[$i]])>0) {
        for ($j = 0; $j < count($_POST[$dog_product_seq[$i]]); $j++) {
            if(!empty($_POST[$dog_product_seq[$i]][$j])) {
                $price_array[$dog_product_seq[$i]][] = $_POST[$dog_product_seq[$i]][$j];
            }
        }
        //print_r($price_array[$dog_product_seq[$i]]);
        ${$dog_product_seq[$i]} = implode(",",$price_array[$dog_product_seq[$i]]);
    } else {
        $price_array[$dog_product_seq[$i]][] = '';
    }
}

for($i=0;$i<count($dog_product_seq_chk);$i++) {
    if(count($_POST[$dog_product_seq_chk[$i]])>0) {
        //상담요청
        for ($j = 0; $j < count($_POST[$dog_product_seq_chk[$i]]); $j++) {
            //echo $_POST[$dog_product_seq_chk[$i]][$j]." : ";
            $counselor_array[$dog_product_seq_chk[$i]][] = $_POST[$dog_product_seq_chk[$i]][$j];
        }
        //echo "<br>";
        ${$dog_product_seq_chk[$i]} = implode(",",$counselor_array[$dog_product_seq_chk[$i]]);
    } else {
        $counselor_array[$dog_product_seq_chk[$i]][] = '';
    }
}

//echo "part->".$beauty1_chk;
//print_r($bath_chk);
/*print_r($price_array);
print_r($counselor_array);*/

//이모티콘 변경
//$comment = mb_convert_encoding($_POST['add_comment'],'UTF-8','Windows-1252');

//echo "aaa->".$part_price;
//이미 등록된 데이터가 있는지 확인한다.
$direct_title = '';
if($_POST['product_second_type'] == '직접입력'){
    $direct_title = $_POST['product_second_type2'];
    $second_type = '직접입력';
    $que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type']}' AND direct_title = '{$_POST['product_second_type2']}'";
} else {
    $second_type = $_POST['product_second_type'];
    $que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type']}'";
}

//echo $que."<p>";
$res = mysqli_query($connection, $que);
$row = mysqli_fetch_assoc($res);
//echo $row['cnt'];

if($row['cnt']>0){//있으면 업데이트 한다.
    $sql  = "UPDATE tb_product_dog_static SET ";
    $sql .= "first_type                 = '{$is_dog}', ";
    $sql .= "second_type                = '{$second_type}', ";
    $sql .= "direct_title               = '{$direct_title}', ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "kgs                        = '{$kgs}', ";
    $sql .= "bath_price                 = '{$bath_price}', ";
    $sql .= "part_price                 = '{$part_price}', ";
    $sql .= "bath_part_price            = '{$bath_part_price}', ";
    $sql .= "sanitation_price           = '{$sanitation_price}', ";
    $sql .= "sanitation_bath_price      = '{$sanitation_bath_price}', ";
    $sql .= "all_price                  = '{$all_price}', ";
    $sql .= "spoting_price              = '{$spoting_price}', ";
    $sql .= "scissors_price             = '{$scissors_price}', ";
    $sql .= "summercut_price            = '{$summercut_price}', ";
    $sql .= "beauty1_price             = '{$beauty1_price}', ";
    $sql .= "beauty2_price             = '{$beauty2_price}', ";
    $sql .= "beauty3_price             = '{$beauty3_price}', ";
    $sql .= "beauty4_price             = '{$beauty4_price}', ";
    $sql .= "beauty5_price             = '{$beauty5_price}', ";

    $sql .= "is_consult_bath            = '{$bath_chk}', ";
    $sql .= "is_consult_part            = '{$part_chk}', ";
    $sql .= "is_consult_bath_part       = '{$bath_part_chk}', ";
    $sql .= "is_consult_sanitation      = '{$sanitation_chk}', ";
    $sql .= "is_consult_sanitation_bath = '{$sanitation_bath_chk}', ";
    $sql .= "is_consult_all             = '{$all_chk}', ";
    $sql .= "is_consult_spoting         = '{$spoting_chk}', ";
    $sql .= "is_consult_scissors        = '{$scissors_chk}', ";
    $sql .= "is_consult_summercut       = '{$summercut_chk}', ";
    $sql .= "is_consult_beauty1         = '{$beauty1_chk}', ";
    $sql .= "is_consult_beauty2         = '{$beauty2_chk}', ";
    $sql .= "is_consult_beauty3         = '{$beauty3_chk}', ";
    $sql .= "is_consult_beauty4         = '{$beauty4_chk}', ";
    $sql .= "is_consult_beauty5         = '{$beauty5_chk}', ";
    $sql .= "is_over_kgs                = '{$_POST['add_weight']}', ";
    $sql .= "what_over_kgs              = '{$_POST['set_weight']}', ";
    $sql .= "over_kgs_price             = '{$_POST['set_price']}', ";
    $sql .= "add_comment                = '".$emoji->emojiStrToDB($_POST['add_comment'])."', ";
    $sql .= "update_time                = NOW() ";
    if($second_type == '직접입력'){
        $sql .= "WHERE customer_id          = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type']}' AND direct_title = '{$_POST['product_second_type2']}' ";
    }else{
        $sql .= "WHERE customer_id          = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type']}' ";
    }

    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());
} else {
    $sql  = "INSERT INTO tb_product_dog_static SET ";
    $sql .= "customer_id                = '{$user_id}', ";
    $sql .= "first_type                 = '{$is_dog}', ";
    $sql .= "second_type                = '{$second_type}', ";
    $sql .= "direct_title               = '{$direct_title}', ";
    $sql .= "in_shop_product            = '".substr($_POST['offer'],0,1)."', ";
    $sql .= "out_shop_product           = '".substr($_POST['offer'],1,1)."', ";
    $sql .= "kgs                        = '{$kgs}', ";
    $sql .= "bath_price                 = '{$bath_price}', ";
    $sql .= "part_price                 = '{$part_price}', ";
    $sql .= "bath_part_price            = '{$bath_part_price}', ";
    $sql .= "sanitation_price           = '{$sanitation_price}', ";
    $sql .= "sanitation_bath_price      = '{$sanitation_bath_price}', ";
    $sql .= "all_price                  = '{$all_price}', ";
    $sql .= "spoting_price              = '{$spoting_price}', ";
    $sql .= "scissors_price             = '{$scissors_price}', ";
    $sql .= "summercut_price            = '{$summercut_price}', ";
    $sql .= "beauty1_price             = '{$beauty1_price}', ";
    $sql .= "beauty2_price             = '{$beauty2_price}', ";
    $sql .= "beauty3_price             = '{$beauty3_price}', ";
    $sql .= "beauty4_price             = '{$beauty4_price}', ";
    $sql .= "beauty5_price             = '{$beauty5_price}', ";

    $sql .= "is_consult_bath            = '{$bath_chk}', ";
    $sql .= "is_consult_part            = '{$part_chk}', ";
    $sql .= "is_consult_bath_part       = '{$bath_part_chk}', ";
    $sql .= "is_consult_sanitation      = '{$sanitation_chk}', ";
    $sql .= "is_consult_sanitation_bath = '{$sanitation_bath_chk}', ";
    $sql .= "is_consult_all             = '{$all_chk}', ";
    $sql .= "is_consult_spoting         = '{$spoting_chk}', ";
    $sql .= "is_consult_scissors        = '{$scissors_chk}', ";
    $sql .= "is_consult_summercut       = '{$summercut_chk}', ";
    $sql .= "is_consult_beauty1         = '{$beauty1_chk}', ";
    $sql .= "is_consult_beauty2         = '{$beauty2_chk}', ";
    $sql .= "is_consult_beauty3         = '{$beauty3_chk}', ";
    $sql .= "is_consult_beauty4         = '{$beauty4_chk}', ";
    $sql .= "is_consult_beauty5         = '{$beauty5_chk}', ";

    $sql .= "is_over_kgs                = '{$_POST['add_weight']}', ";
    $sql .= "what_over_kgs              = '{$_POST['set_weight']}', ";
    $sql .= "over_kgs_price             = '{$_POST['set_price']}', ";
    $sql .= "add_comment                = '".$emoji->emojiStrToDB($_POST['add_comment'])."', ";
    $sql .= "update_time                = NOW() ";
    //echo $sql."<br>";
    mysqli_query($connection,$sql) or die(mysqli_error());

    $que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type2']}'";
    $res = mysqli_query($connection, $que);
    $row = mysqli_fetch_assoc($res);
    //echo $row['cnt'];

    if($row['cnt']>0){//있으면 업데이트 한다.
        $que = "DELETE FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '{$is_dog}' AND second_type = '{$_POST['product_second_type2']}'";
        $res = mysqli_query($connection, $que);
    }
}

$returl_url = 'set_beauty_management';
if($_POST['backurl'])   $returl_url = $_POST['backurl'];

?>
<script>location.href = '../<?php echo $returl_url;?>';</script>


