<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();

$user_id = $_SESSION['gobeauty_user_id'];

$clear = array();
if(ctype_alpha($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

switch($clear['mode']){
    case 'beautyCouponAdd'://미용 쿠폰 등록시
        $product_type = $_POST['product_type'];

//        print_r($_POST);
        $coupon_cnt = count($_POST['coupon_name']);

        //쿠폰상품이 있는지 확인하고 있으면 기존 데이터를 삭제하고
        //이미 등록된 데이터가 있는지 확인한다.
        for($i=0;$i<count($_POST['coupon_name']);$i++) {
            if(!empty($_POST['coupon_name'][$i])) {
                if ($coupon_cnt > 0) {
                    if(empty($_POST['no'][$i])){
                        $sql = "INSERT INTO tb_coupon SET ";
                        $sql .= "customer_id     = '{$user_id}', ";
                        $sql .= "product_type    = '{$product_type}', ";
                        $sql .= "type            = 'C', ";
                        $sql .= "name            = '{$emoji->emojiStrToDB($_POST['coupon_name'][$i])}', ";
                        $sql .= "given           = '{$_POST['given'][$i]}', ";
                        $sql .= "price           = '{$_POST['price'][$i]}', ";
                        $sql .= "reg_date                = NOW() ";
                        //echo $sql . "<br>";
                    } else {
                        $sql = "UPDATE tb_coupon SET ";
                        $sql .= "customer_id     = '{$user_id}', ";
                        $sql .= "product_type    = '{$product_type}', ";
                        $sql .= "type            = 'C', ";
                        $sql .= "name            = '{$emoji->emojiStrToDB($_POST['coupon_name'][$i])}', ";
                        $sql .= "given           = '{$_POST['given'][$i]}', ";
                        $sql .= "price           = '{$_POST['price'][$i]}', ";
                        $sql .= "update_date     = NOW() ";
                        $sql .= " WHERE coupon_seq = {$_POST['no'][$i]} ";
                        //echo $sql . "<br>";
                    }
                    sql_query($sql);
                }
            }
        }

        //쿠폰 코멘트저장
        $que = "SELECT COUNT(*) AS cnt FROM tb_coupon_memo WHERE customer_id = '{$user_id}' ";
        $res = sql_query($que);
        $row = sql_fetch($res);
        if(empty($row['cnt'])){
            $sql = "INSERT INTO tb_coupon_memo SET customer_id = '{$user_id}', coupon_memo = '{$emoji->emojiStrToDB($_POST['coupon_memo'])}'";
        } else {
            $sql = "UPDATE tb_coupon_memo SET coupon_memo = '{$emoji->emojiStrToDB($_POST['coupon_memo'])}' WHERE customer_id = '{$user_id}' ";
        }
        sql_query($sql);


        $flat_cnt = count($_POST['flat_name']);
        for($i=0;$i<count($_POST['flat_name']);$i++) {
            if (!empty($_POST['coupon_name'][$i])) {
                //이미 등록된 데이터가 있는지 확인한다.
                if ($flat_cnt > 0) {
                    if(empty($_POST['flat_no'][$i])){
                        $sql = "INSERT INTO tb_coupon SET ";
                        $sql .= "customer_id     = '{$user_id}', ";
                        $sql .= "product_type    = '{$product_type}', ";
                        $sql .= "type            = 'F', ";
                        $sql .= "name            = '{$emoji->emojiStrToDB($_POST['flat_name'][$i])}', ";
                        $sql .= "given           = '{$_POST['flat_given'][$i]}', ";
                        $sql .= "price           = '{$_POST['flat_price'][$i]}', ";
                        $sql .= "reg_date                = NOW() ";
                        //echo $sql . "<br>";
                    } else {
                        $sql = "UPDATE tb_coupon SET ";
                        $sql .= "customer_id     = '{$user_id}', ";
                        $sql .= "product_type    = '{$product_type}', ";
                        $sql .= "type            = 'F', ";
                        $sql .= "name            = '{$emoji->emojiStrToDB($_POST['flat_name'][$i])}', ";
                        $sql .= "given           = '{$_POST['flat_given'][$i]}', ";
                        $sql .= "price           = '{$_POST['flat_price'][$i]}', ";
                        $sql .= "update_date     = NOW() ";
                        $sql .= " WHERE coupon_seq = {$_POST['flat_no'][$i]} ";
                        //echo $sql . "<br>";
                    }
                    sql_query($sql);
                }
            }
        }

        //쿠폰 코멘트저장
        $que = "SELECT COUNT(*) AS cnt FROM tb_coupon_memo WHERE customer_id = '{$user_id}' ";
        $res = sql_query($que);
        $row = sql_fetch($res);
        if(empty($row['cnt'])){
            $sql = "INSERT INTO tb_coupon_memo SET customer_id = '{$user_id}', flat_memo = '{$emoji->emojiStrToDB($_POST['flat_memo'])}', reg_date = NOW()";
        } else {
            $sql = "UPDATE tb_coupon_memo SET flat_memo = '{$emoji->emojiStrToDB($_POST['flat_memo'])}', update_date = NOW() WHERE customer_id = '{$user_id}' ";
        }
        //echo $sql."<br>";
        sql_query($sql);
        echo "<script>location.href = '../set_beauty_coupon_add';</script>";
    break;

    //데이터 한개 삭제
    case 'deleteOne':
        $json['flag'] = true;
        $clear = array();
        if(ctype_alnum($_POST['no'])){
            $clear['no'] = $_POST['no'];
        }
        $que = "UPDATE tb_coupon SET del_yn = 'Y' WHERE coupon_seq = {$clear['no']}";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            exit;
        }
        echo json_encode($json);

    break;


}

?>

